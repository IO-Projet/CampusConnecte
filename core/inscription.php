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
            <form action="inscription_traitement.php" method="post">
                <span class="champ">

                    <label><?php if(isset($_GET["erreur"])) echo "error : ".$_GET["erreur"]."<br><br>"; ?></label>

                    <label for="pseudo">Pseudo</label>
                    <label><?php switch (isset($_GET["erreur"])){
                            case "pseudo" :
                                echo "<br>Aucun Pseudonyme n'a été fournie !<br>";
                                break;
                            case "pseudomax" :
                                echo "<br>Le Pseudo dépasse les 30 caractères !<br>";
                                break;
                            default:
                                echo "";
                                break;
                        } ?></label>
                    <input type="text" name="pseudo" size="20" value="<?php if(isset($pseudo)) echo $pseudo; ?>"/>
                </span>
                <br>
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
                    <input type="text" name="email" size="30" value="<?php if(isset($email)) echo $email; ?>"/>
                </span>
                <br>
                <span class="champ">
                    <label for="mot_de_passe">Mot de passe</label>
                    <label><?php switch (isset($_GET["erreur"])){
                            case "emptymdp" :
                                echo "<br>Le mot de passe est invalide !<br>";
                                break;
                            default:
                                echo "";
                                break;
                        } ?></label>
                    <input type="password" name="mot_de_passe" size="20"/>
                </span>
                <br>
                <input type="submit" value="Valider" name="go">

                <p>Déjà inscrit? <a href="connexion.php">Se connecter</a></p>
            </form>
        </div>
    </body>
</html>