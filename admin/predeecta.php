<?php

require("../components/connexion.php");

$requete = "SELECT 1 FROM ia_predictions WHERE DATE(dt_prediction) = CURDATE() LIMIT 1";
$stmt = $connexion->prepare($requete);
$stmt->execute();
$nbLignes = $stmt->get_result()->num_rows;
$stmt->close();

$requeteAll = "SELECT 1 FROM ia_predictions";
$stmtAll = $connexion->prepare($requeteAll);
$stmtAll->execute();
$nbPredictions = $stmtAll->get_result()->num_rows;
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
    <p>Vous pouvez paramétrer l'IA lors de son prochain entraînement. Les valeurs entrées vont influer sur le temps d'apprentissage de l'IA.</p>
    <form>
        <label for="integer-input">Epoch (entre 100 et 2000) :</label>
        <input type="number" id="integer-input" name="integer-input" min="100" max="2000" step="1" value="100">
        <input type="submit" value="Envoyer">
    </form>
</div>
<div class="container predeecta">
    <h2>Entraînement manuel</h2>
    <p>Vous pouvez lancer l'entraînement de l'IA une fois par jour.</p>
    <?php
    if ($nbLignes === 1) {
        echo "<p>Predeecta s'est déjà entraînée aujourd'hui...</p>";
        echo "<p>Revenez demain !</p>";
    } else {
        echo "<button id='btn_entrainer' type='button'>Entraîner Predeecta !</button>";
        echo "<p>Cette action peut prendre un certain temps en fonction de la dernière configuration.</p>";
    }

    echo "<p>Predeecta v1.".$nbPredictions."</p>";
    ?>

</div>
<div class="container predeecta">
    <h2>Entraînement automatique</h2>
    <p>Quand cette option est activée, l'IA sera entraînée automatiquement tous les jours à 03:00 du matin</p>
    <button id="btn_auto" type="button">Désactivé</button>
</div>

<?php include "../components/footer.php";?>

</body>
<script>
    document.getElementById('btn_entrainer').addEventListener('click', function () {
        $.ajax({
            url: 'entrainement.php',
            type: 'POST',
            data: {},
            success: function(response) {
                console.log(response);
                if (response) {
                    window.location.href = 'predeecta.php';
                } else {
                    alert('Erreur : L\'entraînement a échoué. Veuillez réessayer.');
                    window.location.href = 'predeecta.php';
                }

            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Une erreur est survenue lors de l\'entraînement de l\'IA. Veuillez réessayer.');
            }
        });
    });
</script>
</html>
