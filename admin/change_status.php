<?php

require("../components/connexion.php");
require "jobs/gcloud_data_processor.php";
require '../vendor/autoload.php';

$success = false;
$newStatus = $_POST['newStatus'];
$idUser = $_POST['user'];

//try {
//    changeAutoTrainStatus($newStatus);
//    $success = true;
//} catch (Exception $e) {
//    $success = false;
//}

//Surrond with if success later...
$requete = "INSERT INTO ia_status (status,  id_user) VALUES (?, ?)";
$stmt = $connexion->prepare($requete);
$stmt->bind_param('ii', $newStatus, $idUser);
$stmt->execute();
