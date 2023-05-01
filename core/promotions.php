<html>
    <head>
        <title>Promotions</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/promotion.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

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

        // Récupération des paramètres de recherche et de filtre
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : '';

        // Préparation de la requête SQL pour récupérer les annonces
        $sql = "SELECT annonces_promotions.*, users.pseudo FROM annonces_promotions JOIN users ON annonces_promotions.author = users.id WHERE 1=1";
        if ($search != '') {
            $sql .= " AND (annonces_promotions.titre LIKE :search OR annonces_promotions.description LIKE :search)";
        }
        if ($filtre != '') {
            $sql .= " AND annonces_promotions.filtre = :filtre";
        }
        $stmt = $pdo->prepare($sql);
        if ($search != '') {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        if ($filtre != '') {
            $stmt->bindParam(':filtre', $filtre);
        }
        $stmt->execute();
        $annonces = $stmt->fetchAll();

        // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
        if (isset($_GET['delete'])) {
            $annonce_id = $_GET['delete'];

            $stmt = $pdo->prepare("DELETE FROM annonces_promotions WHERE id = :annonce_id AND author = :user_id");
            $stmt->bindParam(':annonce_id', $annonce_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            header('Location: promotions.php');
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

        <!-- Formulaire de recherche -->
        <form method="get" action="promotions.php">
            <br><input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <select name="filtre">
                <option value="">None</option>
                <option value="E" <?php if ($filtre == 'E') echo 'selected'; ?>>Ephémère</option>
                <option value="D" <?php if ($filtre == 'D') echo 'selected'; ?>>Durable</option>
            </select>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Affichage des annonces -->
        <?php foreach ($annonces as $annonce): ?>
            <h2><?php echo htmlspecialchars($annonce['titre']); ?></h2>
            Par : <a href="profile.php?pseudo=<?= urlencode($annonce['pseudo']) ?>"> <?php echo htmlspecialchars($annonce['pseudo']); ?></a><br>
            Description : <br><?php echo htmlspecialchars($annonce['description']); ?><br>
            Posté le : <?php echo date('d-m-Y', strtotime($annonce['date_start'])); ?><br>
            <?php if ($annonce['filtre'] == 'E'): ?>
                Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_end'])); ?><br>
            <?php endif; ?>
            <?php if ($user_id == $annonce['author']): ?>
                <form action="?delete=<?php echo urlencode($annonce['id']) ?>">
                    <input type="submit" value="SUPPRIMER"><br>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>

        <script>
            document.querySelector('select[name="filtre"]').onchange = function() {
                this.form.submit();
            };
        </script>
    </body>

    <br>
    <a href="promotions_gerer.php">GERER</a>
</html>