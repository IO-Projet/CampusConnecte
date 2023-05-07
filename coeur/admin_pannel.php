<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';
    require 'classes/ClasseVerification.php';
    require 'classes/ClasseNavigation.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("connexion.php");

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des informations de profil de l'utilisateur
    $req = $pdo -> prepare('SELECT * FROM users WHERE id = :user_id');
    $req -> bindParam(':user_id', $user_id);
    $req -> execute();
    $user = $req -> fetch();

    // Récupération des informations de profil de l'utilisateur
    if($user['admin'] != 1) {
        header('Location: aides.php');
        exit;
    }

    // Ajout d'une matière
    if(isset($_POST['nom_matiere'])) {
        $nom_matiere = trim($_POST['nom_matiere']); // Suppression des espaces au début et à la fin
        $nom_matiere = str_replace("\n", "", $nom_matiere); // Suppression des retours à la ligne
        $nom_matiere = str_replace("\r", "", $nom_matiere); // Suppression des retours chariot
        $nom_matiere = substr($nom_matiere, 0, 20); // Limite la longueur à 20 caractères

        if(!empty($nom_matiere)) {
            // Spécification de la colonne 'nom'
            $req = $pdo -> prepare('INSERT INTO matieres (nom) VALUES (:nom)');
            $req -> bindParam(':nom', $nom_matiere);
            $req -> execute();
        }
        header('Location: admin_pannel.php');
        exit;
    }

    // Suppression d'une matière
    if(isset($_GET['supprimer_matiere'])) {
        $id = $_GET['supprimer_matiere'];
        $req = $pdo -> prepare('DELETE FROM matieres WHERE id = :id');
        $req -> bindParam(':id', $id);
        $req -> execute();
        header('Location: admin_pannel.php');
        exit;
    }

    // Suppression d'un commentaire0
    if(isset($_GET['supprimer_commentaire'])) {
        $id = $_GET['supprimer_commentaire'];
        $req = $pdo -> prepare('DELETE FROM message_admin WHERE id = :id');
        $req -> bindParam(':id', $id);
        $req -> execute();
        header('Location: admin_pannel.php');
        exit;
    }

    // Récupération des matières
    $req = $pdo -> query('SELECT * FROM matieres');

    // Retour du résultat sous la forme d'un tableau associatif
    $matieres = $req -> fetchAll(PDO::FETCH_ASSOC);

    // Récupération des commentaires
    $req = $pdo -> query('SELECT message_admin.id, users.pseudo, message_admin.message FROM message_admin INNER JOIN users ON message_admin.auteur = users.id');
    $comments = $req -> fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Admin Pannel</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icones/admin.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <?php
        $nav = new ClasseNavigation();
        $nav -> sendMenu(htmlspecialchars($user['pseudo']), $user['admin']);
        ?>

        <!-- Affichage du tableau -->
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Supprimer</th>
            </tr>
            <?php foreach ($matieres as $matiere): ?>
                <tr>
                    <td><?php echo htmlspecialchars($matiere['id']); ?></td>
                    <td><?php echo htmlspecialchars($matiere['nom']); ?></td>
                    <td><a href="?supprimer_matiere=<?php echo urlencode($matiere['id']); ?>">X</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Affichage de la zone de saisie et du bouton -->
        <form method="post">
            <input type="text" name="nom_matiere">
            <input type="submit" value="Ajouter">
        </form>

        <br>

        <?php foreach ($comments as $comment) : ?>
            <p><a href="profil/profil.php?pseudo=<?php echo urlencode($comment['pseudo']) ?>"> <?php echo htmlspecialchars($comment['pseudo']); ?></a> a écrit : <br> <?php echo htmlspecialchars($comment['message']) ?></p>
            <form action="" method="get">
                <input type="hidden" name="supprimer_commentaire" value="<?php echo $comment['id']; ?>">
                <input type="submit" value="Supprimer"><br>
            </form>
        <?php endforeach; ?>
    </body>
</html>