<?php

include "jobs/gcloud_data_processor.php";

$idTirage = $_GET['id'];
$date = $_GET['date'];

try {
    getData($date);

    $tirageJson = file_get_contents("../data/" . $date . ".json");

    $tirageData = json_decode($tirageJson, true);

    $boules = [
        $tirageData['boule_1'],
        $tirageData['boule_2'],
        $tirageData['boule_3'],
        $tirageData['boule_4'],
        $tirageData['boule_5'],
        $tirageData['numero_chance']
    ];

    $boulesString = implode(',', $boules);

    pushDataToDb($idTirage, $boulesString);

    echo "Fin du tirage n°" . $_GET['id'] . " : " . $boulesString;

    header("Location: corriger_tirage.php?success=1");
} catch (Exception $e) {
    header("Location: corriger_tirage.php?success=0");
}

