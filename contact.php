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
    <h1>Contactez-nous!</h1>

    <form action="" method="post"> <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div>
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="message">Message :</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <div>
            <button id='btn_contact' type="">Envoyer</button>
        </div>
    </form>
    <p>> Vous pouvez aussi nous contacter directement par mail, ci-dessous.</p>

</div>

<?php include "components/footer.php";?>

</body>
<script>
    document.getElementById('btn_valider').addEventListener('click', function() {
        alert('Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    });
</script>
</html>
