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

            <form action="connexion_traitement.php" method="post">
                <span class="champ">
                    <label for="email">Email</label>
                    <label><?php switch (isset($_GET["erreur"])){
                            case "emptyemail" :
                                echo "<br>Aucune adresse mail n'a été fournie !<br>";
                                break;
                            case "existemail" :
                                echo "<br>L'adresse mail est déjà utiliser !<br>";
                                break;
                            case "email" :
                                echo "<br>L'adresse mail est invalide !<br>";
                                break;
                            default:
                                echo "";
                                break;
                        } ?></label>
                    <input type="text" name="email"/>
                </span>
                <span class="champ">
                    <label for="mot_de_passe">Mot de passe</label>
                    <label><?php switch (isset($_GET["erreur"])){
                            case "mdp" :
                                echo "<br>Mot de Passe incorrecte !<br>";
                                break;
                            default:
                                echo "";
                                break;
                        } ?></label>
                    <input type="password" name="mot_de_passe"/>
                </span>

                <span class="action">
                    <input type="submit" value="Valider"/>
                </span>

                <p>Pas encore inscrit? <a href="inscription.php">S'inscrire</a></p>
            </form>
        </div>
    </body>
</html>