<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("./components/connexion.php");
    $requete = "SELECT 
    t.id, 
    t.date_tirage, 
    t.is_done, 
    t.vl_tirage, 
    t.dt_maj, 
    u.vl_prediction AS vl_predictions, 
    u.pts_gagnes
FROM 
    ((SELECT * FROM tirages WHERE is_done = 1)
    UNION
    (SELECT * FROM tirages WHERE is_done = 0 ORDER BY date_tirage ASC LIMIT 1)) AS t
LEFT JOIN 
    user_predictions AS u ON t.id = u.id_tirage AND u.id_user = 7
ORDER BY 
    t.date_tirage DESC;

";
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $tirages = $stmt->get_result();
    $stmt->close();


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

while ($row = $tirages->fetch_assoc()) {

    $idTirage = $row['id'];
    $dateTirage = new DateTime($row['date_tirage']);
    $formattedDateTirage = $dateTirage->format('d/m/Y');
    $isDone = $row['is_done'];
    $vlTirage = $row['vl_tirage'];
    $vlPredic = $row ['vl_predictions'];
    $tirageTab = array_map('intval', explode(',', $vlTirage));
    $predicTab = array_map('intval', explode(',', $vlPredic));
    $ptsGagnes = $row['pts_gagnes'];

    echo "
    <div class='container predeecta'>
        <h2>Tirage n°$idTirage - $formattedDateTirage</h2>
    ";
    if ($isDone === 0) {
        echo "<p>Statut : <b class='ouvert'>Ouvert</b></p>";
        echo "<button id='btn_valider' type='button'>Je participe !</button>";
    } else {
        echo "<p>Statut : <b class='termine'>Terminé</b></p>";
        echo "<dl>";
        echo "<dd>Résultats du tirage :</dd>";
        foreach ($tirageTab as $key => $ballNumber) {

            if ($key === 5) {
                $addedClass = "";
                $chanceBall = end($predicTab);
                if ($ballNumber === $chanceBall) {
                    $addedClass = "guessed_ball";
                }
                echo "<dd class='predictions_balls ".$addedClass."'><label class='ball ia_chance'>$ballNumber</label></dd>";
            } else {
                $addedClass = "";
                $normalBalls = array_pop($predicTab);
                if (in_array($ballNumber, $normalBalls)) {
                    $addedClass = "guessed_ball";
                }
                echo "<dd class='predictions_balls ".$addedClass."'><label for='ia_ball_$ballNumber' class='ball ia_regular'>$ballNumber</label></dd>";
            }
        }
    }
    echo "</dl>";
    echo "<dl>";

    if (isset($vlPredic) && !empty($vlPredic)) {
        echo "<dd>Votre prédiction : </dd>";
        foreach ($predicTab as $key => $ballNumber) {
            if ($key === 5) {
                $addedClass = "";
                $chanceBall = end($tirageTab);
                if ($ballNumber === $chanceBall) {
                    $addedClass = "guessed_ball";
                }
                echo "<dd class='predictions_balls ".$addedClass."'><label class='ball ia_chance'>$ballNumber</label></dd>";
            } else {
                $addedClass = "";
                $normalBalls = array_pop($tirageTab);
                if (in_array($ballNumber, $normalBalls)) {
                    $addedClass = "guessed_ball";
                }
                echo "<dd class='predictions_balls ".$addedClass."'><label for='ia_ball_$ballNumber' class='ball ia_regular'>$ballNumber</label></dd>";
            }
        }
        if ($ptsGagnes > 1) {
            $string = "+".$ptsGagnes." pts";
        } else {
            $string = "+".$ptsGagnes." pt";
        }
        echo "<dd>$string</dd>";
    } elseif ($isDone === 1) {
        echo "<dd>Vous n'avez pas joué sur ce tirage.</dd>";
    }

    echo "</dl>";
    echo "</div>";
}
?>

<?php include "components/footer.php";?>

</body>
<script>
    document.getElementById('btn_valider').addEventListener('click', function() {
        window.location.href = 'prediction.php';
    });
</script>
</html>
