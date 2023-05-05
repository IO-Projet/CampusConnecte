<html>
    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

        if (!(isset($_SESSION['user_id']))) {
            header('Location: connexion.php');
            exit;
        }

        $show_edit_button = false;
        $show_contact_button = false;

        if (isset($_GET['pseudo'])) {
            $pseudo = $_GET['pseudo'];
            $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = :pseudo");
            $stmt->execute([':pseudo' => $pseudo]);
            $user_id = $stmt->fetchColumn();
            if ($user_id === false) {
                // Si l'utilisateur n'existe pas, afficher une page d'erreur 404
                header("HTTP/1.0 404 Not Found");
                echo "Error 404";
                exit;
            }

            $stmt = $pdo->prepare("SELECT id, pseudo FROM users WHERE id = :id");
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $connected_user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($_SESSION['user_id'] == $user_id) {
                $show_edit_button = true;
            } else {
                $show_contact_button = true;
            }
        } else {
            $show_edit_button = true;
            $user_id = $_SESSION['user_id'];
        }

        if (isset($user_id)) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute([':id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $nom = isset($user['nom']) ? htmlspecialchars($user['nom']) == "" ? 'N.A.' : ucfirst(htmlspecialchars($user['nom'])) : 'N.A.';
        $prenom = isset($user['prenom']) ? htmlspecialchars($user['prenom']) == "" ? 'N.A.' : ucfirst(htmlspecialchars($user['prenom'])) : 'N.A.';
        $pseudo = htmlspecialchars($user['pseudo']);
        if ($user['date'] == '0000-00-00' || $user['date'] == NULL) {
            $date_de_naissance = 'N.A.';
        } else {
            $date = date_create($user['date']);
            $date_de_naissance = $date->format('d-m-Y');
        }
        if (!empty($user['bio'])) {
            $biography = "Biographie: <br>".htmlspecialchars($user['bio']);
        } else {
            $biography = "";
        }

        // Récupération des commentaires de la table 'profile_commentaires'
        $stmt = $pdo->prepare('SELECT profile_commentaires.message, users.pseudo FROM profile_commentaires INNER JOIN users ON profile_commentaires.user_send = users.id WHERE profile_commentaires.user_id = :user_id');
        $stmt->execute([':user_id' => $user['id']]);
        $comments = $stmt->fetchAll();
    ?>

    <head>
        <title>Profile - <?php echo $pseudo ?></title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/user.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <a href="profile.php" title="Profil - <?php echo isset($connected_user['pseudo']) ? $connected_user['pseudo'] : $pseudo ?>"><img src="../icons/user.png" alt="Profil - <?php echo isset($connected_user['pseudo']) ? $connected_user['pseudo'] : $pseudo ?>" width="32" height="32"></a><br>
        <a href="message_prives.php" title="Messages privés"><img src="../icons/message.png" alt="Messages privés" width="32" height="32"></a><br>
        <a href="aides.php" title="Aide"><img src="../icons/help.png" alt="Aide" width="32" height="32"></a><br>
        <a href="promotions.php" title="Promotions"><img src="../icons/promotion.png" alt="Promotions" width="32" height="32"></a><br>
        <?php if ($user['admin'] == 1) : ?>
            <a href="adminpannel.php" title="Panneau d'administration"><img src="../icons/admin.png" alt="Panneau d\'administration" width="32" height="32"></a><br>
        <?php else : ?>
            <a href="contacte.php" title="Contacte Administrateurs"><img src="../icons/contact.png" alt="Contacte Administrateurs" width="32" height="32"></a><br>
        <?php endif; ?>
        <a href="deconnexion.php" title="Déconnexion"><img src="../icons/logout.png" alt="Déconnexion" width="32" height="32"></a><br>

        <h1><?php echo $pseudo ?></h1>
        <p>Nom: <?php echo $nom ?><br>
            Prénom: <?php echo $prenom ?><br>
            Date de naissance: <?php echo $date_de_naissance ?></p>
        <p><?php echo $biography ?></p>

        <?php if ($show_edit_button): ?>
            <form action="profile_edit.php">
                <input type="submit" value="EDIT">
            </form>
        <?php endif; ?>

        <?php if ($show_contact_button): ?>
            <a href="message_prives.php?pseudo=<?= $pseudo ?>">CONTACTER</a><br>

            <form action="formulaires/formulaire_profile_commentaires.php" method="post">
                <br>
                <h2>Ajouter un commantaire</h2>
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <input type="hidden" name="connected_user_id" value="<?php echo $connected_user['id']; ?>">
                <textarea name="message" id="message" maxlength="2000"></textarea><br>
                <input type="submit" value="Envoyer">
            </form>
        <?php endif; ?>

        <h2>Commentaires</h2>
        <?php foreach ($comments as $comment): ?>
            <p><?php echo $comment['pseudo']; ?> à écrit : <br>
                <?php echo $comment['message']; ?></p>
        <?php endforeach; ?>
    </body>
</html>