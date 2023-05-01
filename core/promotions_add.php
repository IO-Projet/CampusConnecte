<html>
    <?php
        session_start();

        if (!(isset($_SESSION['user_id']))) {
            header('Location: connexion.php');
            exit;
        }
    ?>

    <head>
        <title>Ajouter une promotion</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/promotion.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
        <script>
            // Fonction pour activer/désactiver le champ date_end
            function toggleDateEnd() {
                var durable = document.getElementById('durable');
                var dateEnd = document.getElementById('date_end');
                if (durable.checked) {
                    dateEnd.disabled = true;
                } else {
                    dateEnd.disabled = false;
                }
            }
        </script>
    </head>

    <body>
        <a href="promotions_gerer.php">Retour</a>

        <h1>Ajouter une promotion</h1>

        <?php if (isset($_SESSION['erreur'])): ?>
            <?php if ($_SESSION['erreur'] == 1): ?>
                <p>Veuillez remplir le titre.</p>
            <?php elseif ($_SESSION['erreur'] == 2): ?>
                <p>Veuillez fournir une description.</p>
            <?php elseif ($_SESSION['erreur'] == 3): ?>
                <p>La date d'expiration doit être postérieure à la date actuelle.</p>
            <?php endif; ?>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <?php
            // Récupération des valeurs précédemment saisies
            $titre = isset($_SESSION['titre']) ? $_SESSION['titre'] : '';
            $description = isset($_SESSION['description']) ? $_SESSION['description'] : '';
            $date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : date('Y-m-d', strtotime('+1 month'));
            $filtre = isset($_SESSION['filtre']) ? $_SESSION['filtre'] : '';

            // Suppression des valeurs précédemment saisies
            unset($_SESSION['titre']);
            unset($_SESSION['description']);
            unset($_SESSION['date_end']);
            unset($_SESSION['filtre']);
        ?>

        <form method="post" action="formulaires/formulaire_promotions_add.php">
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" maxlength="20" value="<?php echo htmlspecialchars($titre); ?>"><br>

            <label for="description">Description :</label><br>
            <textarea name="description" id="description" maxlength="200"><?php echo htmlspecialchars($description); ?></textarea><br>

            <input type="radio" name="filtre" id="ephemere" value="E"<?php if ($filtre == 'E' || $filtre == '') echo ' checked'; ?> onclick="toggleDateEnd()">
            <label for="ephemere">Ephémère</label><br>
            <input type="radio" name="filtre" id="durable" value="D"<?php if ($filtre == 'D') echo ' checked'; ?> onclick="toggleDateEnd()">
            <label for="durable">Durable</label><br>

            <label for="date_end">Date d'expiration :</label>
            <input type="date" name="date_end" id="date_end" value="<?php echo htmlspecialchars($date_end); ?>"><br>

            <input type="submit" value="Ajouter">
        </form>

        <!-- Désactivation du champ date_end si la case Durable est cochée -->
        <script>
            toggleDateEnd();
        </script>
    </body>
</html>