<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require 'classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des données du formulaire
    $theme = isset($_POST['theme']) ? $_POST['theme'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $date_fin = $_POST['date_fin'];

    // Validation des données
    if($theme == 0) {
        // Données invalides
        $_SESSION['erreur'] = 1;
        $_SESSION['theme'] = $theme;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        header('Location: ../aides/aides_ajout.php');
        exit;
    } else if($description == '') {
        // Données invalides
        $_SESSION['erreur'] = 2;
        $_SESSION['theme'] = $theme;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        header('Location: ../aides/aides_ajout.php');
        exit;
    }

    // Calcul de la date de début
    $date_debut = date('Y-m-d');

    // Date d'expiration renseignée par l'utilisateur ou dans 1 mois
    if($date_fin == '') {
        $date_fin = date('Y-m-d', strtotime('+1 month'));
    }

    // Vérification de la validité de la date d'expiration
    if(strtotime($date_fin) < time()) {
        // Date d'expiration invalide
        $_SESSION['erreur'] = 3;
        $_SESSION['theme'] = $theme;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        header('Location: ../aides/aides_ajout.php');
        exit;
    }

    // Ajout de l'annonce à la base de données
    $req = $pdo -> prepare("INSERT INTO annonces_aides (auteur, theme, description, date_debut, date_fin) VALUES (:auteur, :theme, :description, :date_debut, :date_fin)");
    $req -> bindParam(':auteur', $user_id);
    $req -> bindParam(':theme', $theme);
    $req -> bindParam(':description', $description);
    $req -> bindParam(':date_debut', $date_debut);
    $req -> bindParam(':date_fin', $date_fin);
    $req -> execute();

    // Redirection vers la page des promotions
    header('Location: ../aides/aides.php');
?>