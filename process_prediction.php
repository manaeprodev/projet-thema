<?php

//Vérifier si l'utilisateur a déjà joué sur ce tirage
//Faire l'insert en base de la prédiction

echo "Coucou AJAX!";

if (getenv('ENV') !== 'dev') {
    require("./components/connexion.php");

    //get User
    $requete = "SELECT * FROM users WHERE username = ?";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $_POST['user']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $idUser = $row['id'];
        $username = $row['username'];
    }

    //get next tirage
    $requete = "SELECT * FROM tirages WHERE is_done = 0 ORDER BY date_tirage LIMIT 1";
    $nextTirage = array();
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $idTirage = $row['id'];
    }


    //get alreadyPlay info
    $requete = "SELECT * FROM user_predictions WHERE id_user = ? AND id_tirage = ?";
    $dejaJoue = array();
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('ii', $idUser, $idTirage);
    $stmt->execute();
    $result = $stmt->get_result();
    $nbLignes = $result->num_rows;

    if ($nbLignes > 0) {
        return false;
    }

    $predictionData = $_POST['predictionData'];

    function extractNumbers($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

    $numericParts = array_map('extractNumbers', $predictionData);

    $vlPrediction = implode(',', $numericParts);

    $requete = "INSERT INTO user_predictions (id_user, id_tirage, date_prediction, vl_prediction, using_predeect)
VALUES (?, ?, CURRENT_TIMESTAMP , ?, NULL);";

    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('iis', $idUser, $idTirage, $vlPrediction);
    $stmt->execute();

    return true;

}
