<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
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
    <p>Ici, afficher pour chaque tirage, les résultats depuis la BDD</p>
    <p>Ainsi que la date du prochain tirage</p>
    <p>Si connecté, bouton pour aller pronostiquer sur menu.php</p>
</div>

<?php include "components/footer.php";?>

</body>
</html>
