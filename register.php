<?php
$titlePage = "Inscription";
// Traitement du formulaire d'inscription ici

require_once("components/connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $luckyNumber = htmlspecialchars($_POST['luckyNumber']);


    $formIsCorrect = checkForm($username, $password, $confirmPassword, $luckyNumber);
    // Vous pouvez ajouter ici le code pour enregistrer les données dans une base de données, par exemple

    // Redirection vers une page de succès ou autre
    header("Location: inscription_reussie.php");
    exit();
}

function checkForm($username, $pwd, $confirmPwd, $lckNb)
{
    $validForm = true;

    $requete = "SELECT username FROM users WHERE username = :user LIMIT 1";
    $stmt = $connexion->prepare($requete);
    $stmt->bindParam(':user', $username, PDO::PARAM_STR);
    $stmt->execute();

    $nbLignes = $stmt->rowCount();

    //L'username doit être unique en BDD
    if ($nbLignes > 0) {
        $validForm = false;
    }

    //L'username doit être MAJUSCULES minuscules chiffres underscores uniquement
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $validForm = false;
    }

    //Les deux mots de passe doivent être identiques
    if ($pwd !== $confirmPwd) {
        $validForm = false;
    }

    //Le mot de passe doit faire plus de 6 caractères
    if (strlen($pwd) < 6) {
        $validForm = false;
    }

    //Le numéro chance doit être entre 1 et 9
    if (!($lckNb >=1 && $lckNb <=9)) {
        $validForm = false;
    }

    //Si le formulaire n'est pas valide suite aux vérifs, on renvoie au register
    if(!$validForm) {
        header("Location: register.php?error=invalidInput");
    }
    var_dump($_POST);die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">
    <h2>Formulaire d'Inscription</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="username" placeholder="Nom d'utilisateur *" required>
        <input type="password" name="password" placeholder="Mot de passe *" required>
        <input type="password" name="confirmPassword" placeholder="Confirmer le mot de passe *" required>
        <label for="luckyNumber">Votre numéro chance favori : *</label>
        <select name="luckyNumber" id="luckyNumber" required>
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

<?php include "components/footer.php";?>

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