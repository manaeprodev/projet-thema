<header>
    <h1 id="project_title">PREDEECT</h1>

</header>
    <nav>
        <a class="nav_item" href="<?=getenv('ROOT_DIR') ?>/menu.php">ACCUEIL</a>
        <a class="nav_item" href="<?=getenv('ROOT_DIR') ?>/tirages.php">TIRAGES</a>
        <a class="nav_item" href="<?=getenv('ROOT_DIR') ?>/apropos.php">À PROPOS</a>
        <a class="nav_item" href="<?=getenv('ROOT_DIR') ?>/contact.php">CONTACT</a>
        <a class="nav_item" href="<?=getenv('ROOT_DIR') ?>/mon_profil.php">PROFIL</a>
        <?php
        session_start();
        if (getenv('ENV') === 'dev') {
            $userData = array();
            $userData[0]['username'] = "TIRYAKT";
            $userData[0]['luckyNumber'] = "2";
            $userData[0]['createdDate'] = "2024-02-14";
            $userData[0]['lastUpdatedDate'] = "2024-04-10";
        } elseif (!isset($_SESSION['user'])) {
            header("Location: index.php?inscription_reussie=2");
        } else {
            require './components/connexion.php';
            $requete = "SELECT * FROM users WHERE username = ? LIMIT 1";
            $userData = array();
            $stmt = $connexion->prepare($requete);
            $stmt->bind_param('s', $_SESSION['user'][0]['username']);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $userData[] = $row;
            }

            if ($userData[0]['is_admin'] === 1) {
                echo "<a class='nav_item' href='".getenv('ROOT_DIR')."/admin/index.php'>ADMIN</a>";
            }
        }
        ?>
    </nav>
