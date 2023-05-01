<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    // Vérification si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../connexion.php');
        exit;
    }

    // Récupération des données du formulaire
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    // Préparation de la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO message_admin (author, message) VALUES (:author, :message)");
    if ($stmt->execute([
        ':author' => $user_id,
        ':message' => $message
    ])) {
        // Si l'insertion a réussi, stockage du message de confirmation dans une variable de session
        $_SESSION['confirmation_message'] = "Votre message a été envoyé avec succès.";
    }

    // Redirection vers une page de confirmation
    header('Location: ../contacte.php');
    exit;
?>