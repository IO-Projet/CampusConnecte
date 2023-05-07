<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des données du formulaire
    $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $date_fin = $_POST['date_fin'];
    $filtre = isset($_POST['titre']) ? $_POST['filtre'] : '';

    // Validation des données
    if($titre == '') {
        // Données invalides
        $_SESSION['erreur'] = 1;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions/promotions_ajout.php');
        exit;
    } else if($description == '') {
        // Données invalides
        $_SESSION['erreur'] = 2;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions/promotions_ajout.php');
        exit;
    } else if($filtre == '') {
        // Données invalides
        $_SESSION['erreur'] = 3;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions/promotions_ajout.php');
        exit;
    }

    // Calcul de la date de début
    $date_debut = date('Y-m-d');

    // Calcul de la date d'expiration en fonction du type d'annonce
    if($filtre == 'D') {
        // Annonce durable : date d'expiration dans 1 an
        $date_fin = date('Y-m-d', strtotime('+1 year'));
    } else {
        // Annonce éphémère : date d'expiration renseignée par l'utilisateur ou dans 1 mois
        if($date_fin == '') {
            $date_fin = date('Y-m-d', strtotime('+1 month'));
        }
    }

    // Vérification de la validité de la date d'expiration
    if(strtotime($date_fin) < time()) {
        // Date d'expiration invalide
        $_SESSION['erreur'] = 4;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_fin'] = $date_fin;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions/promotions_ajout.php');
        exit;
    }

    // Ajout de l'annonce à la base de données
    $req = $pdo -> prepare('INSERT INTO annonces_promotions (auteur_promotion, titre, description, date_debut, date_fin, filtre) VALUES (:auteur_promotion, :titre, :description, :date_debut, :date_fin, :filtre)');
    $req -> bindParam(':auteur_promotion', $user_id);
    $req -> bindParam(':titre', $titre);
    $req -> bindParam(':description', $description);
    $req -> bindParam(':date_debut', $date_debut);
    $req -> bindParam(':date_fin', $date_fin);
    $req -> bindParam(':filtre', $filtre);
    $req -> execute();

    // Redirection vers la page des promotions
    header('Location: ../promotions/promotions_gestion.php');
?>