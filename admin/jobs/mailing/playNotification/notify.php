<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../../vendor/autoload.php'; // Assurez-vous d'avoir PHPMailer installé via Composer
require '../../../../components/connexion.php';

$mail = new PHPMailer(true);

try {
    $requete = "SELECT DISTINCT email FROM users WHERE wants_emails = 1";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $result = $stmt->get_result();
    // Configurations SMTP pour Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'contactpredeect@gmail.com'; // Remplacez par votre adresse Gmail
    $mail->Password = 'LApinutella2802-_'; // Remplacez par votre mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Utilisez TLS si nécessaire
    $mail->Port = 993; // Ou 587 pour TLS

    // Paramètres de l'email
    $mail->setFrom('no-reply-predeect@gmail.com', 'PREDEECTA via totodede.nvsphr.fr');
    while ($row = $result->fetch_assoc()) {
        $mail->addBCC($row['email']);
    }
    $mail->Subject = 'Un tirage est prévu ce soir! Venez prédire avant 17:00.';

    // Charger le template HTML
    $templatePath = 'template.html'; // Chemin vers votre fichier HTML
    $htmlBody = file_get_contents($templatePath); // Charger le contenu du fichier

    $mail->isHTML(true); // Indique que le corps est en HTML
    $mail->Body = $htmlBody; // Utiliser le template HTML

    // Envoyer l'email
    $mail->send();
    $stmt->close();
    echo 'Email envoyé avec succès';
} catch (Exception $e) {
    echo 'L\'email n\'a pas pu être envoyé. Erreur : ', $mail->ErrorInfo;
}
