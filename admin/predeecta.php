<?php

require("../components/connexion.php");

$requete = "SELECT 1 FROM ia_predictions WHERE DATE(dt_prediction) = CURDATE() LIMIT 1";
$stmt = $connexion->prepare($requete);
$stmt->execute();
$nbLignes = $stmt->num_rows;
?>
<!DOCTYPE html>
<html lang="fr">
<?php include "../components/head.php";?>
<body>
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="../assets/style.css">
</head>


<div class="container predeecta">
    <h2>Paramétrage</h2>
    <p>Vous pouvez paramétrer l'IA lors de son prochain entraînement.</p>
    <form>
        <label for="integer-input">Epoch (entre 100 et 2000) :</label>
        <input type="number" id="integer-input" name="integer-input" min="100" max="2000" step="1" value="100">
        <input type="submit" value="Envoyer">
    </form>
</div>
<div class="container predeecta">
    <h2>Entraînement</h2>
    <p>Vous pouvez lancer l'entraînement de l'IA une fois par jour.</p>
    <?php
    if ($nbLignes === 1) {
        echo "<p>Predeecta s'est déjà entraînée aujourd'hui...</p>";
        echo "<p>Revenez demain !</p>";
    } else {
        echo "<button id='btn_valider' type='button' hidden>Entraîner Predeecta !</button>";
    }
    ?>

</div>

<?php include "../components/footer.php";?>

</body>
</html>
