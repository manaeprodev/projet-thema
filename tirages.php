<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("./components/connexion.php");
    //get User
    $requete = "(SELECT * FROM tirages WHERE is_done = 1)
        UNION
        (SELECT * FROM tirages WHERE is_done = 0 ORDER BY date_tirage ASC LIMIT 1)";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $idUser = $row['id'];
        $username = $row['username'];
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
<?php
while ($row = $result->fetch_assoc()) {
    $idTirage = $row['id'];
    $dateTirage = $row['date_tirage'];
    $isDone = $row['is_done'];
    $vlTirage = $row['vl_tirage'];

    $predicTab = explode(',', $row['vl_prediction']);
    echo "
    <div class='container predeecta>
        <h2>Tirage n°$idTirage - $dateTirage</h2>
    ";
    if ($isDone === 0) {
        echo "<p>Statut : <b class='ouvert'>Ouvert</b></p>";
    } else {
        echo "<p>Statut : <b class='termine'>Terminé</b></p>";
    }
    foreach ($predicTab as $key => $ballNumber) {
        if ($key === 5) {
            echo "<dd class='predictions_balls'><label class='ball ia_chance'>$ballNumber</label></dd><br><br>";
        } else {
            echo "<dd class='predictions_balls'><label for='ia_ball_$ballNumber' class='ball ia_regular'>$ballNumber</label></dd>";
        }

    }
    echo "</div>";
}
?>

<?php include "components/footer.php";?>

</body>
</html>
