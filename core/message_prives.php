<html>
    <?php
        session_start();
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

        // Récupération des informations de profil de l'utilisateur
        $pseudo = htmlspecialchars($user['pseudo']);

        // Traitement de la recherche d'un utilisateur
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE pseudo = ?");
            $stmt->execute([$search]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($search == $_SESSION['pseudo']) {
                $error = "Tu te cherche toi même, ce n'est pas possible.";
            } else if ($user) {
                header("Location: message_prives.php?pseudo=".urlencode($user['pseudo']));
                exit;
            } else {
                $error = "Aucun utilisateur trouvé avec le pseudo '$search'.";
            }
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

            header("Location: message_prives.php?pseudo=" . urlencode($pseudo));
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
        <title>MP - <?php echo $pseudo ?></title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <ul>
            <li><a href="profile.php">Profil - <?php echo $pseudo ?> </a></li>
            <li><a href="message_prives.php">Messages privés</a></li>
            <li><a href="tableau_aides.php">Aide</a></li>
            <li><a href="promotions.php">Promotions</a></li>
            <li><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
        <br><br>

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
                    <em>(<?= date('H:i', strtotime($message['date'])) ?>)</em> <?php if ($message['user_push'] == $_SESSION['user_id']): ?><a href="?delete=<?= urlencode($message['id']) ?>&amp;pseudo=<?= urlencode($pseudo) ?>">⬛</a><?php endif; ?>
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