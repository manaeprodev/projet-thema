<?php

require_once 'auth.php';

function getData($date)
{
    $bucket = $storage->bucket('predeect_bucket');

    $object = $bucket->object($date.'.json');
    $object->downloadToFile('../data/'.$date.'.json');
}

