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

    ?>

    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil</a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="tableau_aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
        <br><br>

    </body>
</html>