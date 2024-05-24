<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("components/connexion.php");

    $requete = "SELECT username,prec,pts,prestige,RANK() OVER (ORDER BY pts DESC) AS rang FROM users 
WHERE id NOT IN (1,2,3,4) ORDER BY pts DESC;";
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $rankResult = $stmt->get_result();

}
?>
<!DOCTYPE html>
<html lang="fr">
<?php include "components/head.php";?>
<body>
<head>
    <?php include "components/header.php";?>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<div class='container'>
    <table>
        <tr class="score_table">
            <th class="score_head">Rang</th>
            <th class="score_head">Joueur</th>
            <th class="score_head pts">Points</th>
            <th class="score_head">Prestige</th>
            <th class="score_head">Précision</th>
        </tr>
        <?php while ($row = $rankResult->fetch_assoc()) {
            $username = $row['username'];
            $prec = $row['prec'];
            $pts = $row['pts'];
            $prestige = $row['prestige'];
            $rang = $row['rang'];
            $addedClass = "";

            if ($rang === 1) {
                $addedClass = "first_player";
            }

            echo "<tr>";
            echo "<td class='$addedClass pts'>$rang</td>";
            echo "<td class='$addedClass'>$username</td>";
            echo "<td class='$addedClass pts'>$pts</td>";
            echo "<td class='$addedClass'>$prestige</td>";
            echo "<td class='$addedClass'>$prec%</td>";
            echo "</tr>";
        }?>
    </table>
</div>
<?php include "components/footer.php";?>

</body>
</html>
