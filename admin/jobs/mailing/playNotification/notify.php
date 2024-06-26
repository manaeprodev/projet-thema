<?php

require '../../../../vendor/autoload.php';
require '../../../../components/connexion.php';



$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
    ->setUsername('contactpredeect@gmail.com')
    ->setPassword('twpesejjnruponjq');

$requete = "SELECT DISTINCT username, email FROM users WHERE wants_emails = 1";
$stmt = $connexion->prepare($requete);
$stmt->execute();
$resultEmailsToSend = $stmt->get_result();

while ($row = $resultEmailsToSend->fetch_assoc()) {
    $email = $row['email'];
    $username = $row['username'];
    $htmlBody = file_get_contents('template.html');
    $htmlBody = str_replace('{{name}}', $username, $htmlBody);
    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message('Un tirage est prévu ce soir! Venez prédire dés maintenant!'))
        ->setFrom(['contactpredeect@gmail.com' => 'Tony Tiryaki de PREDEECT'])
        ->setBcc([$email])
        ->setBody($htmlBody, 'text/html');

    $result = $mailer->send($message);
}



echo 'Email envoyé avec succès';
