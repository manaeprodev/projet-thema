<?php

require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

$storage = new StorageClient([
    'keyFile' => json_decode(file_get_contents(getenv('GOOGLE_KEY_DIR')), true)
]);