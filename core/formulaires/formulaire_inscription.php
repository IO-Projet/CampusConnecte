<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Récupération des données du formulaire
    $pseudo = strtolower($_POST['pseudo']);
    $date = '0000-00-00'; // obliger de définir la une date par defaut sinon égale à NULL
    $email = strtolower($_POST['email']);
    $password = strtolower($_POST['password']);

    // Validation si le pseudo n'est pas vide, et si le mot de passe est conforme pour une bonne sécurité
    if (strlen($password) < 4 || strlen($password) > 30) {
        // Mot de passe invalide
        $_SESSION['error_message'] = "Le mot de passe doit être supérieur à 3 caractères et inférieur à 31 caractères";
        $_SESSION['email'] = $email;
        header('Location: ../inscription.php');
        exit;
    } else if (empty($_POST['pseudo'])) {
        // Le champ pseudo n'à pas été remplis
        $_SESSION['error_message'] = "Le champ Pseudo est vide";
        $_SESSION['email'] = $email;
        header('Location: ../inscription.php');
        exit();
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
    }

    // Vérification si l'adresse e-mail et le pseudo existent déjà dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR pseudo = :pseudo");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        if ($user['email'] == $email) {
            // L'adresse e-mail existe déjà dans la base de données
            $_SESSION['error_message'] = "Un compte avec cette adresse e-mail existe déjà";
            $_SESSION['email'] = $email;
            header('Location: ../inscription.php');
            exit;
        } else if ($user['pseudo'] == $pseudo) {
            // Le pseudo existe déjà dans la base de données
            $_SESSION['error_message'] = "Un compte avec ce pseudo existe déjà";
            $_SESSION['pseudo'] = $pseudo;
            header('Location: ../inscription.php');
            exit;
        }
    }

    // Insertion des données dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (pseudo, date, email, password) VALUES (:pseudo, :date, :email, :password)");
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hash);
    $stmt->execute();

    // Connexion réussie
    $_SESSION['user_id'] = $user['id'];
    header('Location: ../aides.php');
?>