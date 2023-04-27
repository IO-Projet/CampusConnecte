<html>
    <head>
        <title>Profile</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <?php
        session_start();
        // Inclusion du fichier de configuration
        require '../config.php';

        // Vérification si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: connexion.php');
            exit;
        }

        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo "<p>$error</p>";
            }
            unset($_SESSION['errors']);
        }

        // Récupération de l'ID de l'utilisateur connecté
        $user_id = $_SESSION['user_id'];

        // Récupération des informations de profil de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();

        // Récupération des informations de profil de l'utilisateur
        $nom = isset($user['nom']) ? htmlspecialchars($user['nom']) : '';
        $prenom = isset($user['prenom']) ? htmlspecialchars($user['prenom']) : '';
        $pseudo = isset($user['pseudo']) ? htmlspecialchars($user['pseudo']) : '';
        $sexe = isset($user['sexe']) ? htmlspecialchars($user['sexe']) : 'A';
        $email = isset($user['email']) ? htmlspecialchars($user['email']) : '';
        $date_de_naissance = isset($user['date']) ? htmlspecialchars($user['date']) : '';
        $biography = isset($user['bio']) ? htmlspecialchars($user['bio']) : '';
    ?>

    <body>
        <h1>Modifier le profil</h1>
        <form action="formulaires/formulaire_profile_edit.php" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo $nom ?>" maxlength="20"><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $prenom ?>" maxlength="20"><br>

            <label for="pseudo">Pseudo :</label>
            <input type="text" id="pseudo" name="pseudo" value="<?php echo $pseudo ?>" maxlength="20"><br>

            <label>Sexe :</label><br>
            <input type="radio" id="male" name="sexe" value="M" <?php if ($sexe == 'M') echo 'checked' ?>>
            <label for="male">Male</label><br>
            <input type="radio" id="female" name="sexe" value="F" <?php if ($sexe == 'F') echo 'checked' ?>>
            <label for="female">Female</label><br>
            <input type="radio" id="other" name="sexe" value="A" <?php if ($sexe == 'A') echo 'checked' ?>>
            <label for="other">Autre</label><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo $email ?>"><br>

            <label for="password">Mot de passe actuel :</label>
            <input type="password" id="password" name="password"><br>

            <label for="new_password">Nouveau mot de passe :</label>
            <input type="password" id="new_password" name="new_password"><br>

            <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password"><br>

            <label for="date_de_naissance">Date de naissance :</label>
            <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?php echo $date_de_naissance ?>"><br>
            <label for="biography">Biographie :</label><br>
            <textarea id="biography" name="biography" rows="4" cols="50" maxlength="2000"><?php echo $biography ?></textarea><br>

            <input type="submit" value="Enregistrer les modifications">
        </form>
    </body>
</html>