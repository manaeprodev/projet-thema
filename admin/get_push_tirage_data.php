<?php

require "jobs/gcloud_data_processor.php";

$url = "https://data.opendatasoft.com/api/explore/v2.1/catalog/datasets/resultats-loto-2019-a-aujourd-hui@agrall/records?order_by=date_de_tirage%20DESC&limit=1";
$response = file_get_contents($url);

if ($response === false) {
    echo "Erreur : impossible de récupérer le contenu de l'URL.";
    exit;
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Erreur de décodage JSON : " . json_last_error_msg();
    exit;
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Erreur de décodage JSON : " . json_last_error_msg();
    exit;
}

print_r($data['results']);

$jsonData = json_encode($data, JSON_PRETTY_PRINT);

if ($jsonData === false) {
    echo "Erreur d'encodage JSON : " . json_last_error_msg();
    exit;
}

// Chemin du fichier où écrire les données JSON
$file = 'results/resultats_loto.json';

// Écrire les données JSON dans le fichier
if (file_put_contents($file, $jsonData) === false) {
    echo "Erreur : impossible d'écrire dans le fichier $file.";
    exit;
}

echo "Les données ont été écrites dans le fichier $file avec succès.";

pushToBucket($file, "predeect_bucket");