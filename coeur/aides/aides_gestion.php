<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    // Récupère l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Prépare et exécute la requête SQL pour récupérer les annonces de l'utilisateur connecté
    $req = $pdo -> prepare('SELECT * FROM annonces_aides WHERE auteur_aide = :auteur_aide');
    $req -> bindParam(':auteur_aide', $user_id);
    $req -> execute();
    $annonces = $req -> fetchAll();

    // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
    if(isset($_GET['supprimer'])) {
        $annonce_id = $_GET['supprimer'];

        $req = $pdo -> prepare('DELETE FROM annonces_aides WHERE id = :annonce_id AND auteur_aide = :user_id');
        $req -> execute([':annonce_id' => $annonce_id, ':user_id' => $user_id]);

        header('Location: aides_gestion.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Ajouter une promotion</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/aide.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <a href="aides.php">Retour</a>

        <h1>Gérer mes demandes d'aide</h1>

        <!-- Liste des annonces -->
        <?php
            $i = 1;
            foreach($annonces as $annonce) {
                // Comptage du nombre total de likes pour l'annonce
                $req = $pdo -> prepare('SELECT COUNT(*) FROM annonces_aides_likes WHERE annonce_id = :annonce_id');
                $req -> bindParam(':annonce_id', $annonce['id']);
                $req -> execute();
                $nb_like = $req -> fetchColumn();

                // Récupération du nom de la matière en utilisant l'identifiant de la matière
                $req = $pdo -> prepare('SELECT nom FROM matieres WHERE id = :id');
                $req -> bindParam(':id', $annonce['theme']);
                $req -> execute();
                $matiere = $req -> fetch();

                echo '<h2>Annonce n°'. $i .'</h2>';
                echo '<p>'
                    .'Matière : '. htmlspecialchars($matiere['nom'])
                    // #nl2br() sert à interpréter les chaînes de caractères contenant
                    .'Description : <br>'. nl2br(htmlspecialchars($annonce['description']))
                    .'Date de création : '. date('d-m-Y', strtotime($annonce['date_debut']))
                    .'Date d\'expiration : '. date('d-m-Y', strtotime($annonce['date_fin']))
                    .'</p>';

                if($user_id == $annonce['auteur_aide']) {
                    $urlenc = urlencode($annonce['id']);

                    echo "J'aime (". $nb_like .")";
                    echo '<a href="?supprimer='. $urlenc .'">Supprimer</a>' .'<br>';
                }
                $i++;
            }
        ?>
    </body>

    <br>

    <form action="aides_ajout.php">
        <input type="submit" value="Ajouter une aide" id="add-button">
    </form>

    <!--
        Le nombre d'annonces est limité à 5.
    -->
    <script>
        let annonces = <?php echo json_encode($annonces); ?>;
        if(annonces.length >= 5) {
            // Le bouton n'est plus cliquable
            document.getElementById('add-button').disabled = true;
            document.getElementById('message').textContent = "Vous avez atteint la limite de 5 annonces.";
        } else {
            document.getElementById('add-button').addEventListener('click', function() {
                    window.location.href = 'aides_ajout.php';
            });
        }
    </script>
</html>