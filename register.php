<?php
$titlePage = "Inscription";
$uploadDirectory = './assets/userPfp/';

// Traitement du formulaire d'inscription ici
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération des données du formulaire
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirmPassword = htmlspecialchars($_POST["confirmPassword"]);
    $luckyNumber = htmlspecialchars($_POST["luckyNumber"]);
    $imagePath = date('YmdHis') . htmlspecialchars($_FILES['image']['name']);

    if (!isset($username) || !isset($password) || !isset($confirmPassword)|| !isset($luckyNumber)) {
        header("Location: register.php?error=1");
    }

    //$formIsCorrect = checkForm($username, $password, $confirmPassword, $luckyNumber);
    $formIsCorrect = true;
    if ($formIsCorrect) {

        if ($imagePath != '' && isset($_FILES['image'])) {
            saveImage($_FILES['image'], $uploadDirectory, $imagePath);
        }

        createUser($username, $password, $luckyNumber, $imagePath);

        header("Location: inscription_reussie.php");
    } else {
        header("Location: inscription_reussie.php");
    }

    exit();
}

function checkForm($username, $pwd, $confirmPwd, $lckNb)
{
    $validForm = true;

    require_once("./components/connexion.php");
    $requete = "SELECT username FROM users WHERE username = ? LIMIT 1";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $nbLignes = $result->num_rows;

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

    return $validForm;

}

function createUser($username, $password, $luckyNumber, $imagePath)
{
    //Insertion en base
    $requete = "INSERT INTO users (username, password, luckyNumber, pfp, createdDate, lastUpdatedDate)
VALUES (:username, :password, :luckyNumber, :image, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";

    require_once("./components/connexion.php");
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('ssis', $username, $password, $luckyNumber, $imagePath);
    $stmt->execute();

}

function saveImage($image, $uploadDirectory, $imagePath)
{
    $uploadFile = $uploadDirectory . $imagePath;

    $check = getimagesize($image["tmp_name"]);
    //Enregistrement de l'image
    if ($check !== false) {
        // Vérifier si le fichier existe déjà
        if (file_exists($uploadFile)) {
            echo "Le fichier existe déjà.";
        } else {
            echo $uploadFile;
            // Déplacer le fichier téléchargé vers l'emplacement souhaité
            if (move_uploaded_file($image["tmp_name"], $uploadFile)) {
                echo "Le fichier " . htmlspecialchars(basename($image["name"])) . " a été téléchargé avec succès.";
            } else {
                echo "Une erreur s'est produite lors du téléchargement du fichier.";
            }
        }
    } else {
        echo "Le fichier n'est pas une image valide.";
    }
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
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
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
        if (params.get('error') === '1') {
            alert("Veuillez remplir tous les champs obligatoires.")
        }
    }

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