<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("../components/connexion.php");
    //get User
    $requete = "SELECT * FROM tirages WHERE date_tirage <= CONCAT(CURDATE(), ' 23:59:59') ORDER BY date_tirage DESC";
    $userData = array();
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $result = $stmt->get_result();

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
<div class='container'>
    <table border="1">
        <tr>
            <th>ID du Tirage</th>
            <th>Date du Tirage</th>
            <th>Statut</th>
            <th>Valeur du Tirage</th>
            <th>Actions</th>
        </tr>
<?php while ($row = $result->fetch_assoc()) {
    $idTirage = $row['id'];
    $dateTirage = $row['date_tirage'];
    $isDone = $row['is_done'];
    $vlTirage = $row['vl_tirage'];

    echo "<tr>";
    echo "<td>$idTirage</td>";
    echo "<td>$dateTirage</td>";
    if ($isDone === 0) {
        echo "<td class='ouvert'>Ouvert</td>";
    } else {
        echo "<td class='termine'>Terminé</td>";
    }

    echo "<td>$vlTirage</td>";
    echo "<td>";
    $newDate = substr($dateTirage, 0, 10);
    $today = date("Y-m-d");
    if ($newDate === $today) {
        echo "Indisponible";
    } else {
        echo "<a href='terminer.php?id=$idTirage&date=$newDate'>Actualiser</a>";
    }


    echo "</td>";

    echo "</tr>";
}?>
    </table>
</div>
<?php include "../components/footer.php";?>

</body>
<script>
    const params = new URLSearchParams(window.location.search);
    document.addEventListener("DOMContentLoaded", function() {
        if (params.has('success')) {
            switch (params.get('success')) {
                case '0':
                    alert("Échec de la récupération des résultats depuis la source externe.");
                    break;
                case '1':
                    alert("Le résultat du tirage a bien été récupéré. Celui-ci a donc été clôturé.");
                    break;
                default:
                    break;
            }
        }
    });
</script>
</html>
