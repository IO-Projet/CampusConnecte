<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupération des données du formulaire
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    // Préparation de la requête d'insertion
    $req = $pdo -> prepare('INSERT INTO message_admin (auteur, message) VALUES (:auteur, :message)');
    if($req -> execute([':auteur' => $user_id, ':message' => $message])) {
        // Si l'insertion a réussi, stockage du message de confirmation dans une variable de session
        $_SESSION['confirm_message'] = "Votre message a été envoyé avec succès.";
    }

    // Redirection vers une page de confirmation
    header('Location: ../contact.php');
    exit;
?>