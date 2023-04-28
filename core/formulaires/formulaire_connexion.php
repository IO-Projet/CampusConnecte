<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Récupération des données du formulaire
    $email_or_pseudo = $_POST['email_or_pseudo'];
    $password = $_POST['password'];

    // Récupération des informations de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email_or_pseudo OR pseudo = :email_or_pseudo");
    $stmt->bindParam(':email_or_pseudo', $email_or_pseudo);
    $stmt->execute();
    $user = $stmt->fetch();

    // Vérification du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../aides.php');
    } else {
        // Connexion échouée
        $_SESSION['error_message'] = "Adresse e-mail ou mot de passe incorrect";
        $_SESSION['email_or_pseudo'] = $email_or_pseudo;
        header('Location: ../connexion.php');
    }
?>