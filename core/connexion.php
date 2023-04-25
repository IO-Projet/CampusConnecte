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

            <form action="s.php" method="post">
                <span class="champ">
                    <label for="email">Email</label>
                    <input type="text" name="email"/>
                </span>
                <span class="champ">
                    <label for="mdp">Mot de passe</label>
                    <label><?php switch (isset($_GET["erreur"])){
                            case "mdp" :
                                echo "<br>Mot de Passe Incorrecte !<br>";
                                break;
                            default:
                                echo "";
                                break;
                        } ?></label>
                    <input type="password" name="mdp"/>
                </span>

                <span class="action">
                    <input type="submit" value="Valider"/>
                </span>

                <p>Pas encore inscrit? <a href="inscription.php">S'inscrire</a></p>
            </form>
        </div>
    </body>
</html>