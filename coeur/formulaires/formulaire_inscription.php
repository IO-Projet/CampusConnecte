<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Récupération des données du formulaire
    $pseudo = strtolower($_POST['pseudo']);
    $date = '0000-00-00'; // obliger de définir la une date par defaut sinon égale à NULL
    $email = strtolower($_POST['email']);
    $mdp = strtolower($_POST['mdp']);

    if(empty($pseudo) || empty($email)) {
        // L'un des deux champs n'a pas été rempli
        $_SESSION['erreur_message'] = 'Veuillez remplir les champs <b>Adresse e-mail</b> et <b>Pseudo</b>.';
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['email'] = $email;
        header('Location: ../inscription.php');
        exit;
    }
    /*
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // L'un des deux champs n'a pas été rempli
        $_SESSION['erreur_message'] = 'Cette adresse e-mail est invalide.';
        $_SESSION['email'] = $email;
        header('Location: ../inscription.php');
        exit;
    }
    */

    // Validation si le mot de passe est conforme pour une bonne sécurité
    if(strlen($mdp) < 4 || strlen($mdp) > 30) {
        // Mot de passe invalide
        $_SESSION['erreur_message'] = "Le mot de passe doit être supérieur à 3 caractères et inférieur à 31 caractères.";
        $_SESSION['email'] = $email;
        header('Location: ../inscription.php');
        exit;
    }
    $hash = password_hash($mdp, PASSWORD_BCRYPT);

    // Vérification si l'adresse e-mail et le pseudo existent déjà dans la base de données
    $req = $pdo -> prepare('SELECT * FROM users WHERE email = :email OR pseudo = :pseudo');
    $req -> bindParam(':email', $email);
    $req -> bindParam(':pseudo', $pseudo);
    $req -> execute();
    $user = $req -> fetch();

    if($user) {
        if($pseudo == $user['pseudo']) {
            $_SESSION['erreur_message'] = "Un compte avec ce pseudo existe déjà.";
            $_SESSION['pseudo'] = $pseudo;
            header('Location: ../inscription.php');
            $_SESSION['user_id'] = $user['id'];
            exit;
        }

        if($email == $user['email']) {
            $_SESSION['erreur_message'] = "Un compte avec cette adresse e-mail existe déjà.";
            $_SESSION['email'] = $email;
            header('Location: ../inscription.php');
            $_SESSION['user_id'] = $user['id'];
            exit;
        }
    }

    // Insertion des données dans la base de données
    $req = $pdo -> prepare("INSERT INTO users (pseudo, date, email, mdp) VALUES (:pseudo, :date, :email, :mdp)");
    $req -> bindParam(':pseudo', $pseudo);
    $req -> bindParam(':date', $date);
    $req -> bindParam(':email', $email);
    $req -> bindParam(':mdp', $hash);
    $req -> execute();

    header('Location: ../aides/aides.php');
?>