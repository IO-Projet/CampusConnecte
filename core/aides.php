<html>
    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

        // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
        if (!(isset($_SESSION['user_id']))) {
            header('Location: connexion.php');
            exit;
        }

        // Récupération de l'ID de l'utilisateur connecté
        $user_id = $_SESSION['user_id'];

        // Récupération des informations de profil de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();

        // Récupération des informations de profil de l'utilisateur
        $pseudo_user_connecter = htmlspecialchars($user['pseudo']);

        // Récupération des thèmes sélectionnés
        $selected_themes = isset($_GET['themes']) ? $_GET['themes'] : [];

        // Construction de la requête SQL pour récupérer les annonces en fonction des thèmes sélectionnés
        $sql = "SELECT annonces_aides.*, users.pseudo, matieres.nom FROM annonces_aides JOIN users ON annonces_aides.author = users.id JOIN matieres ON annonces_aides.theme = matieres.id";
        if (!empty($selected_themes)) {
            $sql .= " WHERE matieres.nom IN (";
            foreach ($selected_themes as $theme) {
                $sql .= $pdo->quote($theme) . ",";
            }
            $sql = rtrim($sql, ",") . ")";
        }

        // Exécution de la requête SQL
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $annonces = $stmt->fetchAll();

        // Récupération des thèmes uniques et de leur nombre d'occurrences
        $stmt = $pdo->prepare("SELECT matieres.nom, COUNT(*) as count FROM annonces_aides JOIN matieres ON annonces_aides.theme = matieres.id GROUP BY matieres.nom");
        $stmt->execute();
        $themes = $stmt->fetchAll();

        // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
        if (isset($_GET['delete'])) {
            $annonce_id = $_GET['delete'];

            $stmt = $pdo->prepare("DELETE FROM annonces_aides WHERE id = :annonce_id AND author = :user_id");
            $stmt->bindParam(':annonce_id', $annonce_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            header('Location: aides.php');
            exit;
        }
    ?>

    <head>
        <title>Aide</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil - <?php echo $pseudo_user_connecter ?> </a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>

        <!-- Liste des thèmes -->
        <form method="get" id="filter-form">
            <h1>Légende</h1>
            <?php foreach ($themes as $theme): ?>
                <label>
                    <input type="checkbox" name="themes[]" value="<?php echo htmlspecialchars($theme['nom']) ?>" <?php if (in_array($theme['nom'], $selected_themes)): ?>checked<?php endif; ?>>
                    <?php echo htmlspecialchars($theme['nom']) ?> (<?php echo $theme['count'] ?>)
                </label><br>
            <?php endforeach; ?>
        </form>

        <!-- Liste des annonces -->
        <?php foreach ($annonces as $annonce): ?>
            <h2><?php echo htmlspecialchars($annonce['nom']) ?></h2>
            <p>Par : <a href="profile.php?pseudo=<?= urlencode($annonce['pseudo']) ?>"> <?php echo htmlspecialchars($annonce['pseudo']); ?></a><br>
                Description : <br><?php echo nl2br(htmlspecialchars($annonce['description'])) ?><br>
                Date de création : <?php echo date('d-m-Y', strtotime($annonce['date_start'])) ?><br>
                Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_end'])) ?></p>
            <?php if ($user_id == $annonce['author']): ?>
                <a href="?delete=<?php echo urlencode($annonce['id']) ?>">Supprimer</a><br>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- JavaScript pour soumettre le formulaire automatiquement lorsque l'utilisateur clique sur une case à cocher -->
        <!-- L'URL est donc changé avec des caractères d'échappement pour éviter tout conflit HTML. → %5b = [ | %5D = ] -->
        <script>
            document.querySelectorAll('#filter-form input[type=checkbox]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    document.querySelector('#filter-form').submit();
                });
            });
        </script>
    </body>

    <br>
    <a href="aides_add.php">ADD</a>
</html>