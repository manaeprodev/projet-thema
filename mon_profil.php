<?php

require './components/connexion.php';

if (getenv('ENV') === 'dev') {
    session_start();
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    $requete = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    var_dump($result);die();
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
    <h1>Mon profil</h1>
    <dl>
        <dt>Nom d'utilisateur</dt>
        <dd><?= $_SESSION['user']?></dd>
        <dt>Numéro chance favori</dt>
        <dd>Cascading Style Sheets</dd>
        <dt>Date de création</dt>
        <dd>Cascading Style Sheets</dd>
        <dt>Date de dernière mise à jour</dt>
        <dd>Cascading Style Sheets</dd>
    </dl>
</div>
<?php include "components/footer.php";?>

</body>
<script></script>
</html>
