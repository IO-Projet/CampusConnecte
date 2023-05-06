<html>
    <head>
        <title>Inscription</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/add.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    
    <?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        header('Location: aides.php');
        exit;
    }

    if (isset($_SESSION['error_message'])) {
        echo '<p>'.$_SESSION['error_message'].'</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <body>
        <form action="formulaires/formulaire_inscription.php" method="post">
            <label for="email">Pseudo:</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : ''; ?>"><br><br>

            <label for="email">Adresse e-mail:</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>"><br><br>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password"><br><br>

            <input type="submit" value="S'inscrire">
        </form>
        <p>Déjà inscrit ? <a href="connexion.php">Se Connecter</a></p>
    </body>

    <?php
    unset($_SESSION['email']);
    ?>
</html>