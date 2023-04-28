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
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil - <?php echo isset($connected_user['pseudo']) ? $connected_user['pseudo'] : $pseudo ?> </a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>

        <h1><?php echo $pseudo ?></h1>
        <p>Nom: <?php echo $nom ?><br>
            Prénom: <?php echo $prenom ?><br>
            Date de naissance: <?php echo $date_de_naissance ?></p>
        <p><?php echo $biography ?></p>


        <?php if ($show_edit_button): ?>
            <a href="profile_edit.php">EDIT</a>
        <?php endif; ?>
        <?php if ($show_contact_button): ?>
            <a href="message_prives.php?pseudo=<?= $pseudo ?>">CONTACTER</a><br>

            <form action="formulaires/formulaire_profile_commentaires.php" method="post">
                <br>
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