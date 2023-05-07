<html>
    <head>
        <title>Admin Pannel</title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/admin.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

        if (!(isset($_SESSION['user_id']))) {
            header('Location: connexion.php');
            exit;
        }

        // Récupération de l'ID de l'utilisateur connecté
        $user_id = $_SESSION['user_id'];

        // Récupération des informations de profil de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user['admin'] != 1) {
            header('Location: aides.php');
            exit;
        }

        // Ajout d'une matière
        if (isset($_POST['nom_matiere'])) {
            $nom_matiere = trim($_POST['nom_matiere']); // Supprime les espaces au début et à la fin
            $nom_matiere = str_replace("\n", "", $nom_matiere); // Supprime les retours à la ligne
            $nom_matiere = str_replace("\r", "", $nom_matiere); // Supprime les retours chariot
            $nom_matiere = substr($nom_matiere, 0, 20); // Limite la longueur à 20 caractères
            if (!empty($nom_matiere)) {
                $stmt = $pdo->prepare("INSERT INTO matieres (nom) VALUES (:nom)");
                $stmt->bindParam(':nom', $nom_matiere);
                $stmt->execute();
            }
            header('Location: adminpannel.php');
            exit;
        }
    
        // Suppression d'une matière
        if (isset($_GET['supprimermatiere'])) {
            $id = $_GET['supprimermatiere'];
            $stmt = $pdo->prepare("DELETE FROM matieres WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('Location: adminpannel.php');
            exit;
        }

        // Suppression d'un commentaire
        if (isset($_GET['supprimercommentaire'])) {
            $id = $_GET['supprimercommentaire'];
            $stmt = $pdo->prepare("DELETE FROM message_admin WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('Location: adminpannel.php');
            exit;
        }
    
        // Récupération des matières
        $stmt = $pdo->query("SELECT * FROM matieres");
        $matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupération des commentaires
        $query = $pdo->query('SELECT message_admin.id, users.pseudo, message_admin.message FROM message_admin INNER JOIN users ON message_admin.author = users.id');
        $comments = $query->fetchAll();
    ?>

    <body>
        <!-- Menu -->
        <a href="profile.php" title="Profil - <?php echo htmlspecialchars($user['pseudo']) ?>"><img src="../icons/user.png" alt="Profil - <?php echo htmlspecialchars($user['pseudo']) ?>" width="32" height="32"></a><br>
        <a href="message_prives.php" title="Messages privés"><img src="../icons/message.png" alt="Messages privés" width="32" height="32"></a><br>
        <a href="aides.php" title="Aide"><img src="../icons/help.png" alt="Aide" width="32" height="32"></a><br>
        <a href="promotions.php" title="Promotions"><img src="../icons/promotion.png" alt="Promotions" width="32" height="32"></a><br>
        <?php if ($user['admin'] == 1) : ?>
            <a href="adminpannel.php" title="Panneau d'administration"><img src="../icons/admin.png" alt="Panneau d\'administration" width="32" height="32"></a><br>
        <?php else : ?>
            <a href="contacte.php" title="Contacte Administrateurs"><img src="../icons/contact.png" alt="Contacte Administrateurs" width="32" height="32"></a><br>
        <?php endif; ?>
        <a href="deconnexion.php" title="Déconnexion"><img src="../icons/logout.png" alt="Déconnexion" width="32" height="32"></a><br>

        <br>

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
                    <td><a href="?supprimermatiere=<?php echo urlencode($matiere['id']); ?>">X</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Affichage de la zone de saisie et du bouton -->
        <form method="post">
            <input type="text" name="nom_matiere">
            <input type="submit" value="AJOUTER">
        </form>

        <br>

        <?php foreach ($comments as $comment) : ?>
            <p><a href="profile.php?pseudo=<?= urlencode($comment['pseudo']) ?>"> <?php echo htmlspecialchars($comment['pseudo']); ?></a> a écrit : <br> <?php echo htmlspecialchars($comment['message']) ?></p>
            <form action="" method="get">
                <input type="hidden" name="supprimercommentaire" value="<?php echo $comment['id']; ?>">
                <input type="submit" value="SUPPRIMER"><br>
            </form>
        <?php endforeach; ?>
    </body>
</html>