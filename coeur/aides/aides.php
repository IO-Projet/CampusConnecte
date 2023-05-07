<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseNavigation.php';
    require '../classes/ClasseVerification.php';

    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
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

    // Récupération des thèmes sélectionnés
    $themes_choisis = isset($_GET['themes']) ? $_GET['themes'] : [];

    // Construction de la requête SQL pour récupérer les annonces en fonction des thèmes sélectionnés

    /**
     *  Ce code permet de créer un filtre pour n'afficher que les matières sélectionnées par l'utilisateur.
     *  Ou que les matières sélectionnées par l'utilisateur, qui sont likées si 'favoris' est sélectionné.
     *  De sorte que l'on sorte de la table, seulement les matières que l'utilisateur veut.
     */
    $query = '
        SELECT annonces_aides.*, users.pseudo, matieres.nom 
        FROM annonces_aides 
        JOIN users 
        ON annonces_aides.auteur_aide = users.id 
        JOIN matieres 
        ON annonces_aides.theme = matieres.id
        ';
    if(!empty($themes_choisis) || (isset($_GET['favoris']) && $_GET['favoris'] == 1)) {
        $query .= " WHERE ";
        if(!empty($themes_choisis)) {
            $query .= "matieres.nom IN (";
            foreach($themes_choisis as $theme) {
                // #quote() sert à entourer la chaine de caractere de quote pour être utilisé en tant que requete SQL
                $query .= $pdo -> quote($theme).",";
            }
            // #rtrim(); supprime les espaces de fin de chaîne de caractere
            $query = rtrim($query, ",").")";
        }
        if(isset($_GET['favoris']) && $_GET['favoris'] == 1) {
            if(!empty($themes_choisis)) {
                $query .= " AND ";
            }
            $query .= "annonces_aides.id IN (SELECT annonce_id FROM annonces_aides_likes WHERE user_id = ".$pdo -> quote($user_id).")";
        }
    }

    // Exécution de la requête SQL
    $req = $pdo -> prepare($query);
    $req -> execute();
    $annonces = $req -> fetchAll();

    // Récupération des thèmes uniques et de leur nombre d'occurrences
    $req = $pdo -> prepare('SELECT matieres.nom, COUNT(*) as comptage FROM annonces_aides JOIN matieres ON annonces_aides.theme = matieres.id GROUP BY matieres.nom');
    $req -> execute();
    $themes = $req -> fetchAll();

    // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
    if(isset($_GET['supprimer'])) {
        $annonce_id = $_GET['supprimer'];

        $req = $pdo -> prepare('DELETE FROM annonces_aides WHERE id = :annonce_id');
        $req -> bindParam(':annonce_id', $annonce_id);
        $req -> execute();

        header('Location: aides.php');
        exit;
    }

    if(isset($_POST['like'])) {
        $annonce_id = $_POST['like'];

        // Vérifie si l'utilisateur a déjà liké l'annonce
        $req = $pdo -> prepare('SELECT * FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
        $req -> execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
        $like = $req -> fetch();

        // Si l'utilisateur a déjà liké l'annonce
        if($like) {
            // Suppression du like
            $req = $pdo -> prepare('DELETE FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
            $req -> execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
        } else {
            // Ajout du like
            $req = $pdo -> prepare('INSERT INTO annonces_aides_likes (user_id, annonce_id) VALUES (:user_id, :annonce_id)');
            $req -> execute(['user_id' => $user_id, 'annonce_id' => $annonce_id]);
        }

        header('Location: aides.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Aide</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/aide.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <?php
            $nav = new ClasseNavigation();
            $nav -> sendMenuPlusUn($page_user_pseudo, $user['admin']);
        ?>
        <!-- Liste des thèmes -->
        <form id="filtre-form" method="get">
            <h1>Légende</h1>
            <?php foreach($themes as $theme): ?>
                <label>
                    <input type="checkbox" name="themes[]" value="<?php echo htmlspecialchars($theme['nom']) ?>"
                        <?php if(in_array($theme['nom'], $themes_choisis)): ?>
                            checked
                        <?php endif; ?>
                    >
                    <?php echo htmlspecialchars($theme['nom']) ?> (<?php echo $theme['comptage'] ?>)
                </label><br>
            <?php endforeach; ?>
            <label>
                <input type="checkbox" name="favoris" value="1"
                    <?php if(isset($_GET['favoris'])): ?>
                        checked
                    <?php endif; ?>
                >
                Favoris
            </label><br>
        </form>

        <!-- Liste des annonces -->
        <?php foreach($annonces as $annonce): ?>
            <?php
            // Compte le nombre total de likes pour l'annonce
            $req = $pdo -> prepare('SELECT COUNT(*) FROM annonces_aides_likes WHERE annonce_id = :annonce_id');
            $req -> execute(['annonce_id' => $annonce['id']]);
            $like_comptage = $req -> fetchColumn();

            // Vérifie si l'utilisateur a déjà liké l'annonce
            $req = $pdo -> prepare('SELECT * FROM annonces_aides_likes WHERE user_id = :user_id AND annonce_id = :annonce_id');
            $req -> execute(['user_id' => $user_id, 'annonce_id' => $annonce['id']]);
            $like = $req -> fetch();

            // Définit le texte du bouton en fonction de si l'utilisateur a déjà liké l'annonce ou non
            $bouton_texte = $like ? 'Dislike' : 'Like';
            ?>
            <h2><?php echo htmlspecialchars($annonce['nom']) ?></h2>
            <p>Par : <a href="../profil/profil.php?pseudo=<?= urlencode($annonce['pseudo']) ?>"> <?php echo htmlspecialchars($annonce['pseudo']); ?></a><br>
                Description : <br><?php echo nl2br(htmlspecialchars($annonce['description'])) ?><br>
                Date de création : <?php echo date('d-m-Y', strtotime($annonce['date_debut'])) ?><br>
                Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_fin'])) ?></p>
            <?php if($user_id == $annonce['auteur_aide'] || $user['admin'] == 1): ?>
                Like(s) (<?php echo $like_comptage; ?>)
                <form action="aides.php" method="get">
                    <input type="hidden" name="supprimer" value="<?php echo $annonce['id']; ?>">
                    <input type="submit" value="Supprimer">
                </form>
            <?php else: ?>
                <form method="post">
                    <input type="hidden" name="like" value="<?php echo $annonce['id']; ?>">
                    <button type="submit"><?php echo $bouton_texte; ?> (<?php echo $like_comptage; ?>)</button>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- JavaScript pour soumettre le formulaire automatiquement lorsque l'utilisateur clique sur une case à cocher -->
        <!-- L'URL est donc changé avec des caractères d'échappement pour éviter tout conflit HTML. → %5b = [ | %5D = ] -->
        <script>
            document.querySelectorAll('#filtre-form input[type=checkbox]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    document.querySelector('#filtre-form').submit();
                });
            });
        </script>
    </body>

    <br>
    <a href="aides_gestion.php">Gérer</a>
</html>