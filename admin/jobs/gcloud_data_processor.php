<?php

function getData($date, $bucket, $ext)
{
    require_once 'auth.php';
    $bucket = $storage->bucket($bucket);

    $object = $bucket->object($date.$ext);
    $object->downloadToFile('../data/'.$date.$ext);
}

function pushDataToDb($idTirage, $boulesString)
{
    require("../components/connexion.php");

    $requete = "UPDATE tirages SET is_done = 1, vl_tirage = ? WHERE id = ?";

    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('si', $boulesString, $idTirage);
    $stmt->execute();
}

function pushToBucket($fileToPush, $targetBucket)
{
    require_once 'auth.php';
    $objectName = date('Y-m-d', strtotime('-1 day')) . ".json";
    $source = "results/resultats_loto.json";
    $bucket = $storage->bucket($targetBucket);

    $object = $bucket->upload(
        fopen($source, 'r'),
        [
            'name' => $objectName
        ]
    );

    echo "Le fichier $fileToPush a été uploadé dans le bucket $targetBucket avec le nom $objectName.";

}

function getLastAiParams() {
    require_once 'auth.php';

    $bucket = $storage->bucket('ai_params');
    $objects = $bucket->objects();

    $mostRecentObject = null;
    $mostRecentTimestamp = null;

    foreach ($objects as $object) {
        $info = $object->info();
        $updated = new DateTime($info['updated']);

        if (is_null($mostRecentTimestamp) || $updated > $mostRecentTimestamp) {
            $mostRecentObject = $object;
            $mostRecentTimestamp = $updated;
        }
    }

    return $mostRecentObject;
}

function pushAiParams($file) {
    require 'auth.php';

    $bucket = $storage->bucket('ai_params');

    $object = $bucket->upload(
        fopen($file, 'r'),
        [
            'name' => $file
        ]
    );

    echo "Le fichier $file a été uploadé dans le bucket ai_params.";
}