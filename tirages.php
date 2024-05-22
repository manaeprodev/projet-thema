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
date_default_timezone_set('Europe/Paris');

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
$dateProchain = $_SESSION['dateProchain'];

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
    <p>Prédisez pour le tirage du</p>
    <h2 id="date_title"><?php

        $dateFormatee = strftime("%A %d %B %Y", strtotime($dateProchain));

        echo strtoupper($dateFormatee);

        echo "<script>var endDate = new Date('$dateProchain');</script>";
        ?></h2>
    <div class="file-input-wrapper">
        <p>Temps avant clôture :</p>
        <label id="countdown" class="btn-upload">
        </label>
    </div>
</div>
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
        if (!isset($vlPredic) && empty($vlPredic)) {
            echo "<button id='btn_valider' type='button'>Je participe !</button>";
        }
    } else {
        echo "<p>Statut : <b class='termine'>Terminé</b></p>";
        echo "<dl>";
        echo "<dd>Résultats du tirage :</dd>";
        $predicChanceBall = end($predicTab);
        array_pop($predicTab);

        $tirageChanceBall = end($tirageTab);
        array_pop($tirageTab);
        foreach ($tirageTab as $key => $ballNumber) {
            $addedClass = "";
            if (!empty($vlPredic)) {
                if (in_array($ballNumber, $predicTab)) {
                    $addedClass = "guessed_ball";
                }
            }

            echo "<dd class='predictions_balls'><label for='ia_ball_$ballNumber' class='ball ia_regular ".$addedClass."'>$ballNumber</label></dd>";
        }

        if (!empty($vlPredic)) {
            $addedClass = "";
            if ($predicChanceBall === $tirageChanceBall) {
                $addedClass = "guessed_ball";
            }
        }

        echo "<dd class='predictions_balls'><label class='ball ia_chance ".$addedClass."'>$tirageChanceBall</label></dd>";
    }
    echo "</dl>";
    echo "<dl>";

    if (isset($vlPredic) && !empty($vlPredic)) {
        echo "<br><br>";
        echo "<dd>Votre prédiction : </dd>";
        if ($isDone === 1) {
            foreach ($predicTab as $key => $ballNumber) {
                $addedClass = "";
                if (in_array($ballNumber, $tirageTab)) {
                    $addedClass = "guessed_ball";
                }
                echo "<dd class='predictions_balls'><label for='ia_ball_$ballNumber' class='ball ia_regular ".$addedClass."'>$ballNumber</label></dd>";

            }

            if (!empty($vlPredic)) {
                $addedClass = "";
                if ($predicChanceBall === $tirageChanceBall) {
                    $addedClass = "guessed_ball";
                }
            }

            echo "<dd class='predictions_balls'><label class='ball ia_chance ".$addedClass."'>$predicChanceBall</label></dd>";

        } else {
            foreach ($predicTab as $key => $ballNumber) {
                if($key === 5) {
                    echo "<dd class='predictions_balls'><label class='ball ia_chance'>$ballNumber</label>";
                } else {
                    echo "<dd class='predictions_balls'><label for='ia_ball_$ballNumber' class='ball ia_regular'>$ballNumber</label>";
                }
            }
        }

        if ($ptsGagnes > 1) {
            $string = "+".$ptsGagnes." pts";
        } elseif ($ptsGagnes >= 0 && $isDone === 1) {
            $string = "+".$ptsGagnes." pt";
        } else {
            $string = "> Les résultats arrivent...";
        }
        echo "<p class='added_pts'>$string</p>";
    } elseif ($isDone === 1) {
        echo "<p>Vous n'avez pas joué sur ce tirage.</p>";
    }

    echo "</dl>";
    echo "</div>";
}
?>

<?php include "components/footer.php";?>

</body>
<script>
    var x = setInterval(function() {
        // Date actuelle
        var now = new Date().getTime();


        var distance = endDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = days + "j " + hours + "h " + minutes + "m " + seconds + "s ";

        if (distance < 0) {
            allowPrediction = false;
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "EXPIRÉ";
            document.getElementById("btn_valider").hidden = true;
            alert("La date limite de prédiction a expiré, réessayez plus tard.");
        } else {
            document.getElementById("btn_valider").hidden = false;
        }
    }, 1000);
    document.getElementById('btn_valider').addEventListener('click', function() {
        window.location.href = 'prediction.php';
    });
</script>
</html>
