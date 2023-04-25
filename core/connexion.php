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
        <title>Connexion</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <div class="boite-connexion">
            <h2>Se connecter</h2>

            <form action="formulaire_connexion.php" method="post">
                <span class="champ">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ""; ?>">
                </span>
                <span class="champ">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" id="password" name="mdp"/>
                </span>
                <span class="action">
                    <input type="submit" value="Valider"/>
                    <input type="reset" value="Effacer"/>
                </span>

                <p>Pas encore inscrit? <a href="inscription.php">S'inscrire</a></p>
            </form>
        </div>
    </body>

    <?php
    unset($_SESSION['email']);
    ?>
</html>