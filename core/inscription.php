<?php
    session_start();

    if(isset($_SESSION['message_erreur'])) {
        echo "<p>". $_SESSION['message_erreur'] ."</p>";
        unset($_SESSION['message_erreur']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Inscription</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <div class="boite-inscription">
            <h2>S'inscrire</h2>

            <form action="formulaire_inscription.php" method="post">
               <span class="champ">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ""; ?>">
                </span>
                <span class="champ">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="password" name="mdp"/>
                </span>
                <span class="champ">
                    <label for="confirm_mdp">Confirmation du mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_mdp"/>
                </span>

                <input type="submit" value="Valider" name="go">
                <input type="reset" value="Effacer">

                <p>Déjà inscrit? <a href="connexion.php">Se connecter</a></p>
            </form>
        </div>
    </body>

    <?php
        unset($_SESSION['email']);
    ?>
</html>