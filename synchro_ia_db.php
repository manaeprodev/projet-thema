<?php

require "admin/jobs/gcloud_data_processor.php";
require './components/connexion.php';

if ($_GET['secureKey'] == getenv('SYNCHRO_SECURE_KEY')) {
    $lastPredeection = getLastPredeection();
    $lastPredeectionData = json_decode($lastPredeection, true);
    $normalBallsData = $lastPredeectionData['prediction'][0];
    $chanceBallData = $lastPredeectionData['numero_chance'][0];

    $normalBallsData = checkData($normalBallsData);

    $normalBallsString = implode(',', $normalBallsData);
    $allBallsString = $normalBallsString . "," .$chanceBallData[0];

    $requete = "INSERT INTO ia_predictions (vl_prediction) VALUES (?)";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('s', $allBallsString);
    $stmt->execute();
} else {
    echo "Vous devez fournir une clé de sécurité pour accéder à ce webservice";
}