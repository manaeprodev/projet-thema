<?php
session_start();
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
<div class="container">
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
    <p>Choisissez 5 numéros</p>
    <p>Choisissez votre numéro chance</p>
</div>
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
            document.getElementById("btn_prediction").hidden = true;
            alert("La date limite de prédiction a expiré, réessayez plus tard.");
        } else {
            document.getElementById("btn_prediction").hidden = false;
        }
    }, 1000);


</script>
</html>


