<html>
    <head>
        <title>Promotions</title>
        <meta charset="utf-8">
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
        $pseudo_user_connecter = htmlspecialchars($user['pseudo']);

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
    ?>

    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil - <?php echo $pseudo_user_connecter ?> </a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>

        <!-- Formulaire de recherche -->
        <form method="get" action="promotions.php">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
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
        <?php endforeach; ?>

        <script>
            document.querySelector('select[name="filtre"]').onchange = function() {
                this.form.submit();
            };
        </script>
    </body>

    <br>
    <a href="promotions_add.php">ADD</a>
</html>