<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Récupération des données du formulaire
    $email_ou_pseudo = $_POST['email_ou_pseudo'];
    $mdp = $_POST['mdp'];

    /**
     * Récupération des informations de l'utilisateur
     * On sélectionne toutes les colonnes de 'users' où l'email ou le pseudo correspondent à la variable $email_ou_pseudo
     */
    $req = $pdo -> prepare('SELECT * FROM users WHERE email = :email_ou_pseudo OR pseudo = :email_ou_pseudo');
    $req -> bindParam(':email_ou_pseudo', $email_ou_pseudo);
    $req -> execute();

    // Récupération des résultats sous la forme d'un tableau contenu dans $user
    $user = $req -> fetch();

    // Vérification du mot de passe
    if($user && password_verify($mdp, $user['mdp'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../aides/aides.php');
    } else {
        // Connexion échouée
        $_SESSION['erreur_message'] = "Adresse e-mail ou mot de passe incorrect.";
        $_SESSION['email_ou_pseudo'] = $email_ou_pseudo;
        header('Location: ../connexion.php');
    }
?>