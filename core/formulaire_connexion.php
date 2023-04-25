<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';

    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['mdp'];

    // Vérification des informations de connexion
    $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email AND mot_de_passe = :password");
    $stmt -> bindParam(':email', $email);
    $stmt -> bindParam(':password', $password);
    $stmt -> execute();
    $user = $stmt -> fetch();

    if($user) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        header('Location: verification.php');
    } else {
        // Connexion échouée
        $_SESSION['message_erreur'] = "Adresse e-mail ou mot de passe incorrect.";
        $_SESSION['email'] = $email;
        header('Location: connexion.php');
        exit;
    }
?>