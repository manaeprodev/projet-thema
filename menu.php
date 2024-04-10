<?php

if (getenv('ENV') === 'dev') {
    session_start();
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
    <h2>Bonjour, <a id="username_title"><?php if (isset($_SESSION['user'])) { echo "@".$_SESSION['user']; } else { echo "Inconnu";}?></a></h2>
    <?php
        if (!isset($_SESSION['user'])) {
            echo "<a href='index.php'>Pensez à vous connecter en cliquant ici.</a>";
        }
    ?>
    <h3>Prochain tirage :</h3>
    <h2 id="date_title"><?php
        date_default_timezone_set('Europe/Paris');

        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

        $dateActuelle = new DateTime();

        while (!in_array($dateActuelle->format('N'), array(1, 3, 6))) { // 1 pour lundi, 3 pour mercredi, 6 pour samedi
            $dateActuelle->modify('+1 day');
        }

        $dateProchain = $dateActuelle->format('Y-m-d 17:00:00');
        $_SESSION['dateProchain'] = $dateProchain;

        $dateFormatee = strftime("%A %d %B %Y", strtotime($dateProchain));

        echo strtoupper($dateFormatee);

        echo "<script>var endDate = new Date('$dateProchain');</script>";
        ?></h2>
    <input id="btn_prediction" type="submit" value="Faire une prédiction !" hidden>

            <div class="file-input-wrapper">
                <p>Temps avant clôture :</p>
                <label id="countdown" class="btn-upload">
                </label>
            </div>

            <div class="file-input-info" id="fileInfo"></div>
</div>

<?php include "components/footer.php";?>

</body>
<script>
    var allowPrediction = true;
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

    document.getElementById("btn_prediction").addEventListener("click", function() {
        if(allowPrediction) {
            window.location.href = "prediction.php";
        } else {
            alert("La date limite de prédiction a expiré, réessayez plus tard.");
        }

    });
</script>
</html>

