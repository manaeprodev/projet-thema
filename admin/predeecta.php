<?php

require("../components/connexion.php");
require "jobs/gcloud_data_processor.php";

$requete = "SELECT 1 FROM ia_predictions WHERE DATE(dt_prediction) = CURDATE() LIMIT 1";
$stmt = $connexion->prepare($requete);
$stmt->execute();
$nbLignes = $stmt->get_result()->num_rows;
$stmt->close();

$requeteAll = "SELECT 1 FROM ia_predictions";
$stmtAll = $connexion->prepare($requeteAll);
$stmtAll->execute();
$nbPredictions = $stmtAll->get_result()->num_rows;

$lastParamsData = [
    'epoch' => 100,
    'learning_rate' => 0.2,
    'verbose' => 2,
    'batch_size' => 10000,
];

if ($lastParam = getLastAiParams()) {
    $lastParamsData = json_decode($lastParam, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $epoch = $_POST['integer-input'];
        $lRate = $_POST['learning-input'];
        $verbose = $_POST['verbose'];
        $batchSize = $_POST['batch-size'];

        $data = [
            'epoch' => $epoch,
            'learning_rate' => $lRate,
            'verbose' => $verbose,
            'batch_size' => $batchSize,
        ];

        $filename = date('YmdHis') . '.json';

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents($filename, $jsonData);

        pushToBucket($filename, 'ai_params');

        header('Location : predeecta.php?return=1');
    } catch (Exception $e) {
        header('Location : predeecta.php?return=2');
    }

}
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="integer-input">Epoch (100-2000) :</label>
        <input type="number" id="integer-input" name="integer-input" min="100" max="2000" step="1" value="<?=$lastParamsData['epoch']?>"><br>
        <label for="learning-input">Learning rate (0.1-1) :</label>
        <input type="number" id="learning-input" name="learning-input" min="0.1" max="1" step="0.1" value="<?=$lastParamsData['learning_rate']?>"><br>
        <label for="verbose">Verbose (0-3) :</label>
        <input type="number" id="verbose" name="verbose" min="0" max="3" step="1" value="<?=$lastParamsData['verbose']?>"><br>
        <label for="batch-size">Batch size (200-20000) :</label>
        <input type="number" id="batch-size" name="batch-size" min="200" max="20000" step="200" value="<?=$lastParamsData['batch_size']?>"><br>
        <input id ="btn_param_ia" type="submit" value="Envoyer">
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
                alert('Erreur : L\'entraînement a débuté. Veuillez patienter quelques instants.');
                window.location.href = 'predeecta.php';
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Une erreur est survenue lors de l\'entraînement de l\'IA. Veuillez réessayer.');
            }
        });
    });

    const params = new URLSearchParams(window.location.search);
    document.addEventListener("DOMContentLoaded", function() {
        if (params.has('return')) {
            switch (params.get('return')) {
                case '1':
                    alert("Le paramétrage a bien été sauvegardé. Ils seront utilisés lors du prochain entraînement de l'IA.");
                    break;
                case '2':
                    alert("Une erreur est survenue lors de la sauvegarde des paramètres. Consultez les logs pour plus de détails.");
                    break;
                default:
                    break;
            }
        }
    });
</script>
</html>
