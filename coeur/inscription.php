<?php
    session_start();

    require 'classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isSet("aides/aides.php");
    $verif -> siErreur();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Inscription</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icones/ajout.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <form action="formulaires/formulaire_inscription.php" method="post">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : ''; ?>"><br><br>
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>"><br><br>
            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp"><br><br>
            <input type="submit" value="S'inscrire">
        </form>
        <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
    </body>

    <?php
        unset($_SESSION['pseudo']);
        unset($_SESSION['email']);
    ?>
</html>