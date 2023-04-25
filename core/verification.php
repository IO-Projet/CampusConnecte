<?php
    session_start();

    if(isset($_SESSION['user_id'])) {
        // Utilisateur connecté
        echo "Tu es connecté";
    } else {
        // Utilisateur non connecté
        header('Location: connexion.php');
    }
?>