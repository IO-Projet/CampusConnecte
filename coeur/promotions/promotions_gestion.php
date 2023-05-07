<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des informations de profil de l'utilisateur
    $req = $pdo -> prepare('SELECT * FROM users WHERE id = :user_id');
    $req -> bindParam(':user_id', $user_id);
    $req -> execute();
    $user = $req -> fetch();

    // Prépare et exécute la requête SQL pour récupérer les annonces de l'utilisateur connecté
    $req = $pdo -> prepare('SELECT * FROM annonces_promotions WHERE auteur_promotion = :auteur_promotion');
    $req -> execute(['auteur_promotion' => $user_id]);
    $annonces = $req -> fetchAll();

    // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
    if(isset($_GET['supprimer'])) {
        $annonce_id = $_GET['supprimer'];

        $req = $pdo -> prepare("DELETE FROM annonces_promotions WHERE id = :annonce_id AND author = :user_id");
        $req -> bindParam(':annonce_id', $annonce_id);
        $req -> bindParam(':user_id', $user_id);
        $req -> execute();

        header('Location: promotions_gestion.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Ajouter une promotion</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/promotion.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <a href="promotions.php">Retour</a>

        <h1>Gérer mes annonces de Promotion</h1>

        <!-- Liste des annonces -->
        <?php
            $i = 1;
            foreach($annonces as $annonce) {
                // Affichage des annonces
                echo '<h2>Annonce n°'. $i .' : '. htmlspecialchars($annonce['titre']) .'</h2>';
                echo '<p>'
                    .'Description : <br>'. htmlspecialchars($annonce['description'])
                    .'Posté le : '. date('d-m-Y', strtotime($annonce['date_debut']))
                    .'</p>';

                if($annonce['filtre'] == 'E') {
                    echo "Date d'expiration : ". date('d-m-Y', strtotime($annonce['date_fin']));
                }

                if($user_id == $annonce['auteur_promotion']) {
                    echo '<a href="?supprimer='. urlencode($annonce['id']) .'">Supprimer</a>'.'<br>';
                }
                $i++;
            }
        ?>
    </body>

    <form action="promotions_ajout.php">
        <input type="submit" value="Ajouter une annonce">
    </form>
</html>