<?php
// Traitement du formulaire d'inscription ici
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    // Vous pouvez ajouter ici le code pour enregistrer les données dans une base de données, par exemple

    // Redirection vers une page de succès ou autre
    header("Location: inscription_reussie.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Page</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        nav {
            background-color: #007BFF;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 0 5px;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 400px;
            margin: 100px auto 100px auto;
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

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .file-input-wrapper .btn-upload {
            border: 2px solid #007BFF;
            color: #007BFF;
            background-color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 20px;
        }

        .file-input-wrapper .btn-upload:hover {
            background-color: #007BFF;
            color: #fff;
        }

        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }

        p {
            margin-top: 20px;
        }

        .italic{
            font-style: italic;
            color: darkgrey;
        }

    </style>
</head>
<body>

<?php include "header.php";?>
<div class="container">
    <h2>Formulaire d'Inscription</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="username" placeholder="Nom d'utilisateur *" required>
        <input type="password" name="password" placeholder="Mot de passe *" required>
        <input type="password" name="password" placeholder="Confirmer le mot de passe *" required>
        <label for="numericValue">Votre numéro chance favori : *</label>
        <select name="numericValue" id="numericValue" required>
            <?php
            for ($i = 1; $i <= 9; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
            ?>
        </select>
        <p>Votre photo de profil :</p>
        <div class="file-input-wrapper">
            <label class="btn-upload">Choisir un fichier
                <input type="file" name="image" accept="image/*" id="imageInput">
            </label>
        </div>
        <div class="file-input-info" id="fileInfo"></div>
        <div id="preview"></div>
        <input type="submit" value="S'inscrire">
    </form>
    <p>Déjà un compte? <a href="index.php">Connectez-vous ici!</a></p>
    <p class="italic">*Les champs marqués d'un astérisque sont obligatoires.</p>
</div>

<?php include "footer.php";?>

</body>
<script>
    // Fonction pour afficher les informations du fichier et l'aperçu
    function showFileInfo() {
        var input = document.getElementById('imageInput');
        var info = document.getElementById('fileInfo');
        var preview = document.getElementById('preview');

        // Vérifiez si un fichier a été choisi
        if (input.files.length > 0) {
            var file = input.files[0];
            var fileSize = (file.size / 1024).toFixed(2) + ' KB';

            info.innerHTML = 'Nom du fichier : ' + file.name + '<br> Taille du fichier : ' + fileSize;

            // Afficher un aperçu de l'image
            if (file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu"  style="max-height: 100px; max-width: 100px; margin-bottom: 10px;">';
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        } else {
            info.innerHTML = '';
            preview.innerHTML = '';
        }
    }

    // Ajouter un écouteur d'événements pour détecter les changements dans le champ de fichier
    document.getElementById('imageInput').addEventListener('change', showFileInfo);
</script>
</html>