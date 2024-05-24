<?php

if ($_GET['disconnected']==1 || $_GET['inscription_reussie']==4 || $_GET['inscription_reussie']==5) {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
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
        $stmt->close();
        $nbLignes = $result->num_rows;

        if ($nbLignes > 0) {
            session_start();
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $_SESSION['user'] = $data;
            $requete = "UPDATE users SET lastUpdatedDate = CURRENT_TIMESTAMP  WHERE username = ?";
            $stmt = $connexion->prepare($requete);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            header("Location: mon_profil.php?redirect=menu");
        } else {
            header("Location: index.php?inscription_reussie=7");
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
    <h2>Se connecter</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
    </form>
    <p>Pas de compte?<br><a href="register.php">Inscrivez-vous dés maintenant!</a></p>
    <p><a href="reset_password.php">Mot de passe oublié?</a></p>
</div>

<?php include "components/footer.php";?>

</body>
<script>
    const params = new URLSearchParams(window.location.search);
    document.addEventListener("DOMContentLoaded", function() {
        if (params.has('inscription_reussie')) {
            switch (params.get('inscription_reussie')) {
                case '1':
                    alert("Votre compte a bien été créé! Vous pouvez vous connecter.");
                    break;
                case '2':
                    alert("Vous devez vous connecter pour continuer.");
                    break;
                case '3':
                    alert('Erreur inconnue survenue.');
                    break;
                case '4':
                    alert('Votre compte a bien été anonymisé. Merci d\'avoir utilisé Predeect.');
                    break;
                case '5':
                    alert('Votre compte a bien été supprimé définitivement. Merci d\'avoir utilisé Predeect.');
                    break;
                case '6':
                    alert('Si votre nom d\'utilisateur ou votre e-mail est reconnu, alors la procédure de changement de mot de passe vous sera envoyée sous peu.');
                    break;
                case '7':
                    alert('Nom d\'utilisateur ou mot de passe incorrect.');
                    break;
                default:
                    break;
            }
        }
    });
</script>
</html>
