<?php
$titlePage = "Connexion";


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
    <form>
        <input type="text" placeholder="Nom d'utilisateur" required>
        <input type="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
    </form>
    <p>Pas de compte? <a href="register.php">Inscrivez-vous dés maintenant!</a></p>
</div>

<?php include "components/footer.php";?>

</body>
</html>
