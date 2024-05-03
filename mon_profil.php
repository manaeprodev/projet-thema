<?php

session_start();
if (getenv('ENV') === 'dev') {
    $userData = array();
    $userData[0]['username'] = "TIRYAKT";
    $userData[0]['luckyNumber'] = "2";
    $userData[0]['createdDate'] = "2024-02-14";
    $userData[0]['lastUpdatedDate'] = "2024-04-10";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require './components/connexion.php';
    $requete = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $_SESSION['user'][0]['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $userData[] = $row;
    }

    $requete = "SELECT up.id_user, up.id_tirage, up.vl_prediction, t.vl_tirage
FROM user_predictions up
INNER JOIN tirages t ON up.id_tirage = t.id
WHERE t.is_done = 1 AND up.id_user = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $userData[0]['id']);
    $stmt->execute();
    $asso_up_tir = $stmt->get_result();

    $nbOfPredic = $asso_up_tir->num_rows;
    $nbOfCorrect = 0;

    while ($row = $asso_up_tir->fetch_assoc()) {
        $array1 = array_map('intval', explode(',', $row['vl_prediction']));
        $array2 = array_map('intval', explode(',', $row['vl_tirage']));

        $commonElements = array_intersect($array1, $array2);

        $commonCount = count($commonElements);

        $nbOfCorrect += $commonCount;
    }
    $stmt->close();

    $precision = ($nbOfCorrect / ($nbOfPredic * 6)) * 100;

    $requete = "UPDATE users SET prec = ? WHERE id = ?";

    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('di', $precision, $userData[0]['id']);
    $stmt->execute();
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
        <dd><?= $userData[0]['username']?></dd>
        <dt>Numéro chance favori</dt>
        <dd><?= $userData[0]['luckyNumber']?></dd>
        <dt>Date de création</dt>
        <dd><?= $userData[0]['createdDate']?></dd>
        <dt>Date de dernière connexion</dt>
        <dd><?= $userData[0]['lastUpdatedDate']?></dd>
    </dl>
    <a id="deco" href="index.php?disconnected=1">Se déconnecter</a>
</div>
<div class="container predeecta">
    <h2>Mes dernières prédictions</h2>
    <dl>
        <?php
        $idUser = $userData[0]['id'];
        $requete = "SELECT * FROM user_predictions WHERE id_user = ? ORDER BY date_prediction DESC LIMIT 10";
        $myPredictions = array();
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('i', $idUser);
        $stmt->execute();
        $result = $stmt->get_result();
        $nbPredic = $result->num_rows;

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

        while ($row = $result->fetch_assoc()) {
            $date = new DateTime($row['date_prediction']);
            $formattedDate = $date->format('l d F Y \à H:i');
            foreach ($translations as $eng => $fr) {
                $formattedDate = str_replace($eng, $fr, $formattedDate);
            }
            echo "<dt>Tirage n°".$row['id_tirage']."</dt><br>";
            echo "<dt>".$formattedDate."</dt><br>";
            $predicTab = explode(',', $row['vl_prediction']);
            foreach ($predicTab as $key => $ballNumber) {
                if ($key === 5) {
                    echo "<dd class='predictions_balls'><label class='ball ia_chance'>$ballNumber</label></dd><br><br>";
                } else {
                    echo "<dd class='predictions_balls'><label for='ia_ball_$ballNumber' class='ball ia_regular'>$ballNumber</label></dd>";
                }

            }
        }
        ?>
    </dl>
</div>
<div class="container predeecta">
    <h2>Mes stats</h2>
    <dl>
        <dt>Nombre de prédictions totales</dt>
        <dd><?= $nbPredic;?></dd>
        <dt>Précision des prédictions</dt>
        <dd><?= $precision;?>%</dd>
    </dl>
</div>
<?php include "components/footer.php";?>

</body>
<script>
</script>
</html>
