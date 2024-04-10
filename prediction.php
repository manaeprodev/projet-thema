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
<div class="container predeecta">
    <p>Choisissez 5 numéros</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <?php for ($i=1; $i<=49; $i++) {
        echo "<label id=\"r_$i\" for=\"ball$i\" class='ball regular'>$i</label>";
        if ($i%10 == 0) {
            echo "<br>";
        }
    }
    ?>
    <p>Choisissez votre numéro chance</p>
    <?php for ($i=1; $i<=10; $i++) {
        echo "<label id=\"c_$i\" for=\"ball$i\" class='ball chance'>$i</label>";
        if ($i%5 == 0) {
            echo "<br>";
        }
    }
    ?>
        <button id="btn_valider" type="button" hidden>Valider ma prédiction !</button>
    </form>
</div>
<div class="container predeecta">
    <p>Les conseils de Predeecta</p>
    <img>
    <p>D'après mes calculs, voici les numéros qui sont le plus susceptible de tomber!</p>
    <div class="label_container">
    <?php
    $randomArray = array();
    while (count($randomArray) < 5) {
        $randomNumber = mt_rand(1, 49);
        if (!in_array($randomNumber, $randomArray)) {
            $randomArray[] = $randomNumber;
            echo "<label for='ia_ball_$randomNumber' class='ball ia_regular'>$randomNumber</label>";
        }
    }

    $randomNumber = mt_rand(1, 10);
    echo "<label class='ball ia_chance'>$randomNumber</label>";
    ?>
    </div>
    <button id="btn_ecouter_ia" type="button">Je fais confiance en Predeecta</button>

</div>
<?php include "components/footer.php";?>
</body>
<script>
    const MAX_REGULAR_BALLS = 5;
    const MAX_CHANCE_BALLS = 1;
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

    var selectedBalls = 0;
    var regularBalls = document.querySelectorAll('.regular');
    var chanceBalls = document.querySelectorAll('.chance');
    var selectedChanceBalls;
    var selectedRegularBalls;
    regularBalls.forEach(function(regularBall) {
        regularBall.addEventListener('click', function () {
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                } else {
                    selectedRegularBalls = document.querySelectorAll('.selected.regular');
                    if (selectedRegularBalls.length < MAX_REGULAR_BALLS) {
                        this.classList.add('selected');
                    } else {
                        alert('Maximum ' + MAX_REGULAR_BALLS + ' boule(s) à sélectionner.');
                    }
                }
            });
        });

    chanceBalls.forEach(function(chanceBall) {
        chanceBall.addEventListener('click', function () {
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
            } else {
                selectedChanceBalls = document.querySelectorAll('.selected.chance');
                if (selectedChanceBalls.length < MAX_CHANCE_BALLS) {
                    this.classList.add('selected');
                } else {
                    alert('Maximum ' + MAX_CHANCE_BALLS + ' boule(s) chance à sélectionner.');
                }
            }
        });
    });

    var error = 0;

    document.getElementById('btn_valider').addEventListener('click', function() {
        if (selectedRegularBalls && selectedChanceBalls) {
            if (selectedRegularBalls.length+1 !== MAX_REGULAR_BALLS
                && selectedChanceBalls.length+1 !== MAX_CHANCE_BALLS) {
                error = 1;
                alert('Veuillez sélectionner 5 numéros ainsi qu\'1 numéro chance.');
            } else {
                $.ajax({
                    url: 'process_prediction.php',
                    type: 'POST',
                    data: {'parametre': 'valeur'},
                    success: function(response) {
                        console.log(response);
                        alert('Votre prédiction a bien été prise en compte.');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Une erreur est survenue lors de la validation de votre prédiction. Veuillez réessayer.');
                    }
                });
            }
        } else {
            error = 1;
            alert('Veuillez sélectionner 5 numéros ainsi qu\'1 numéro chance.');
        }
    });


</script>
</html>


