<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseNavigation.php';
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

    // Récupération des informations de profil de l'utilisateur
    $page_user_pseudo = htmlspecialchars($user['pseudo']);

    // Récupération des paramètres de recherche et de filtre
    $recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
    $filtre = isset($_GET['filtre']) ? $_GET['filtre'] : '';

    // Préparation de la requête SQL pour récupérer les annonces
    $sql = "
            SELECT annonces_promotions.*, users.pseudo 
            FROM annonces_promotions 
            JOIN users 
            ON annonces_promotions.auteur_promotion = users.id 
            WHERE 1=1
        ";

    if($recherche != '') {
        $sql .= " AND (annonces_promotions.titre LIKE :recherche OR annonces_promotions.description LIKE :recherche)";
    }

    if($filtre != '') {
        $sql .= " AND annonces_promotions.filtre LIKE :filtre";
    }
    $req = $pdo -> prepare($sql);

    if($recherche != '') {
        $req -> bindValue(':supprimer', '%'. $recherche .'%');
    }

    if($filtre != '') {
        $req -> bindParam(':filtre', $filtre);
    }
    $req -> execute();
    $annonces = $req -> fetchAll();

    // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
    if(isset($_GET['supprimer'])) {
        $annonce_id = $_GET['supprimer'];

        if($user['admin'] == 1) {
            $req = $pdo -> prepare('DELETE FROM annonces_promotions WHERE id = :annonce_id');
            $req -> bindParam(':annonce_id', $annonce_id);
        } else {
            $req = $pdo -> prepare('DELETE FROM annonces_promotions WHERE id = :annonce_id AND auteur_promotion = :user_id');
            $req -> bindParam(':annonce_id', $annonce_id);
            $req -> bindParam(':user_id', $user_id);
        }
        $req -> execute();

        header('Location: promotions.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Promotions</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/promotion.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <?php
            $nav = new ClasseNavigation();
            $nav -> sendMenuPlusUn($page_user_pseudo, $user['admin']);
        ?>
        <!-- Formulaire de recherche -->
        <form method="get" action="promotions.php">
            <br>
            <input type="text" name="recherche" value="<?php echo htmlspecialchars($recherche); ?>">
            <select name="filtre">
                <option value="">Aucun</option>
                <option value="E" <?php if ($filtre == 'E') echo 'sélectionné'; ?>>Ephémère</option>
                <option value="D" <?php if ($filtre == 'D') echo 'sélectionné'; ?>>Durable</option>
            </select>
            <input type="submit" value="Rechercher">
        </form>

        <!-- Affichage des annonces -->
        <?php foreach ($annonces as $annonce): ?>
            <h2><?php echo htmlspecialchars($annonce['titre']); ?></h2>
            Par : <a href="../profil/profil.php?pseudo=<?= urlencode($annonce['pseudo']) ?>"> <?php echo htmlspecialchars($annonce['pseudo']); ?></a><br>
            Description : <br><?php echo htmlspecialchars($annonce['description']); ?>
            Posté le : <?php echo date('d-m-Y', strtotime($annonce['date_start'])); ?><br>
            <?php
                if($annonce['filtre'] == 'E') {
                    echo "Date d'expiration : ". date('d-m-Y', strtotime($annonce['date_fin']));
                }
            ?>
            <?php if($user_id == $annonce['auteur_promotion']): ?>
                <form action="?supprimer=<?php echo urlencode($annonce['id']) ?>">
                    <input type="submit" value="Supprimer"><br>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>

        <script>
            // Au changement du filtre,
            document.querySelector('select[name="filtre"]').onchange = function() {
                // Rechercher le form qui est dans le fichier courant
                this.form.submit();
            };
        </script>
    </body>

    <br>

    <a href="promotions_gestion.php">Gérer</a>
</html>