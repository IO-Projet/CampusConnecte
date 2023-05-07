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
        <link rel="icon" href="icones/accueil.png" type="image/png">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <a href="inscription.php"><button>Inscription</button></a>
        <a href="coeur/connexion.php"><button>Connexion</button></a>

        <h2>Présentation</h2>
        <pre>Vous êtes étudiant et vous cherchez un endroit pour obtenir de l’aide pour vos devoirs, partager des informations et des offres spéciales pour les étudiants ? Ne cherchez plus, Campus Connecté est là pour vous !

Notre site, créé par des étudiants pour des étudiants, offre un espace de partage et d’entraide unique.
Avec notre coin “aide aux devoirs”, vous pouvez demander de l’aide en postant une annonce et obtenir des réponses de la part d’autres étudiants.
De plus, notre système d’information vous tient au courant des dernières offres et promotions spéciales pour les étudiants.
Mais ce n’est pas tout !

Campus Connecté offre également d’autres options pour vous aider dans votre vie étudiante, telles que des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant.

Rejoignez notre communauté dès maintenant et profitez de tous les avantages que Campus Connecté a à offrir !
        </pre>

        <h2>Pourquoi choisir notre site ?</h2>
        <ul>
            <li>Créé par des étudiants pour des étudiants.</li>
            <li>Coin “aide aux devoirs” pour demander de l’aide en postant une annonce.</li>
            <li>Système d’information pour les offres et promotions spéciales pour les étudiants.</li>
            <li>Possibilité pour les utilisateurs d’ajouter des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant.</li>
            <li>Espace de partage et d’entraide unique pour les étudiants.</li>
        </ul>
    </body>
</html>