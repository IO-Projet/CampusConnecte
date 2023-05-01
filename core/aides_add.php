<html>
<?php
session_start();
// Inclusion du fichier de configuration
require '../config.php';

if (!(isset($_SESSION['user_id']))) {
    header('Location: connexion.php');
    exit;
}

// Récupération des valeurs précédemment saisies
$titre = isset($_SESSION['theme']) ? $_SESSION['theme'] : '';
$description = isset($_SESSION['description']) ? $_SESSION['description'] : '';
$date_end = isset($_SESSION['date_end']) ? $_SESSION['date_end'] : date('Y-m-d', strtotime('+1 month'));
$filtre = isset($_SESSION['filtre']) ? $_SESSION['filtre'] : '';

// Suppression des valeurs précédemment saisies
unset($_SESSION['theme']);
unset($_SESSION['description']);
unset($_SESSION['date_end']);
unset($_SESSION['filtre']);

// Prépare et exécute la requête SQL pour récupérer les matières de la table 'matieres'
$stmt = $pdo->prepare('SELECT * FROM matieres');
$stmt->execute();
$matieres = $stmt->fetchAll();

?>

<head>
    <title>Ajouter une promotion</title>
    <meta charset="utf-8">
    <link rel="icon" href="../icons/help.png" type="image/png">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<a href="aides_gerer.php">Retour</a>

<h1>Ajouter une demande d'aide</h1>

<?php if (isset($_SESSION['erreur'])): ?>
    <?php if ($_SESSION['erreur'] == 1): ?>
        <p>Veuillez remplir le theme.</p>
    <?php elseif ($_SESSION['erreur'] == 2): ?>
        <p>Veuillez fournir une description.</p>
    <?php elseif ($_SESSION['erreur'] == 3): ?>
        <p>La date d'expiration doit être postérieure à la date actuelle.</p>
    <?php endif; ?>
    <?php unset($_SESSION['erreur']); ?>
<?php endif; ?>

<form method="post" action="formulaires/formulaire_aides_add.php">
    <label for="theme">Thème :</label>
    <select name="theme" id="theme">
        <option value="0">Vide</option>
        <?php foreach ($matieres as $matiere): ?>
            <option value="<?php echo htmlspecialchars($matiere['id']); ?>"><?php echo htmlspecialchars($matiere['nom']); ?></option>
        <?php endforeach; ?>
    </select><br>

    <label for="description">Description :</label><br>
    <textarea name="description" id="description" rows="5" cols="33" maxlength="200"><?php echo htmlspecialchars($description); ?></textarea><br>

    <label for="date_end">Date d'expiration :</label>
    <input type="date" name="date_end" id="date_end" value="<?php echo htmlspecialchars($date_end); ?>"><br>

    <input type="submit" value="Ajouter">
</form>
</body>
</html>