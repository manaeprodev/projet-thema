<?php

include "jobs/gcloud_data_processor.php";
require("../components/connexion.php");

$today = date("Y-m-d");

getData($today, 'predeections', '.txt');

$content = file_get_contents("../data/" . $today . ".txt");

$requete = "INSERT INTO ia_predictions (vl_prediction) VALUES (?)";
$stmt = $connexion->prepare($requete);
$stmt->bind_param('s', $content);
$stmt->execute();

return true;