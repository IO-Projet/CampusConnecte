<html>
    <head>
        <title>Aide</title>
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
        $pseudo = htmlspecialchars($user['pseudo']);

    ?>

    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil - <?php echo $pseudo ?> </a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="tableau_aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
        <br><br>

    </body>
</html>