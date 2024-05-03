<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("../components/connexion.php");

    $requete = "SELECT username,prec,pts,RANK() OVER (ORDER BY pts DESC) AS rang FROM users 
WHERE id NOT IN (1,2,3,4) ORDER BY pts DESC;";
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
            <th>Rang</th>
            <th>Joueur</th>
            <th>Précision %</th>
            <th>Points</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) {
            $username = $row['username'];
            $prec = $row['prec'];
            $pts = $row['pts'];
            $rang = $row['$rang'];
            $dt_maj = $row['dt_maj'];

            echo "<tr>";
            echo "<td>$rang</td>";
            echo "<td>$username</td>";
            echo "<td>$pts</td>";
            echo "<td>$prec</td>";
            echo "</tr>";
        }?>
    </table>
</div>
<?php include "../components/footer.php";?>

</body>
</html>
