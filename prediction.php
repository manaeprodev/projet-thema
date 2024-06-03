<?php

require "admin/jobs/gcloud_data_processor.php";

session_start();
if (getenv('ENV') === 'dev') {

    $_SESSION['user'][0]['username'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
}

date_default_timezone_set('Europe/Paris');

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
$dateProchain = $_SESSION['dateProchain'];

$lastPredeection = getLastPredeection();
$lastPredeectionTab = explode(",", $lastPredeection);
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


        $translations = array(
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
            'January' => 'Janvier',
            'February' => 'Février',
            'March' => 'Mars',
            'April' => 'Avril',
            'May' => 'Mai',
            'June' => 'Juin',
            'July' => 'Juillet',
            'August' => 'Août',
            'September' => 'Septembre',
            'October' => 'Octobre',
            'November' => 'Novembre',
            'December' => 'Décembre'
        );

        foreach ($translations as $eng => $fr) {
            $dateFormatee = str_replace($eng, $fr, $dateFormatee);
        }

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
        <button id="btn_clear" type="button">Effacer</button>
    </form>
</div>
<div class="container predeecta">
    <p>Les conseils de Predeecta</p>
    <p>"D'après mes calculs, voici les numéros qui sont le plus susceptible de tomber!"</p>
    <div class="label_container">
        <label for="ia_ball_<?=$lastPredeectionTab[0]?>" class="ball ia_regular ia"><?=$lastPredeectionTab[0]?></label>
        <label for="ia_ball_<?=$lastPredeectionTab[1]?>" class="ball ia_regular ia"><?=$lastPredeectionTab[1]?></label>
        <label for="ia_ball_<?=$lastPredeectionTab[2]?>" class="ball ia_regular ia"><?=$lastPredeectionTab[2]?></label>
        <label for="ia_ball_<?=$lastPredeectionTab[3]?>" class="ball ia_regular ia"><?=$lastPredeectionTab[3]?></label>
        <label for="ia_ball_<?=$lastPredeectionTab[4]?>" class="ball ia_regular ia"><?=$lastPredeectionTab[4]?></label>
        <label class="ball ia_chance ia"><?=$lastPredeectionTab[5]?></label>
    </div>
    <button id="btn_ecouter_ia_pred" type="button">Je fais confiance en Predeecta !</button>
    <p>"Vous pouvez aussi choisir ces numéros générés aléatoirement!"</p>
    <div class="label_container">
        <?php
        $randomArray = array();
        while (count($randomArray) < 5) {
            $randomNumber = mt_rand(1, 49);
            if (!in_array($randomNumber, $randomArray)) {
                $randomArray[] = $randomNumber;
                echo "<label for='ia_ball_$randomNumber' class='ball ia_regular ia_rand'>$randomNumber</label>";
            }
        }

        $randomNumber = mt_rand(1, 10);
        echo "<label class='ball ia_chance ia_rand'>$randomNumber</label>";
        ?>
    </div>
    <button id="btn_ecouter_ia" type="button">J'utilise l'aléatoire.</button>
</div>
<div class="container predeecta">
    <input id="user" type="submit" value="<?= $_SESSION['user'][0]['username']?>" hidden>
    <p>"Je vous ai préparé quelques indicateurs pour vous aider!"</p>
    <dl>
        <dt>Les 10 numéros les plus gagnants dans l'histoire du Loto : </dt>
        <dd class='predictions_balls'><label class='ball ia_regular'>44</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>11</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>14</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>4</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>47</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>34</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>3</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>35</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>30</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>23</label></dd>
        <dt>Les 3 numéros chances les plus gagnants dans l'histoire du Loto : </dt>
        <dd class='predictions_balls'><label class='ball ia_chance'>3</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>6</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>9</label></dd>
        <dt>Les 10 numéros les plus gagnants des 10 derniers tirages : </dt>
        <dd class='predictions_balls'><label class='ball ia_regular'>23</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>3</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>45</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>24</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>9</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>44</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>12</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>39</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>4</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>49</label></dd>
        <dt>Les 3 numéros chances les plus gagnants des 10 derniers tirages : </dt>
        <dd class='predictions_balls'><label class='ball ia_chance'>9</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>1</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>5</label></dd>
        <dt>Les numéros les moins sortis dans l'histoire du Loto : </dt>
        <dd class='predictions_balls'><label class='ball ia_regular'>24</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>46</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>22</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>2</label></dd>
        <dd class='predictions_balls'><label class='ball ia_regular'>36</label></dd>
        <dt>Les numéros chances les moins sortis dans l'histoire du Loto : </dt>
        <dd class='predictions_balls'><label class='ball ia_chance'>5</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>8</label></dd>
        <dd class='predictions_balls'><label class='ball ia_chance'>2</label></dd>
    </dl>
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
            selectedRegularBalls = document.querySelectorAll('.selected.regular');
            selectedChanceBalls = document.querySelectorAll('.selected.chance');
            if (selectedRegularBalls.length !== MAX_REGULAR_BALLS
                && selectedChanceBalls.length !== MAX_CHANCE_BALLS) {
                error = 1;
                alert('Veuillez sélectionner 5 numéros ainsi qu\'1 numéro chance.');
            } else {
                var ids = [];
                var username;
                selectedBalls = document.querySelectorAll('.selected');
                selectedBalls.forEach(function(ball) {
                    var id = ball.getAttribute('id');

                    // Ajouter l'ID au tableau
                    ids.push(id);
                });
                username = document.getElementById('user').value;
                $.ajax({
                    url: 'process_prediction.php',
                    type: 'POST',
                    data: {'predictionData': ids, 'user': username},
                    success: function(response) {
                        console.log(response);
                        if (response) {
                            alert('Votre prédiction a bien été prise en compte.')
                            window.location.href = 'mon_profil.php';
                        } else {
                            alert('Erreur : La prédiction a échoué. Veuillez réessayer.');
                            window.location.href = 'menu.php';
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Une erreur est survenue lors de la validation de votre prédiction. Veuillez réessayer.');
                    }
                });
            }
        } else {
            error = 1;
            alert('Erreur : Veuillez sélectionner 5 numéros ainsi qu\'1 numéro chance.');
        }
    });

    function clear() {
        var elements = document.querySelectorAll('.selected');

        elements.forEach(function(element) {
            element.classList.remove('selected');
        });
    }

    document.getElementById('btn_clear').addEventListener('click', function () {
        clear();
    });

    document.getElementById('btn_ecouter_ia').addEventListener('click', function () {
        var elementsWithIAClass = document.getElementsByClassName('ia_rand');
        clear();

        for (var i = 0; i < elementsWithIAClass.length; i++) {
            if (i === elementsWithIAClass.length - 1) {
                document.getElementById('c_' + elementsWithIAClass[i].textContent).classList.add('selected');
            } else {
                document.getElementById('r_' + elementsWithIAClass[i].textContent).classList.add('selected');
            }

        }
        selectedRegularBalls = document.querySelectorAll('.selected.regular');
        selectedChanceBalls = document.querySelectorAll('.selected.chance');
        alert('Les boules aléatoires ont été sélectionnées, vous pouvez valider votre prédiction.');
    });

    document.getElementById('btn_ecouter_ia_pred').addEventListener('click', function () {
        var elementsWithIAClass = document.getElementsByClassName('ia');
        clear();

        for (var i = 0; i < elementsWithIAClass.length; i++) {
            if (i === elementsWithIAClass.length - 1) {
                document.getElementById('c_' + elementsWithIAClass[i].textContent).classList.add('selected');
            } else {
                document.getElementById('r_' + elementsWithIAClass[i].textContent).classList.add('selected');
            }

        }
        selectedRegularBalls = document.querySelectorAll('.selected.regular');
        selectedChanceBalls = document.querySelectorAll('.selected.chance');
        alert('Les boules prédites par Predeecta ont été sélectionnées, vous pouvez valider votre prédiction.');
    });


</script>
</html>


