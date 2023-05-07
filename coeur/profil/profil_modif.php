<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    if(isset($_SESSION['erreurs'])) {
        foreach ($_SESSION['erreurs'] as $error) {
            echo "<p>$error</p>";
        }
        unset($_SESSION['erreurs']);
    }

    // Récupération de l'ID de l'utilisateur connecté
    $user_id = $_SESSION['user_id'];

    // Récupération des informations de profil de l'utilisateur
    $req = $pdo -> prepare('SELECT * FROM users WHERE id = :user_id');
    $req -> bindParam(':user_id', $user_id);
    $req -> execute();
    $user = $req -> fetch();

    // Récupération des informations de profil de l'utilisateur
    $nom = isset($user['nom']) ? htmlspecialchars($user['nom']) : '';
    $prenom = isset($user['prenom']) ? htmlspecialchars($user['prenom']) : '';
    $pseudo = isset($user['pseudo']) ? htmlspecialchars($user['pseudo']) : '';
    $sexe = isset($user['sexe']) ? htmlspecialchars($user['sexe']) : 'A';
    $email = isset($user['email']) ? htmlspecialchars($user['email']) : '';
    $date_de_naissance = isset($user['date']) ? htmlspecialchars($user['date']) : '';
    $biographie = isset($user['bio']) ? htmlspecialchars($user['bio']) : '';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Profil</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/user.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>

    <body>
        <h1>Modifier le profil</h1>
        <form action="../formulaires/formulaire_profil_modif.php" method="post">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?php echo $nom ?>" maxlength="20"><br>

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $prenom ?>" maxlength="20"><br>

            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo $pseudo ?>" maxlength="20"><br>

            <label>Sexe</label><br>
            <input type="radio" id="homme" name="sexe" value="M" <?php if ($sexe == 'M') echo 'coché' ?>>
            <label for="homme">Homme</label><br>
            <input type="radio" id="femme" name="sexe" value="F" <?php if ($sexe == 'F') echo 'coché' ?>>
            <label for="femme">Femme</label><br>
            <input type="radio" id="autre" name="sexe" value="A" <?php if ($sexe == 'A') echo 'coché' ?>>
            <label for="autre">Autre</label><br>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email ?>"><br>

            <label for="mdp">Mot de passe actuel</label>
            <input type="password" id="mdp" name="mdp"><br>

            <label for="nouveau_mdp">Nouveau mot de passe</label>
            <input type="password" id="nouveau_mdp" name="nouveau_mdp"><br>

            <label for="confirm_nouveau_mdp">Confirmer le nouveau mot de passe</label>
            <input type="password" id="confirm_nouveau_mdp" name="confirm_nouveau_mdp"><br>

            <label for="date_de_naissance">Date de naissance</label>
            <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?php echo $date_de_naissance ?>"><br>
            <label for="biographie">Biographie</label><br>
            <textarea id="biographie" name="biographie" rows="4" cols="50" maxlength="2000"><?php echo $biographie ?></textarea><br>

            <input type="submit" value="Enregistrer les modifications">
        </form>
    </body>
</html>