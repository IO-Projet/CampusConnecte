<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';
    require 'classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("connexion.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Contact</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icones/contact.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <a href="aides/aides.php">Retour</a>

        <?php
        if(isset($_SESSION['confirm_message'])) {
            echo '<p>'. $_SESSION['confirm_message'] .'</p>';
            unset($_SESSION['confirm_message']);
        }
        ?>

        <form id="form" action="formulaires/formulaire_message_admin.php" method="post">
            <br>
            <label>
                <textarea name="message" maxlength="2000"></textarea>
            </label>
            <br>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="submit" value="Envoyer">
        </form>

        <script>
            document.querySelector("form").addEventListener('submit', function(event) {
                // Si le message est vide
                if(document.querySelector('textarea').value.trim() === '') {
                    // Annulation de l'envoi
                    event.preventDefault();
                }
            });
        </script>
    </body>
</html>