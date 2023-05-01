<html>
    <head>
        <title>Contacte</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/contact.png" type="image/png">
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
    ?>

    <body>
        <a href="aides.php">Retour</a>

        <?php if (isset($_SESSION['confirmation_message'])): ?>
            <p><?php echo $_SESSION['confirmation_message']; ?></p>
            <?php unset($_SESSION['confirmation_message']); ?>
        <?php endif; ?>

        <form action="formulaires/formulaire_message_admin.php" method="post">
            <br><textarea name="message" maxlength="2000"></textarea><br>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="submit" value="ENVOYER">
        </form>

        <script>
            document.querySelector('form').addEventListener('submit', function(event) {
                if (document.querySelector('textarea').value.trim() === '') {
                    event.preventDefault();
                }
            });
        </script>
    </body>
</html>