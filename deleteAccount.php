<?php

require './components/connexion.php';

$idUser = $_GET['idUser'];

switch ($_GET['mode']) {
    case 1:
        $requete = "UPDATE users SET username = CONCAT('Player', ?), email = NULL, wants_emails = 0, is_admin = 0, password = '%%%%%%%%%%%%%%%%%%%%' FROM users WHERE id = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('si', $idUser, $idUser);
        $stmt->execute();
        header('Location: index.php?inscription_reussie=4');
        break;
    case 2:
        $requete = "DELETE FROM user_predictions WHERE id = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('i', $idUser);
        $stmt->execute();

        $requete = "DELETE FROM users WHERE id = ?";
        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('i', $idUser);
        $stmt->execute();
        header('Location: index.php?inscription_reussie=4');
        break;
    default:
        header('Location: index.php?inscription_reussie=3');
        break;
}
