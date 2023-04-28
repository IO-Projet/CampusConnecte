<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../connexion.php');
        exit;
    }

    // Vérification que le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données du formulaire
        $page_user_id = $_POST['page_user_id'];
        $connected_user_id = $_POST['connected_user_id'];
        $message = $_POST['message'];

        // Enregistrement du commentaire dans la base de données
        $stmt = $pdo->prepare('INSERT INTO profile_commentaires (user_id, user_send, message) VALUES (:user_id, :user_send, :message)');
        $stmt->execute([
            ':user_id' => $page_user_id,
            ':user_send' => $connected_user_id,
            ':message' => $message,
        ]);

        // Récupération du pseudo de l'utilisateur de la page
        $stmt = $pdo->prepare('SELECT pseudo FROM users WHERE id = :id');
        $stmt->execute([':id' => $page_user_id]);
        $page_user_pseudo = $stmt->fetchColumn();

        // Redirection vers la page de profil de l'utilisateur en utilisant son pseudo
        header('Location: ../profile.php?pseudo='.urlencode($page_user_pseudo));
        exit;
    }
?>