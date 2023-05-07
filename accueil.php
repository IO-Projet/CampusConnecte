<?php
    session_start();

    require 'coeur/classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isSet("coeur/aides/aides.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Accueil</title>
        <meta charset="utf-8">
        <link rel="icon" href="icons/accueil.png" type="image/png">
        <link rel="stylesheet" href="css/style.css">
    </head>



    <body>
        <a href="coeur/connexion.php"><button>Connexion</button></a>
    </body>
</html>