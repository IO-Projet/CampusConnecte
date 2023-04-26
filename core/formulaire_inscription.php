<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';

    // Récupération des données du formulaire
    $pseudo = strtolower($_POST['pseudo']);
    $email = strtolower($_POST['email']);
    $password = strtolower($_POST['password']);

    // Validation du mot de passe
    if (strlen($password) < 4 || strlen($password) > 30) {
        // Mot de passe invalide
        $_SESSION['error_message'] = "Le mot de passe doit être supérieur à 3 caractères et inférieur à 31 caractères";
        $_SESSION['email'] = $email;
        header('Location: inscription.php');
        exit;
    }

    // Vérification si l'adresse e-mail existe déjà dans la base de données
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email OR pseudo = :pseudo");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        if ($user['email'] == $email) {
            // L'adresse e-mail existe déjà dans la base de données
            $_SESSION['error_message'] = "Un compte avec cette adresse e-mail existe déjà";
            $_SESSION['email'] = $email;
            header('Location: inscription.php');
            exit;
        } else if ($user['pseudo'] == $pseudo) {
            // Le pseudo existe déjà dans la base de données
            $_SESSION['error_message'] = "Un compte avec ce pseudo existe déjà";
            $_SESSION['pseudo'] = $pseudo;
            header('Location: inscription.php');
            exit;
        }
    }

    // Insertion des données dans la base de données
    $stmt = $dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Connexion réussie
    $_SESSION['user_id'] = $user['id'];
    header('Location: tableau_aides.php');
?>