<?php

require("../components/connexion.php");
require "jobs/gcloud_data_processor.php";
require '../vendor/autoload.php';

$success = false;
$newStatus = $_POST['newStatus'];

try {
    changeAutoTrainStatus($newStatus);
    $success = true;
} catch (Exception $e) {
    $success = false;
}

if ($success) {
    //change in DB
}