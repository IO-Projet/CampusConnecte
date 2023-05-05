<html>
    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

        if (!(isset($_SESSION['user_id']))) {
            header('Location: connexion.php');
            exit;
        }

        // Récupération des informations de l'utilisateur connecté
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $connected_user = $stmt->fetch();

        // Vérification de la présence du paramètre search dans l'URL
        if (isset($_GET['search'])) {
            // Récupération des informations de l'utilisateur recherché
            $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = :pseudo");
            $stmt->execute(['pseudo' => $_GET['search']]);
            $user = $stmt->fetch();

            // Vérification si l'utilisateur recherché existe
            if ($user) {
                // Vérification si l'utilisateur recherché est l'utilisateur connecté
                if ($user['id'] == $connected_user['id']) {
                    $error = "Vous ne pouvez pas vous envoyer un message à vous-même.";
                } else {
                    // Redirection vers la page message_prives.php avec le pseudo de l'utilisateur recherché en paramètre
                    header("Location: message_prives.php?pseudo=".urlencode($user['pseudo']));
                    exit;
                }
            } else {
                // l'utilisatur rechercher n'existe pas
                $error = "L'utilisateury '".$_GET['search']."' n'existe pas.";
                $user = $connected_user;
            }
        } else {
            // Affectation des informations de l'utilisateur connecté à la variable $user
            $user = $connected_user;
        }

        // Traitement de la suppression d'un message
        if (isset($_GET['delete'])) {
            $message_id = $_GET['delete'];
            $stmt = $pdo->prepare("DELETE FROM message_prives WHERE id = ? AND user_push = ?");
            $stmt->execute([$message_id, $_SESSION['user_id']]);
        }

        // Traitement de l'envoi du formulaire
        if (isset($_POST['message']) && isset($_POST['pseudo'])) {
            $message = rtrim($_POST['message']);
            $pseudo = $_POST['pseudo'];
            $user_push = $_SESSION['user_id'];

            // Récupération de l'ID de l'utilisateur destinataire à partir de son pseudo
            $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
            $stmt->execute([$pseudo]);
            $user_pull = $stmt->fetchColumn();

            if ($user_pull && strlen($message) <= 2000) {
                $stmt = $pdo->prepare("INSERT INTO message_prives (user_push, user_pull, message, date) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$user_push, $user_pull, $message]);
            }

            header("Location: message_prives.php?pseudo=".urlencode($pseudo));
            exit;
        }

        // Récupération de la liste des utilisateurs avec lesquels l'utilisateur connecté a déjà eu une conversation à partir de la base de données
        $stmt = $pdo->prepare("SELECT DISTINCT users.* FROM users JOIN message_prives ON (users.id = message_prives.user_push OR users.id = message_prives.user_pull) WHERE (message_prives.user_push = ? OR message_prives.user_pull = ?) AND users.id != ?");
        $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupération des messages entre l'utilisateur connecté et l'utilisateur sélectionné à partir de la base de données
        if (isset($_GET['pseudo'])) {
            $pseudo = $_GET['pseudo'];
            // Récupération de l'ID de l'utilisateur destinataire à partir de son pseudo
            $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
            $stmt->execute([$pseudo]);
            $selected_user = $stmt->fetchColumn();

            if ($selected_user) {
                // Récupération des messages entre les deux utilisateurs
                $stmt = $pdo->prepare("SELECT message_prives.*, users.pseudo FROM message_prives JOIN users ON message_prives.user_push = users.id WHERE (user_push = ? AND user_pull = ?) OR (user_push = ? AND user_pull = ?) ORDER BY message_prives.date ASC");
                $stmt->execute([$_SESSION['user_id'], $selected_user, $selected_user, $_SESSION['user_id']]);
                $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Aucun utilisateur trouvé avec ce pseudo
                header("Location: message_prives.php");
                exit;
            }
        }
    ?>

    <head>
        <title>MP<?php echo isset($user['pseudo']) ? ' - '.$user['pseudo'] : '' ?></title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/message.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <a href="profile.php" title="Profil - <?php echo $connected_user['pseudo'] ?>"><img src="../icons/user.png" alt="Profil - <?php echo $connected_user['pseudo'] ?>" width="32" height="32"></a><br>
        <a href="message_prives.php" title="Messages privés"><img src="../icons/message.png" alt="Messages privés" width="32" height="32"></a><br>
        <a href="aides.php" title="Aide"><img src="../icons/help.png" alt="Aide" width="32" height="32"></a><br>
        <a href="promotions.php" title="Promotions"><img src="../icons/promotion.png" alt="Promotions" width="32" height="32"></a><br>
        <?php if ($user['admin'] == 1) : ?>
            <a href="adminpannel.php" title="Panneau d'administration"><img src="../icons/admin.png" alt="Panneau d\'administration" width="32" height="32"></a><br>
        <?php else : ?>
            <a href="contacte.php" title="Contacte Administrateurs"><img src="../icons/contact.png" alt="Contacte Administrateurs" width="32" height="32"></a><br>
        <?php endif; ?>
        <a href="deconnexion.php" title="Déconnexion"><img src="../icons/logout.png" alt="Déconnexion" width="32" height="32"></a><br>

        <h1>Messages privés</h1>

        <?php if (!isset($selected_user)): ?>
            <!-- Formulaire pour rechercher un utilisateur par son pseudo -->
            <form method="get">
                <label for="search">Rechercher un utilisateur:</label>
                <input type="text" name="search" id="search">
                <button type="submit">Rechercher</button>
            </form>

            <!-- Affichage d'un message d'erreur si aucun utilisateur n'a été trouvé lors de la recherche -->
            <?php if (isset($error)): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- Menu avec la liste des utilisateurs avec lesquels l'utilisateur connecté a déjà eu une conversation -->
            <h2>Utilisateurs</h2>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><a href="?pseudo=<?= urlencode($user['pseudo']) ?>"><?= htmlspecialchars($user['pseudo']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <!-- Lien pour revenir en arrière -->
            <p><a href="message_prives.php">RETOUR</a></p>

            <!-- Affichage des messages entre l'utilisateur connecté et l'utilisateur sélectionné -->
            <h2>Messages</h2>
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message['user_push'] == $_SESSION['user_id'] ? 'mine' : 'theirs' ?>">
                    <?= htmlspecialchars($message['pseudo']) ?> : <?= nl2br(htmlspecialchars($message['message'])) ?><br>
                    <em>(<?= date('H:i', strtotime($message['date'])) ?>)</em>
                    <?php if ($message['user_push'] == $_SESSION['user_id']): ?>
                        <a href="?delete=<?= urlencode($message['id']) ?>&amp;pseudo=<?= urlencode($pseudo) ?>">⬛</a>
                    <?php endif; ?>
                </div>
                <br>
            <?php endforeach; ?>
            <br>

            <!-- Formulaire pour envoyer un nouveau message à l'utilisateur sélectionné -->
            <form method="post">
                <input type="hidden" name="pseudo" value="<?= htmlspecialchars($pseudo) ?>">
                <textarea name="message"></textarea><br>
                <button type="submit">Envoyer</button>
            </form>
        <?php endif; ?>
    </body>
</html>