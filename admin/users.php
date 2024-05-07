<?php
session_start();
if (getenv('ENV') === 'dev') {
    $_SESSION['user'] = "TIRYAKT";
} elseif (!isset($_SESSION['user'])) {
    header("Location: index.php?inscription_reussie=2");
} else {
    require("../components/connexion.php");
    $requete = "SELECT id, username, email, createdDate, lastUpdatedDate, luckyNumber, wants_emails, is_admin FROM users ORDER BY id ASC";
    $stmt = $connexion->prepare($requete);
    $stmt->execute();
    $resultUsers = $stmt->get_result();

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


<div class="container">
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>E-mail</th>
            <th>Date de création</th>
            <th>Date de dernière MAJ</th>
            <th>Numéro favori</th>
            <th>Reçoit e-mails</th>
            <th>Est Admin</th>
        </tr>
        <?php while ($row = $resultUsers->fetch_assoc()) {
            $idUser = $row['id'];
            $username = $row['username'];
            $email = $row['email'];
            $createdDate = $row['createdDate'];
            $lastUpDate = $row['lastUpdatedDate'];
            $luckyNumber = $row['luckyNumber'];
            $wantsEmail = $row['wants_email'];
            $isAdmin = $row['is_admin'];
            echo "<tr>";
            echo "<td>$idUser</td>";
            echo "<td>$username</td>";
            echo "<td>$email</td>";
            echo "<td>$createdDate</td>";
            echo "<td>$lastUpDate</td>";
            echo "<td>$luckyNumber</td>";
            echo "<td>$wantsEmail</td>";
            echo "<td>$isAdmin</td>";
            echo "</tr>";
        } ?>
    </table>
</div>

<?php include "../components/footer.php";?>

</body>
</html>
