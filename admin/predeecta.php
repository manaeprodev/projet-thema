<?php
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
    <form>
        <label for="integer-input">Epoch (entre 100 et 2000) :</label>
        <input type="number" id="integer-input" name="integer-input" min="100" max="2000" step="1" value="100">
        <input type="submit" value="Envoyer">
    </form>
</div>

<?php include "../components/footer.php";?>

</body>
</html>
