<?php
    session_start();

    if (!(isset($_SESSION['user_id']))) {
        header('Location: accueil.php');
        exit;
    }
    
    unset($_SESSION['user_id']);
    header('Location: accueil.php');
?>