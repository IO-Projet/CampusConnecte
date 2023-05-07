<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../config.php';
    require 'classes/ClasseVerification.php';
    require 'classes/ClasseNavigation.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("connexion.php");

    // Récupération des informations de l'utilisateur connecté
    $req = $pdo -> prepare('SELECT * FROM users WHERE id = :id');
    $req -> execute([':id' => $_SESSION['user_id']]);
    $user_connecte = $req -> fetch();

    // Vérification de la présence du paramètre search dans l'URL
    if(isset($_GET['recherche'])) {
        // Récupération des informations de l'utilisateur recherché
        $req = $pdo -> prepare('SELECT * FROM users WHERE pseudo = :pseudo');
        $req -> execute([':pseudo' => $_GET['recherche']]);
        $user = $req -> fetch();
    
        // Vérification si l'utilisateur recherché existe
        if($user) {
            // Vérification si l'utilisateur recherché est l'utilisateur connecté
            if($user['id'] == $user_connecte['id']) {
                $erreur = "Vous ne pouvez pas envoyer un message à vous-même.";
            } else {
                // Redirection vers la page message_prives.php avec le pseudo de l'utilisateur recherché en paramètre
                header("Location: message_prives.php?pseudo=".urlencode($user['pseudo']));
                exit;
            }
        } else {
            // l'utilisatur rechercher n'existe pas
            $erreur = "L'utilisateur ". $_GET['recherche'] ." n'existe pas.";
            $user = $user_connecte;
        }
    } else {
        // Affectation des informations de l'utilisateur connecté à la variable $user
        $user = $user_connecte;
    }

    // Traitement de la suppression d'un message
    if(isset($_GET['supprimer'])) {
        $message_id = $_GET['supprimer'];
        $req = $pdo -> prepare('DELETE FROM message_prives WHERE id = :id AND expediteur = :expediteur');
        $req -> execute([':id' => $message_id, ':expediteur' => $_SESSION['user_id']]);
    }

    // Traitement de l'envoi du formulaire
    if(isset($_POST['message']) && isset($_POST['pseudo'])) {
        // #rtrim() Suppression des espaces de fin de string
        $message = rtrim($_POST['message']);
        $pseudo = $_POST['pseudo'];
        $expediteur = $_SESSION['user_id'];

        // Récupération de l'ID de l'utilisateur destinataire à partir de son pseudo
        $req = $pdo -> prepare('SELECT id FROM users WHERE pseudo = :pseudo');
        $req -> execute([':pseudo' => $pseudo]);
        $destinataire = $req -> fetchColumn();

        if($destinataire && strlen($message) <= 2000) {
            // MySQL méthode #NOW() -> 'YYYY-MM-DD HH:MM:SS'
            $req = $pdo -> prepare('INSERT INTO message_prives (expediteur, destinataire, message, date) VALUES (:expediteur, :destinataire, :message, NOW())');
            $req -> execute([':expediteur' => $expediteur, ':destinataire' => $destinataire, ':message' => $message]);
        }

        header("Location: message_prives.php?pseudo=". urlencode($pseudo));
        exit;
    }

    // Récupération de la liste des utilisateurs avec lesquels l'utilisateur connecté a déjà eu une conversation à partir de la base de données
    $query = "
            SELECT DISTINCT users.* 
            FROM users 
            JOIN message_prives 
            ON (users.id = message_prives.expediteur OR users.id = message_prives.destinataire) 
            WHERE (message_prives.expediteur = ? OR message_prives.destinataire = ?) 
            AND users.id != ?
        ";
    $req = $pdo -> prepare($query);
    $req -> execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
    /**
     * PDO::FETCH_ASSOC spécifie que la méthode #fetch doit retourner chaque ligne sous forme de tableau indexé par le nom de la colonne telle qu'elle est retournée dans l'ensemble des résultats.
     * Si cet ensemble contient plusieurs colonnes portant le même nom, PDO::FETCH_ASSOC ne renvoie qu'une seule valeur par nom de colonne.
     */
    $users = $req -> fetchAll(PDO::FETCH_ASSOC);

    // Récupération des messages entre l'utilisateur connecté et l'utilisateur sélectionné à partir de la base de données
    if(isset($_GET['pseudo'])) {
        $pseudo = $_GET['pseudo'];
        // Récupération de l'ID de l'utilisateur destinataire à partir de son pseudo
        $req = $pdo -> prepare('SELECT id FROM users WHERE pseudo = :pseudo');
        $req -> execute([':pseudo' => $pseudo]);
        $user_selectionne = $req -> fetchColumn();

        if($user_selectionne) {
            // Récupération des messages entre les deux utilisateurs
            $query = "
                    SELECT message_prives.*, users.pseudo 
                    FROM message_prives 
                    JOIN users 
                    ON message_prives.expediteur = users.id 
                    WHERE (expediteur = ? AND destinataire = ?) 
                    OR (expediteur = ? AND destinataire = ?)
                    ORDER BY message_prives.date ASC
                ";
            $req = $pdo -> prepare($query);
            $req -> execute([$_SESSION['user_id'], $user_selectionne, $user_selectionne, $_SESSION['user_id']]);
            $messages = $req -> fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Aucun utilisateur trouvé avec ce pseudo
            header("Location: message_prives.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>MP<?php echo isset($user['pseudo']) ? ' - '. $user['pseudo'] : '' ?></title>
        <meta charset="utf-8">
        <link rel="icon" href="../icons/message.png" type="image/png">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <!-- Menu -->
        <?php
        $nav = new ClasseNavigation();
        $nav -> sendMenu($user_connecte['pseudo'], $user['admin']);
        ?>
        <h1>Messages privés</h1>

        <?php if(!isset($user_selectionne)): ?>
            <!-- Formulaire pour rechercher un utilisateur par son pseudo -->
            <form method="get">
                <label for="recherche">Rechercher un utilisateur</label>
                <input type="text" name="recherche" id="recherche">
                <button type="submit">Rechercher</button>
            </form>

            <!-- Affichage d'un message d'erreur si aucun utilisateur n'a été trouvé lors de la recherche -->
            <?php if (isset($erreur)): ?>
                <p><?php echo htmlspecialchars($erreur) ?></p>
            <?php endif; ?>

            <!-- Menu avec la liste des utilisateurs avec lesquels l'utilisateur connecté a déjà eu une conversation -->
            <h2>Utilisateurs</h2>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><a href="?pseudo=<?php echo urlencode($user['pseudo']) ?>"><?php echo htmlspecialchars($user['pseudo']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <!-- Lien pour revenir en arrière -->
            <p><a href="message_prives.php">Retour</a></p>

            <!-- Affichage des messages entre l'utilisateur connecté et l'utilisateur sélectionné -->
            <h2>Messages</h2>
            <?php foreach ($messages as $message): ?>
                <div>
                    <?php echo htmlspecialchars($message['pseudo']) .':'. nl2br(htmlspecialchars($message['message'])) ?><br>
                    <em>
                        (<?= date('H:i', strtotime($message['date'])) ?>)
                    </em>
                    <?php if($message['expediteur'] == $_SESSION['user_id']): ?>
                        <!--
                            &amp; est la conversion de '&' du html au php
                        -->
                        <a href="?supprimer=<?= urlencode($message['id']) ?>&amp;pseudo=<?= urlencode($pseudo) ?>">🗑️</a>
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