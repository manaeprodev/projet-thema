<?php

use Google\Cloud\Storage\StorageClient;

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

    if ($mostRecentObject) {
        return $mostRecentObject->downloadAsString();
    }

    return null;
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

function getLastPredeection() {
    require 'vendor/autoload.php';

    $storage = new StorageClient([
        'keyFile' => json_decode(file_get_contents('admin/'.getenv('GOOGLE_KEY_DIR')), true)
    ]);

    $bucket = $storage->bucket('predeections');
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

    if ($mostRecentObject) {
        return $mostRecentObject->downloadAsString();
    }

    return null;
}

function checkData($normalBalls) {
    $valeursUniques = [];

    foreach ($normalBalls as $valeur) {
        $ogValeur = $valeur;
        if (!in_array($valeur, $valeursUniques)) {
            $valeursUniques[] = $valeur;
        } else {
            do {
                if ($ogValeur >= 25) {
                    $valeur--;
                } else {
                    $valeur++;
                }
            } while (in_array($valeur, $valeursUniques));

            $valeursUniques[] = $valeur;
        }
    }

    return $valeursUniques;
}
