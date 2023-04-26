<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';

    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: connexion.php');
        exit;
    }

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Vérification si le formulaire a été soumis
    if (isset($_POST['nom']) || isset($_POST['prenom']) || isset($_POST['pseudo']) || isset($_POST['sexe']) || isset($_POST['email']) || isset($_POST['new_password']) || isset($_POST['date_de_naissance']) || isset($_POST['biography'])) {
        // Récupération des données du formulaire
        $nom = isset($_POST['nom']) ? strtolower($_POST['nom']) : null;
        $prenom = isset($_POST['prenom']) ? strtolower($_POST['prenom']) : null;
        $pseudo = isset($_POST['pseudo']) ? strtolower($_POST['pseudo']) : null;
        $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : null;
        $email = isset($_POST['email']) ? strtolower($_POST['email']) : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : null;
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
        $date_de_naissance = isset($_POST['date_de_naissance']) ? $_POST['date_de_naissance'] : null;
        $biography = isset($_POST['biography']) ? $_POST['biography'] : null;

        // Validation des données
        $errors = [];

        // Vérification du mot de passe actuel
        if ($new_password) {
            if ($password) {
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :user_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                if ($password != $stmt->fetchColumn()) {
                    $errors[] = "Le mot de passe actuel est incorrect.";
                }
            } else {
                $errors[] = "Veuillez saisir votre mot de passe actuel.";
            }
        }

        // Vérification du nouveau mot de passe
        if ($new_password && (!$confirm_password || $new_password != $confirm_password)) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérification du pseudo
        if ($pseudo) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE pseudo = :pseudo AND id != :user_id");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Ce pseudo est déjà utilisé.";
            }
        }

        if (empty($errors)) {
            // Mise à jour des informations de profil dans la base de données
            $query = "UPDATE users SET nom = :nom, prenom = :prenom, pseudo = :pseudo, sexe = :sexe, email = :email, date = :date, bio = :bio";
            if ($new_password) {
                // Si un nouveau mot de passe a été saisi, le lier à la requête
                $query .= ", password = :password";
            }
            $query .= " WHERE id = :user_id";
            $stmt = $pdo->prepare($query);
            if ($new_password) {
                $stmt->bindParam(':password', $new_password);
            }
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':sexe', $sexe);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':date', $date_de_naissance);
            $stmt->bindParam(':bio', $biography);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        
            // Redirection vers la page de profil
            header('Location: profile.php');
            exit;
        } else {
            // Stockage des erreurs dans une variable de session
            $_SESSION['errors'] = $errors;
            // Redirection vers la page profile_edit.php
            header('Location: profile_edit.php');
            exit;
        }
    }
?>