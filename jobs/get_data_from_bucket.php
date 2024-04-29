<?php

function getData($date)
{
    require_once 'auth.php';
    $bucket = $storage->bucket('predeect_bucket');

    $object = $bucket->object($date.'.json');
    $object->downloadToFile('../data/'.$date.'.json');
}

