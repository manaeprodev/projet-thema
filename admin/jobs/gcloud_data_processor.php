<?php

function getData($date)
{
    require_once 'auth.php';
    $bucket = $storage->bucket('predeect_bucket');

    $object = $bucket->object($date.'.json');
    $object->downloadToFile('../data/'.$date.'.json');
}

function pushDataToDb($idTirage, $boulesString)
{
    require("../components/connexion.php");

    $requete = "UPDATE tirages SET is_done = 1, vl_tirage = ? WHERE id = ?";

    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('si', $boulesString, $idTirage);
    $stmt->execute();
}
