<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Vérification si le formulaire a été soumis
    if(isset($_POST['nom']) ||
        isset($_POST['prenom']) ||
        isset($_POST['pseudo']) ||
        isset($_POST['sexe']) ||
        isset($_POST['email']) ||
        isset($_POST['nouveau_mdp']) ||
        isset($_POST['date_de_naissance']) ||
        isset($_POST['biographie'])
    ) {
        // Récupération des données du formulaire
        $nom = isset($_POST['nom']) ? strtolower($_POST['nom']) : null;
        $prenom = isset($_POST['prenom']) ? strtolower($_POST['prenom']) : null;
        $pseudo = isset($_POST['pseudo']) ? strtolower($_POST['pseudo']) : null;
        $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : null;
        $email = isset($_POST['email']) ? strtolower($_POST['email']) : null;
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;
        $nouveau_mdp = isset($_POST['nouveau_mdp']) ? $_POST['nouveau_mdp'] : null;
        $confirm_mdp = isset($_POST['confirm_mdp']) ? $_POST['confirm_mdp'] : null;
        $date_de_naissance = isset($_POST['date_de_naissance']) ? $_POST['date_de_naissance'] : null;
        $biographie = isset($_POST['biographie']) ? $_POST['biographie'] : null;

        // Validation des données
        $erreurs = [];

        // Vérification du mot de passe actuel
        if($nouveau_mdp) {
            if(!$mdp) {
                $erreurs[] = "Veuillez saisir votre mot de passe actuel.";
            }

            $req = $pdo -> prepare('SELECT mdp FROM users WHERE id = :user_id');
            $req -> bindParam(':user_id', $user_id);
            $req -> execute();
            $hash = $req -> fetchColumn();

            if(!password_verify($mdp, $hash)) {
                $erreurs[] = "Le mot de passe actuel est incorrect.";
            }
        }

        // Vérification du nouveau mot de passe
        if ($nouveau_mdp && (!$confirm_mdp || $nouveau_mdp != $confirm_mdp)) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérification du pseudo
        if($pseudo) {
            $req = $pdo -> prepare('SELECT COUNT(*) FROM users WHERE pseudo = :pseudo AND id != :user_id');
            $req -> bindParam(':pseudo', $pseudo);
            $req -> bindParam(':user_id', $user_id);
            $req -> execute();

            if($req -> fetchColumn() > 0) {
                $erreurs[] = "Ce pseudo est déjà utilisé.";
            }
        }

        if(!empty($erreurs)) {
            // Stockage des erreurs dans une variable de session
            $_SESSION['erreurs'] = $erreurs;
            // Redirection vers la page profil_modif.php
            header('Location: ../profil/profil_modif.php');
            exit;
        }

        // Mise à jour des informations de profil dans la base de données
        $query = 'UPDATE users SET nom = :nom, prenom = :prenom, pseudo = :pseudo, sexe = :sexe, email = :email, date = :date, bio = :bio';

        if($nouveau_mdp) {
            // Si un nouveau mot de passe a été saisi, le lier à la requête
            $query .= ", mdp = :mdp";
        }

        $query .= " WHERE id = :user_id";
        $req = $pdo -> prepare($query);

        if($nouveau_mdp) {
            // Hachage du nouveau mot de passe avec BCrypt
            $hash = password_hash($nouveau_mdp, PASSWORD_BCRYPT);
            $req -> bindParam(':mdp', $hash);
        }
        $req -> bindParam(':nom', $nom);
        $req -> bindParam(':prenom', $prenom);
        $req -> bindParam(':pseudo', $pseudo);
        $req -> bindParam(':sexe', $sexe);
        $req -> bindParam(':email', $email);
        $req -> bindParam(':date', $date_de_naissance);
        $req -> bindParam(':bio', $biographie);
        $req -> bindParam(':user_id', $user_id);
        $req -> execute();

        // Redirection vers la page de profil
        header('Location: ../profil/profil.php');
        exit;
    }
?>