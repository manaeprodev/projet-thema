<?php

include "../jobs/get_data_from_bucket.php";

$date = $_GET['date'];

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

echo "Fin du tirage n°" . $_GET['id'] . " : " . $boulesString;