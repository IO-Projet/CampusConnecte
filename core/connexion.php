<html>
    <head>
        <title>Connexion</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        header('Location: tableau_aides.php');
        exit;
    }

    if (isset($_SESSION['error_message'])) {
        echo '<p>'.$_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <body>
        <form action="formulaire_connexion.php" method="post">
            <label for="email_or_pseudo">Adresse e-mail ou Pseudo:</label>
            <input type="text" id="email_or_pseudo" name="email_or_pseudo" value="<?php echo isset($_SESSION['email_or_pseudo']) ? htmlspecialchars($_SESSION['email_or_pseudo']) : ''; ?>"><br><br>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password"><br><br>
            <input type="submit" value="Se connecter">
        </form>
        <p>Pas de compte ? <a href="inscription.php">S'inscrire</a></p>
    </body>

    <?php
        unset($_SESSION['email_or_pseudo']);
    ?>
</html>