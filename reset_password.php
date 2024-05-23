<?php

session_start();

function generateRandomPassword()
{
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $numbers = '0123456789';
    $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    $all = $upper . $lower . $numbers . $special;

    $characters = str_shuffle($all);

    $password = '';

    for ($i = 0; $i < 12; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return md5($password);
}

function sendResetPwdMail($email, $tempPwd, $username)
{
    require './vendor/autoload.php';

    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setUsername(getenv('GOOGLE_EMAIL'))
        ->setPassword(getenv('GOOGLE_PASSWORD'));

    $htmlBody = file_get_contents('admin/jobs/mailing/resetPassword/template.html');
    $htmlBody = str_replace(array('{{tempPwd}}', '{{name}}', '{{name2}}'), array($tempPwd, $username, $username), $htmlBody);

    $mailer = new Swift_Mailer($transport);

    $message = (new Swift_Message('Demande de réinitialisation de mot de passe'))
        ->setFrom(['contactpredeect@gmail.com' => 'Tony Tiryaki de PREDEECT'])
        ->setBcc([$email])
        ->setBody($htmlBody, 'text/html');

    $result = $mailer->send($message);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require './components/connexion.php';

    if (!empty($_POST['text'])) {
        $searchUser = "SELECT id, username, email FROM users WHERE email = ? OR username = ?";
        $stmt = $connexion->prepare($searchUser);
        $stmt->bind_param('ss', $_POST['text'], $_POST['text']);
        $stmt->execute();
        $foundUser = $stmt->get_result();

        while ($row = $foundUser->fetch_assoc()) {
            $idUser = $row['id'];
            $username = $row['username'];
            $email = $row['email'];
            $randomPwd = generateRandomPassword();

            $tempPwd = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $connexion->prepare($tempPwd);
            $stmt->bind_param('si', $randomPwd, $idUser);
            $stmt->execute();

            if (!empty($email)) {
                sendResetPwdMail($email, $randomPwd, $username);
            }
            header('Location: index.php?inscription_reussie=6');

        }



    } else {
        header('Location: reset_password.php?error=1');
    }


}

?>
<!DOCTYPE html>
<html lang="fr">
<?php include "components/head.php";?>
<body>
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="assets/style.css">
</head>
<div class="container predeecta">
    <h2>Réinitialiser mon mot de passe</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <input type="text" name="text" placeholder="Nom d'utilisateur ou e-mail" required>
        <input type="submit" value="Valider">
    </form>
    <p>Entrez votre nom d'utilisateur ou bien votre e-mail. Si votre compte est retrouvé, vous recevrez par mail la procédure de réinitialisation de votre mot de passe.</p>
    <p><a href="reset_password.php">Mot de passe oublié?</a></p>
</div>

<?php include "components/footer.php";?>

</body>
</html>