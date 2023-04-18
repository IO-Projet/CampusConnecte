<!DOCTYPE html>
<html land="fr">
    <head>
        <meta charset="utf-8">
        <title>Connexion</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <div class="boite-connexion">
            <h2>Se connecter</h2>

            <form action="inscription_traitement.php" method="post">
                <span class="champ">
                    <label for="email">Email</label>
                    <input type="text" name="email"/>
                </span>
                <span class="champ">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp"/>
                </span>

                <span class="action">
                    <input type="submit" value="Valider"/>
                    <input type="reset" value="Effacer"/>
                </span>

                <p>Pas encore inscrit? <a href="inscription.php">S'inscrire</a></p>
            </form>
        </div>
    </body>
</html>