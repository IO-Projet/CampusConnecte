<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';

    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['mdp'];
    $conf_password = $_POST['confirm_mdp'];

    // Validation du mot de passe
    if(strlen($password) < 4 || strlen($password) > 30) {
        // Mot de passe invalide
        $_SESSION['message_erreur'] = "Le mot de passe doit être supérieur à 3 caractères et inférieur à 31 caractères.";
        $_SESSION['email'] = $email;
        header('Location: inscription.php');
        exit;
    }

    // Vérification si l'adresse e-mail existe déjà dans la base de données
    $req_mail = $pdo -> prepare("SELECT * FROM users WHERE email = :email");
    $req_mail -> bindParam(':email', $email);
    $req_mail -> execute();
    $user = $req_mail -> fetch();

    if($user) {
        // L'adresse e-mail existe déjà dans la base de données
        $_SESSION['message_erreur'] = "Un compte avec cette adresse e-mail existe déjà.";
        $_SESSION['email'] = $email;
        header('Location: inscription.php');
        exit;
    }

    // Vérification si les mots de passe correspondent
    if($password != $conf_password) {
        $_SESSION['message_erreur'] = "Les deux mots de passes ne correspondent pas.";
        header('Location: inscription.php');
        exit;
    }

    // Insertion des données dans la base de données
    $req_inscription = $pdo -> prepare("INSERT INTO users (email, mot_de_passe) VALUES (:email, :password)");
    $req_inscription -> bindParam(':email', $email);
    $req_inscription -> bindParam(':password', $password);
    $req_inscription -> execute();

    header('Location: verification.php');
?>