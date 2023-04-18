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
                    <input type="radio" name="sexe[]" value="M" required/>
                    <label for="sexeM">M</label>
                    <input type="radio" name="sexe[]" value="F" required/>
                    <label for="sexeF">F</label>
                    <input type="radio" name="sexe[]" value="NB" required/>
                    <label for="sexeNB">NB</label>
                </span>
                <span class="champ">
                    <label for="nom">Nom</label>
                    <input type="text" placeholder="ex: NOM" name="nom" size="20" value="<?php if(isset($nom)) echo $nom; ?>" required/>
                </span>
                <span class="champ">
                    <label for="prenom">Prénom</label>
                    <input type="text" placeholder="ex: Prénom" name="prenom" size="20" value="<?php if(isset($prenom)) echo $prenom; ?>" required/>
                </span>
                <span class="champ">
                    <label for="date_naissance">Date de naissance</label>
                    <input type="datetime-local" name="date_naissance" size="30" value="<?php if(isset($date_naissance)) echo $date_naissance; ?>" required/>
                </span>
                <br>
                <span class="champ">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" size="20" value="<?php if(isset($pseudo)) echo $pseudo; ?>" required/>
                </span>
                <span class="champ">
                    <label for="email">Email</label>
                    <input type="text" name="email" size="30" value="<?php if(isset($email)) echo $email; ?>" required/>
                </span>
                <span class="champ">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" name="mot_de_passe" size="20" required/>
                </span>
                <span class="champ">
                    <label for="confirm_mot_de_passe">Confirmation du mot de passe</label>
                    <input type="password" name="confirm_mot_de_passe" size="20" required/>
                </span>

                <input type="submit" value="Valider" name="go">
                <input type="reset" value="Effacer">

                <p>Déjà inscrit? <a href="connexion.php">Se connecter</a></p>
            </form>
        </div>
    </body>
</html>