<?php

if (isset($_SESSION)) {
    session_destroy();
}

require_once("./components/connexion.php");

$titlePage = "Connexion";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            header("Location: index.php?error=1");
        }

        //Regex pour empecher injection SQL

        $requete = "SELECT id, username, luckyNumber, pfp FROM users WHERE username = ? AND password = ? LIMIT 1";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();
        $nbLignes = $result->num_rows;
        
        if ($nbLignes > 0) {
            session_start();
            $_SESSION['user'] = $result;
            header("Location: menu.php");
        } else {
            header("Location: index.php?error=2");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php include "components/head.php";?>
<body>
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="assets/style.css">
</head>


<div class="container">
    <h2>Formulaire de Connexion</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
    </form>
    <p>Pas de compte? <a href="register.php">Inscrivez-vous dés maintenant!</a></p>
</div>

<?php include "components/footer.php";?>

</body>
</html>
