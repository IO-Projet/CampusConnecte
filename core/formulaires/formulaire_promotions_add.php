<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';

    if (!(isset($_SESSION['user_id']))) {
        header('Location: connexion.php');
        exit;
    }

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des données du formulaire
    $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $date_end = $_POST['date_end'];
    $filtre = $_POST['filtre'];

    // Validation des données
    if ($titre == '') {
        // Données invalides
        $_SESSION['erreur'] = 1;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_end'] = $date_end;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions_add.php');
        exit;
    } else if ($description == '') {
        // Données invalides
        $_SESSION['erreur'] = 2;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_end'] = $date_end;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions_add.php');
        exit;
    }

    // Calcul de la date de début
    $date_start = date('Y-m-d');

    // Calcul de la date d'expiration en fonction du type d'annonce
    if ($filtre == 'D') {
        // Annonce durable : date d'expiration dans 1 an
        $date_end = date('Y-m-d', strtotime('+1 year'));
    } else {
        // Annonce éphémère : date d'expiration renseignée par l'utilisateur ou dans 1 mois
        if ($date_end == '') {
            $date_end = date('Y-m-d', strtotime('+1 month'));
        }
    }

    // Vérification de la validité de la date d'expiration
    if (strtotime($date_end) < time()) {
        // Date d'expiration invalide
        $_SESSION['erreur'] = 3;
        $_SESSION['titre'] = $titre;
        $_SESSION['description'] = $description;
        $_SESSION['date_end'] = $date_end;
        $_SESSION['filtre'] = $filtre;
        header('Location: ../promotions_add.php');
        exit;
    }

    // Ajout de l'annonce à la base de données
    $stmt = $pdo->prepare("INSERT INTO annonces_promotions (author, titre, description, date_start, date_end, filtre) VALUES (:author, :titre, :description, :date_start, :date_end, :filtre)");
    $stmt->bindParam(':author', $user_id);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);
    $stmt->bindParam(':filtre', $filtre);
    $stmt->execute();

    // Redirection vers la page des promotions
    header('Location: ../promotions.php');
?>