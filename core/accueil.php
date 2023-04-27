<html>
    <head>
        <title>Accueil</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <?php
        session_start();

        if (isset($_SESSION['user_id'])) {
            header('Location: aides.php');
            exit;
        }
    ?>

    <body>
        <a href="inscription.php"><button>Inscription</button></a><br><br>
        <a href="connexion.php"><button>Connexion</button></a>
    </body>
</html>