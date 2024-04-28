<?php

require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient([
    'keyFile' => json_decode(file_get_contents('./predeect-410808-3a524e99970e.json'), true)
]);