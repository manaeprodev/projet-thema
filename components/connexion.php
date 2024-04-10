<?php
// Informations de connexion à la base de données
$serveur = getenv('SERVER');
$nomUtilisateur = getenv('USER_BDD');
$motDePasse = getenv('PASSWORD_BDD');
$baseDeDonnees = getenv('BDD_NAME');

// Établir la connexion à la base de données
$connexion = new mysqli($serveur, $nomUtilisateur, $motDePasse, $baseDeDonnees,3306);

// Vérification des erreurs de connexion
if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}
