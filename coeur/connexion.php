<?php
    session_start();

    require 'classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isSet("aides/aides.php");
    $verif -> ifError();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Connexion</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icones/connexion.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <form action="formulaires/formulaire_connexion.php" method="post">
            <label for="email_ou_pseudo">Adresse e-mail ou Pseudo</label>
            <input type="text" id="email_ou_pseudo" name="email_ou_pseudo" value="<?php echo isset($_SESSION['email_ou_pseudo']) ? htmlspecialchars($_SESSION['email_ou_pseudo']) : ''; ?>"><br>
            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp"><br>
            <input type="submit" value="Se connecter">
        </form>
        <p>Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
    </body>

    <?php
        unset($_SESSION['email_ou_pseudo']);
    ?>
</html>