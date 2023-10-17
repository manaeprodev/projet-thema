<?php

require_once("connexion.php"); // Inclure le fichier de connexion

$requete = "SELECT last_connected_date FROM admin ORDER BY last_connected_date DESC LIMIT 1";
$resultat = $connexion->query($requete);

if ($resultat->num_rows == 1) {
    $row = $resultat->fetch_assoc();
    $lastConnectedDate = $row['last_connected_date'];
    echo "Dernière date de connexion : $lastConnectedDate";
} else {
    // L'utilisateur n'est pas authentifié
    echo "Échec de la connexion. Vérifiez votre nom d'utilisateur et votre mot de passe.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin-top: 10%;
        }


        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
<h2>Connexion</h2>
<form action="user.php" method="post">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Se connecter">
</form>
</div>
<div class="container">
    <h2>Date de dernière activité sur des Admins</h2>
    <h3><?= $lastConnectedDate?></h3>
</div>
</body>
</html>

