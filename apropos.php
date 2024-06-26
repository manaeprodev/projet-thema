<?php
?>

<!DOCTYPE html>
<html lang="fr">
<?php include "components/head.php";?>
<body>
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="assets/style.css">
</head>

<div class="container">
    <h1>À propos de PREDEECT...</h1>
    <h2>Qui sommes-nous?</h2>
    <p>
        Cette inferface WEB est un projet d'étude réalisé par<br>Alexandre DUBOIS et Tony TIRYAKI.</p>
    <p>
        Étudiants à l'INSSET de Saint-Quentin en deuxième année de Master Cloud Computing & Mobility, nous avons
        l'opportunité d'effectuer un projet
        mêlant des technologies que nous avons découvertes au cours de nos deux ans au sein de ce Master.

    </p>
    <h2>Qu'est-ce que PREDEECT?</h2>
    <p>Avant d'être un projet qui porte ses fruits, PREDEECT est avant tout une étude sur l'aléatoire.<br>
        Cette étude conjugue Intelligence Artificielle et Expérience Cloud.
    Notre but est d'exploiter le potentiel de l'Intelligence Artificielle sur la grande problématique de l'aléatoire.
    </p>
    <h2>Comment jouer?</h2>
    <p>Rendez-vous à l'accueil et commencez une prédiction! Vous pouvez choisir de suivre les prédictions de notre
    IA, ou pas! Faites comme bon vous semble.</p>
    <h2>Système de points</h2>
    <p>À la fin de chaque tirage, des points sont attribués à votre compte pour vous mesurer aux autres utilisateurs.
    Ces points sont calculés en fonction de vos performances et de la précision de vos prédictions.
    Voici le tableau des scores :</p>
    <table border="1">
        <tr>
            <th>Description</th>
            <th>Nombre de points</th>
        </tr>
        <tr>
            <td>Jouer au tirage</td>
            <td>+1 pt</td>
        </tr>
        <tr>
            <td>1 boule correcte</td>
            <td>+1 pt</td>
        </tr>
        <tr>
            <td>2 boules correctes</td>
            <td>+3 pts</td>
        </tr>
        <tr>
            <td>3 boules correctes</td>
            <td>+7 pts</td>
        </tr>
        <tr>
            <td>4 boules correctes</td>
            <td>+15 pts</td>
        </tr>
        <tr>
            <td>5 boules correctes</td>
            <td>+31 pts</td>
        </tr>
        <tr>
            <td>Boule chance correcte</td>
            <td>+6 pts</td>
        </tr>
    </table>
    <p>Vous pouvez consulter vos points et votre rang dans l'onglet CLASSEMENT.</p>
    <h2>Disclaimers</h2>
    <p>Ce projet prend les résultats de la Française des Jeux à l'aide de webscrapping et ses développeurs ne sont
    pas affiliés à celle-ci. Toute prédiction effectuée sur notre site est à titre informatif et statistique
    et n'est pas à but lucratif.</p>
    <h1>Que la chance vous sourit!</h1>
</div>

<?php include "components/footer.php";?>

</body>
</html>

