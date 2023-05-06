<html>
<head>
    <title>Ajouter une promotion</title>
    <meta charset="utf-8">
    <link rel="icon" href="../icons/help.png" type="image/png">
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

    // Récupère l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Prépare et exécute la requête SQL pour récupérer les annonces de l'utilisateur connecté
    $stmt = $pdo->prepare('SELECT * FROM annonces_aides WHERE author = :author');
    $stmt->execute(['author' => $user_id]);
    $annonces = $stmt->fetchAll();

    // Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
    if (isset($_GET['delete'])) {
        $annonce_id = $_GET['delete'];

        $stmt = $pdo->prepare("DELETE FROM annonces_aides WHERE id = :annonce_id AND author = :user_id");
        $stmt->bindParam(':annonce_id', $annonce_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        header('Location: aides_gerer.php');
        exit;
    }
?>
 
<body>
<a href="aides.php">Retour</a>

<h1>Gérer mes demandes d'aide</h1>

<!-- Liste des annonces -->
<?php
    $i = 1;
    foreach ($annonces as $annonce) {
        // Compte le nombre total de likes pour l'annonce
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM annonces_aides_likes WHERE annonce_id = :annonce_id');
        $stmt->execute(['annonce_id' => $annonce['id']]);
        $like_count = $stmt->fetchColumn();

        // Prépare et exécute la requête SQL pour récupérer le nom de la matière en utilisant l'identifiant de la matière stocké dans l'annonce
        $stmt = $pdo->prepare('SELECT nom FROM matieres WHERE id = :id');
        $stmt->execute(['id' => $annonce['theme']]);
        $matiere = $stmt->fetch();
        ?>
        <h2>Annonce <?php echo $i; ?></h2>
        <p>Matière : <?php echo htmlspecialchars($matiere['nom']) ?><br>
            Description : <br><?php echo nl2br(htmlspecialchars($annonce['description'])) ?><br>
            Date de création : <?php echo date('d-m-Y', strtotime($annonce['date_start'])) ?><br>
            Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_end'])) ?></p>
        <?php if ($user_id == $annonce['author']): ?>
            J'aime (<?php echo $like_count; ?>)
            <a href="?delete=<?php echo urlencode($annonce['id']) ?>">Supprimer</a><br>
        <?php endif; ?>
        <?php
        $i++;
    }
    ?>
</body>

<br>
<p id="message"></p>
<form action="aides_add.php">
<input type="button" value="Ajouter une annonce" id="add-button">
</form>

<script>
    var annonces = <?php echo json_encode($annonces); ?>;
    if (annonces.length >= 5) {
        document.getElementById('add-button').disabled = true;
        document.getElementById('message').textContent = "Vous avez atteint la limite de 5 annonces.";
    } else {
        document.getElementById('add-button').addEventListener('click', function() {
            window.location.href = 'aides_add.php';
        });
    }
</script>
</html>