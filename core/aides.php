<html>
    <head>
        <title>Aide</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/help.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

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
        $page_user_pseudo = htmlspecialchars($user['pseudo']);

        // Récupération des thèmes sélectionnés
        $selected_themes = isset($_GET['themes']) ? $_GET['themes'] : [];

        // Construction de la requête SQL pour récupérer les annonces en fonction des thèmes sélectionnés
        $sql = "SELECT annonces_aides.*, users.pseudo, matieres.nom FROM annonces_aides JOIN users ON annonces_aides.author = users.id JOIN matieres ON annonces_aides.theme = matieres.id";
        if (!empty($selected_themes) || (isset($_GET['favoris']) && $_GET['favoris'] == 1)) {
            $sql .= " WHERE ";
            if (!empty($selected_themes)) {
                $sql .= "matieres.nom IN (";
                foreach ($selected_themes as $theme) {
                    $sql .= $pdo->quote($theme).",";
                }
                $sql = rtrim($sql, ",").")";
            }
            if (isset($_GET['favoris']) && $_GET['favoris'] == 1) {
                if (!empty($selected_themes)) {
                    $sql .= " AND ";
                }
                $sql .= "annonces_aides.id IN (SELECT annonce_id FROM annonces_aides_likes WHERE user_id = ".$pdo->quote($user_id).")";
            }
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

        if (isset($_POST['like'])) {
            $annonce_id = $_POST['like'];

            // Vérifie si l'utilisateur a déjà liké l'annonce
            $stmt = $pdo->prepare('SELECT * FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
            $stmt->execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
            $like = $stmt->fetch();

            if ($like) {
                // Si l'utilisateur a déjà liké l'annonce, supprime le like
                $stmt = $pdo->prepare('DELETE FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
                $stmt->execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
            } else {
                // Sinon, ajoute un like
                $stmt = $pdo->prepare('INSERT INTO annonces_aides_likes (user_id, annonce_id) VALUES (:user_id, :annonce_id)');
                $stmt->execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
            }

            header('Location: aides.php');
            exit;
        }
    ?>

    <body>
        <!-- Menu -->
        <a href="profile.php" title="Profil - <?php echo $page_user_pseudo ?>"><img src="../icons/user.png" alt="Profil - <?php echo $page_user_pseudo ?>" width="32" height="32"></a><br>
        <a href="message_prives.php" title="Messages privés"><img src="../icons/message.png" alt="Messages privés" width="32" height="32"></a><br>
        <a href="aides.php" title="Aide"><img src="../icons/help.png" alt="Aide" width="32" height="32"></a><br>
        <a href="promotions.php" title="Promotions"><img src="../icons/promotion.png" alt="Promotions" width="32" height="32"></a><br>
        <?php if ($user['admin'] == 1) : ?>
            <a href="adminpannel.php" title="Panneau d\'administration"><img src="../icons/admin.png" alt="Panneau d\'administration" width="32" height="32"></a><br>
        <?php else : ?>
            <a href="contacte.php" title="Contacte Administrateurs"><img src="../icons/contact.png" alt="Contacte Administrateurs" width="32" height="32"></a><br>
        <?php endif; ?>
        <a href="deconnexion.php" title="Déconnexion"><img src="../icons/logout.png" alt="Déconnexion" width="32" height="32"></a><br>

        <!-- Liste des thèmes -->
        <form method="get" id="filter-form">
            <h1>Légende</h1>
            <?php foreach ($themes as $theme): ?>
                <label>
                    <input type="checkbox" name="themes[]" value="<?php echo htmlspecialchars($theme['nom']) ?>"
                           <?php if (in_array($theme['nom'], $selected_themes)): ?>
                               checked
                           <?php endif; ?>
                    >
                    <?php echo htmlspecialchars($theme['nom']) ?> (<?php echo $theme['count'] ?>)
                </label><br>
            <?php endforeach; ?>
            <label>
                <input type="checkbox" name="favoris" value="1"
                       <?php if (isset($_GET['favoris'])): ?>
                           checked
                       <?php endif; ?>
                >
                Favoris
            </label><br>
        </form>

        <!-- Liste des annonces -->
        <?php foreach ($annonces as $annonce): ?>
            <?php
                // Compte le nombre total de likes pour l'annonce
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM annonces_aides_likes WHERE annonce_id = :annonce_id');
                $stmt->execute(['annonce_id' => $annonce['id']]);
                $like_count = $stmt->fetchColumn();

                // Vérifie si l'utilisateur a déjà liké l'annonce
                $stmt = $pdo->prepare('SELECT * FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
                $stmt->execute(['user_id' => $user_id, 'annonce_id' => $annonce['id']]);
                $like = $stmt->fetch();

                // Définit le texte du bouton en fonction de si l'utilisateur a déjà liké l'annonce ou non
                $button_text = $like ? 'Dislike' : 'Like';
            ?>
            <h2><?php echo htmlspecialchars($annonce['nom']) ?></h2>
            <p>Par : <a href="profile.php?pseudo=<?= urlencode($annonce['pseudo']) ?>"> <?php echo htmlspecialchars($annonce['pseudo']); ?></a><br>
                Description : <br><?php echo nl2br(htmlspecialchars($annonce['description'])) ?><br>
                Date de création : <?php echo date('d-m-Y', strtotime($annonce['date_start'])) ?><br>
                Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_end'])) ?></p>
            <?php if ($user_id == $annonce['author'] || $user['admin'] == 1): ?>
                Like(s) (<?php echo $like_count; ?>)
                <form action="?delete=<?php echo urlencode($annonce['id']) ?>">
                    <input type="submit" value="SUPPRIMER"><br>
                </form>
            <?php else: ?>
                <form method="post">
                    <input type="hidden" name="like" value="<?php echo $annonce['id']; ?>">
                    <button type="submit"><?php echo $button_text; ?> (<?php echo $like_count; ?>)</button>
                </form>
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
    <a href="aides_gerer.php">GERER</a>
</html>