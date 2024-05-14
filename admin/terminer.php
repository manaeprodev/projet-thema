<?php

include "jobs/gcloud_data_processor.php";

$idTirage = $_GET['id'];
$date = $_GET['date'];

function addPts($idTirage, $boulesString)
{
    require "../components/connexion.php";

    $requete = "SELECT * FROM user_predictions WHERE id_tirage = ?";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('i', $idTirage);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $idUser = $row['id_user'];
        $vlPrediction = $row['vl_prediction'];

        $array1 = explode(',', $vlPrediction);
        $array2 = explode(',', $boulesString);

        $chance1 = array_pop($array1);
        $chance2 = array_pop($array2);

        $commonElements = array_intersect($array1, $array2);

        $commonCount = count($commonElements);

        switch ($commonCount) {
            case 0:
                $nbPts = 1;
                break;
            case 1:
                $nbPts = 2;
                break;
            case 2:
                $nbPts = 4;
                break;
            case 3:
                $nbPts = 8;
                break;
            case 4:
                $nbPts = 16;
                break;
            case 5:
                $nbPts = 32;
                break;
            default:
                $nbPts = 0;
                break;
        }

        if ($chance1 === $chance2) {
            $nbPts += 6;
        }
        $stmt->close();

        $requete = "UPDATE users SET pts = pts + ? WHERE id = ?";

        $stmt = $connexion->prepare($requete);
        $stmt->bind_param('ii', $nbPts, $idUser);
        $stmt->execute();
        $stmt->close();

        $requeteAjPts = "UPDATE user_predictions SET pts_gagnes = ? WHERE id_user = ? AND id_tirage = ?";

        $stmt = $connexion->prepare($requeteAjPts);
        $stmt->bind_param('iii', $nbPts, $idUser, $idTirage);
        $stmt->execute();
    }
}

function sendMails($idTirage)
{
    require "../components/connexion.php";
    $requete = "SELECT u.username, u.email FROM user_predictions up 
INNER JOIN users u ON up.id_user = u.id
WHERE up.id_tirage = ? AND u.wants_emails = 1";
    $stmt = $connexion->prepare($requete);
    $stmt->bind_param('i', $idTirage);
    $stmt->execute();
    $resultPlayers = $stmt->get_result();

    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setUsername(getenv('GOOGLE_EMAIL'))
        ->setPassword(getenv('GOOGLE_PASSWORD'));

    while ($row = $resultPlayers->fetch_assoc()) {
        $email = $row['email'];
        $username = $row['username'];
        $htmlBody = file_get_contents('resultTemplate.html');
        $htmlBody = str_replace(array('{{idTirage}}', '{{name}}'), array($idTirage, $username), $htmlBody);
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Les résultats du tirage n°'.$idTirage.' sont disponibles!'))
            ->setFrom(['contactpredeect@gmail.com' => 'Tony Tiryaki de PREDEECT'])
            ->setBcc([$email])
            ->setBody($htmlBody, 'text/html');

        $result = $mailer->send($message);
    }
}

try {
    getData($date, 'predeect_bucket', '.json');

    $tirageJson = file_get_contents("../data/" . $date . ".json");

    $tirageData = json_decode($tirageJson, true);

    $boules = [
        $tirageData['boule_1'],
        $tirageData['boule_2'],
        $tirageData['boule_3'],
        $tirageData['boule_4'],
        $tirageData['boule_5'],
        $tirageData['numero_chance']
    ];

    $boulesString = implode(',', $boules);

    pushDataToDb($idTirage, $boulesString);

    addPts($idTirage, $boulesString);

    sendMails($idTirage);

    echo "Fin du tirage n°" . $_GET['id'] . " : " . $boulesString;

    header("Location: corriger_tirage.php?success=1");
} catch (Exception $e) {
    header("Location: corriger_tirage.php?success=0");
}

