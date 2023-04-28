<html>
<head>
    <title>Ajouter une promotion</title>
    <meta charset="utf-8">
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

// Prépare et exécute la requête SQL pour récupérer les annonces de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT * FROM annonces_promotions WHERE author = :author');
$stmt->execute(['author' => $user_id]);
$annonces = $stmt->fetchAll();

// Suppression d'une annonce si l'utilisateur a cliqué sur le lien "Supprimer"
if (isset($_GET['delete'])) {
    $annonce_id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM annonces_promotions WHERE id = :annonce_id AND author = :user_id");
    $stmt->bindParam(':annonce_id', $annonce_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header('Location: promotions_gerer.php');
    exit;
}
?>

<body>
<a href="promotions.php">Retour</a>

<h1>Gérer mes annonce de Promotion</h1>

<!-- Liste des annonces -->
<?php
$i = 1;
foreach ($annonces as $annonce) {
    ?>
    <!-- Affichage des annonces -->
    <h2>Annonce <?php echo $i; ?> : <?php echo htmlspecialchars($annonce['titre']); ?></h2>
    Description : <br><?php echo htmlspecialchars($annonce['description']); ?><br>
    Posté le : <?php echo date('d-m-Y', strtotime($annonce['date_start'])); ?><br>
    <?php if ($annonce['filtre'] == 'E'): ?>
        Date d'expiration : <?php echo date('d-m-Y', strtotime($annonce['date_end'])); ?><br>
    <?php endif; ?>
    <?php if ($user_id == $annonce['author']): ?>
        <a href="?delete=<?php echo urlencode($annonce['id']) ?>">Supprimer</a><br>
    <?php endif; ?>
    <?php
    $i++;
}
?>
</body>

<br>
<p id="message"></p>
<form action="promotions_add.php">
    <input type="submit" value="Ajouter une annonce">
</form>
</html>