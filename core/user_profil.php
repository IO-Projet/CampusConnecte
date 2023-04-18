<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>User Profile</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <div class="menu">
            <nav>
                <a href="user_profil.php">Menu 1</a><br>
                <a href="message_prive.php">Menu 2</a><br>
                <a href="tableau_aides.php">Menu 3</a><br>
                <a href="promotions.php">Menu 4</a><br>
            </nav>
        </div>
        <div class="information">
            <p>
                Nom : <?php ?><br> <!-- Affiche le nom de l'utilisateur -->
                Prénom : <?php ?><br> <!-- Affiche le prenom de l'utilisateur -->
                Description : <?php ?><br> <!-- Affiche la description/biography de l'utilisateur, s'il y a -->
            </p>
        </div>
        <div class="commentaires">
            <p>
                <?php ?> <!-- Affiche le nom des commentaires relatif à l'utilisateur, s'il y en a -->
            </p>
        </div>
        <div class="parametre">
            <form action="" method="form">
                <input type="submit" value="Parametre" name="new"> <!-- Affiche si l'utilisateur est sur sa page de profile -->
            </form>
        </div>
    </body>
</html>