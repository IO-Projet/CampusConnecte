<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Vérification que le formulaire a été soumis
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $user_id = $_POST['user_id'];
        $user_connecte = $_POST['user_id_connecte'];
        $message = $_POST['message'];

        // Enregistrement du commentaire dans la base de données
        $req = $pdo -> prepare('INSERT INTO profil_commentaires (user_id, expediteur, message) VALUES (:user_id, :expediteur, :message)');
        $req -> execute([':user_id' => $user_id, ':expediteur' => $user_connecte, ':message' => $message]);

        // Récupération du pseudo de l'utilisateur de la page
        $req = $pdo -> prepare('SELECT pseudo FROM users WHERE id = :id');
        $req -> execute([':id' => $user_id]);
        $page_user_pseudo = $req -> fetchColumn();

        // Redirection vers la page de profil de l'utilisateur en utilisant son pseudo
        header('Location: ../profil/profil.php?pseudo='. urlencode($page_user_pseudo));
        exit;
    }
?>