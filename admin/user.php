<?php
require_once("connexion.php"); // Inclure le fichier de connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Vous pouvez maintenant utiliser la connexion à la base de données ($connexion) pour vérifier les informations de connexion
    // ... (vérification de l'utilisateur, requêtes SQL, etc.)

    // Exemple : vérification basique (ne pas utiliser en production)
    $requete = "SELECT * FROM admin WHERE login='$username' AND password='$password'";
    $resultat = $connexion->query($requete);

    if ($resultat->num_rows == 1) {
        // L'utilisateur est authentifié avec succès
        echo "Connexion réussie. Bienvenue, $username!";
    } else {
        // L'utilisateur n'est pas authentifié
        echo "Échec de la connexion. Vérifiez votre nom d'utilisateur et votre mot de passe.";
    }
}
?>

