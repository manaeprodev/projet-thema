<?php

session_start();
if (getenv('ENV') === 'dev') {
    $userData = array();
    $userData['username'] = "TIRYAKT";
    $userData['luckynumber'] = "2";
    $userData['createdDate'] = "2024-02-14";
    $userData['lastUpdatedDate'] = "2024-04-10";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require './components/connexion.php';
    $requete = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $userData[] = $row;
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

<div class="container predeecta">
    <h2>Mon profil</h2>
    <dl>
        <dt>Nom d'utilisateur</dt>
        <dd><?= $userData['username']?></dd>
        <dt>Numéro chance favori</dt>
        <dd><?= $userData['luckynumber']?></dd>
        <dt>Date de création</dt>
        <dd><?= $userData['createdDate']?></dd>
        <dt>Date de dernière mise à jour</dt>
        <dd><?= $userData['lastUpdatedDate']?></dd>
    </dl>
    <a id="deco" href="index.php">Se déconnecter</a>
</div>
<div class="container predeecta">
    <h2>Mes dernières prédictions</h2>
    <dl>
        <dt>2024-04-10</dt>
        <dd>5,6,7,8,9,10</dd>
        <dt>2024-04-08</dt>
        <dd>5,6,7,8,9,10</dd>
        <dt>2024-04-06</dt>
        <dd>5,6,7,8,9,10</dd>
        <dt>2024-04-03</dt>
        <dd>5,6,7,8,9,10</dd>
        <dt>2024-04-01</dt>
        <dd>5,6,7,8,9,10</dd>
    </dl>
</div>
<div class="container predeecta">
    <h2>Mes stats</h2>
    <dl>
        <dt>Nombre de prédictions totales</dt>
        <dd>5</dd>
        <dt>Précision des prédictions</dt>
        <dd>12%</dd>
        <dt>Rang</dt>
        <dd>1/12</dd>
    </dl>
</div>
<?php include "components/footer.php";?>

</body>
<script>
</script>
</html>
