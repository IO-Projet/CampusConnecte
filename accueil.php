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
        <link rel="stylesheet" href="css/boite.css">
    </head>

    <body>
        <header>
            <h1>
                <img src="icones/logo.png" alt="Logo" width="32" height="32">
                - Campus Connecté
            </h1>
            <nav class="nav">
                <a href="coeur/connexion.php">
                    <button class="btn">Connexion</button>
                </a>
            </nav>
        </header>

        <div class="boite-accueil">
            <h2>📝 - Présentation</h2>
            <p>Vous êtes étudiant et vous cherchez un endroit pour obtenir de l’aide pour vos devoirs,
                partager des informations et des offres spéciales pour les étudiants ?<br>
                Ne cherchez plus, Campus Connecté est là pour vous ! <br><br>

                Notre site, créé par des étudiants pour des étudiants, offre un espace de partage et d’entraide unique. <br>
                Avec notre coin "aide aux devoirs", vous pouvez demander de l’aide en postant une annonce et obtenir des réponses de la part d’autres étudiants. <br>
                De plus, notre système d’information vous tient au courant des dernières offres et promotions spéciales pour les étudiants. <br>
                Mais ce n’est pas tout ! <br> Campus Connecté offre également d’autres options pour vous aider dans votre vie étudiante, telles que des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant. <br><br>

                Rejoignez notre communauté dès maintenant et profitez de tous les avantages que Campus Connecté a à offrir ! <br><br>
            </p>

            <h2>🤔 - Pourquoi choisir notre site ?</h2>
            <ul>
                <li>Créé par des étudiants pour des étudiants.</li>
                <li>Coin “aide aux devoirs” pour demander de l’aide en postant une annonce.</li>
                <li>Système d’information pour les offres et promotions spéciales pour les étudiants.</li>
                <li>Possibilité pour les utilisateurs d’ajouter des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant.</li>
                <li>Espace de partage et d’entraide unique pour les étudiants.</li>
            </ul>
        </div>
    </body>
</html>