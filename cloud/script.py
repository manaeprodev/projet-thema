import pandas as pd
import numpy as np
import pickle
import keras
import json

from sklearn.model_selection import train_test_split
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense
from tensorflow.keras.optimizers import Adam
from keras import ops
from google.cloud import storage
from google.cloud.storage import blob


### Prediction Tirage

# Importer le fichier CSV
lottery_data = pd.read_csv('query_result.csv')

data = lottery_data[['boule_1','boule_2','boule_3','boule_4','boule_5',]]
dataset = data.values.tolist()

num_numbers = len(dataset[0]) 
sequence_length = 10  # Longueur de la séquence pour la prédiction

# Préparation des données pour l'entraînement du modèle
X = []
y = []
for i in range(len(dataset) - sequence_length):
    X.append(dataset[i:i+sequence_length])
    y.append(dataset[i+sequence_length])

X = np.array(X)
y = np.array(y)

# Diviser les données en ensembles d'entraînement et de test
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Création du modèle RNN
model = Sequential()
model.add(LSTM(64, input_shape=(sequence_length, num_numbers), return_sequences=True))
model.add(LSTM(64, dropout=0.1, return_sequences=False))
model.add(Dense(num_numbers))

# Compilation du modèle
optimizer = Adam(learning_rate=0.001)
model.compile(optimizer=optimizer, loss='mse')

# Entraînement du modèle
model.fit(X_train, y_train, epochs=10, batch_size=32, validation_split=0.1)


# Évaluation du modèle
loss = model.evaluate(X_test, y_test)
print(f'Loss: {loss}')

#Export du modèle
filename = 'model1.keras'
model.save(filename)

# Prédiction sur de nouvelles données
new_data = np.array([data[-sequence_length:]]) 
prediction = model.predict(new_data).astype(int)
print(f'Prédiction: {prediction}')



### Prediction Numero Chance

import_luck_data = pd.read_csv('query_luck_result.csv')

luck_data = import_luck_data[['numero_chance']]
luck_dataset = luck_data.values.tolist()

luck_numbers = len(luck_dataset[0]) 
sequence_length = 10  # Longueur de la séquence pour la prédiction

# Préparation des données pour l'entraînement du modèle
X = []
y = []
for i in range(len(luck_dataset) - sequence_length):
    X.append(luck_dataset[i:i+sequence_length])
    y.append(luck_dataset[i+sequence_length])

X = np.array(X)
y = np.array(y)

# Diviser les données en ensembles d'entraînement et de test
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Création du modèle RNN
model = Sequential()
model.add(LSTM(64, input_shape=(sequence_length, luck_numbers), return_sequences=True))
model.add(LSTM(64, dropout=0.1, return_sequences=False))
model.add(Dense(luck_numbers))

# Compilation du modèle
optimizer = Adam(learning_rate=0.001)
model.compile(optimizer=optimizer, loss='mse')

# Entraînement du modèle
model.fit(X_train, y_train, epochs=1500, batch_size=32, validation_split=0.1)

# Évaluation du modèle
loss = model.evaluate(X_test, y_test)
print(f'Loss: {loss}')

#Export du modèle
filename = 'model1.pkl'
pickle.dump(model, open(filename, 'wb'))

# Prédiction sur de nouvelles données
new_data = np.array([luck_data[-sequence_length:]])  
luck_prediction = model.predict(new_data).astype(int)
print(f'Prédiction: {luck_prediction}')



### Ecriture dans un fichier JSon


data = {
  "prediction": prediction.tolist(),
  "numero_chance": luck_prediction.tolist()
}
with open('predeection.json', 'w') as f:
    json.dump(data, f, indent=4)


### Ecriture du fichier de resultat json dans un bucket GCP

client = storage.Client(project='predeect-410808')
bucket = client.get_bucket('predeections')
blob = bucket.blob('predeection.json')
with open('predeection.json', 'rb') as f:
  blob.upload_from_file(f)
