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
