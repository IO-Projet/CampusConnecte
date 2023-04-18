<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Promotions</title>
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

        <div class="search">
            <form action="" method="get">
                <input type="search" placeholder="Recherche ...">
                <select name="duree" size="1">
                    <option value="1"> Ephémère </option>
                    <option value="2"> Durable </option>
                </select>
            </form>
        </div>

        <!-- Model Annonce -->
        <div class="annonce">
            <h3>Titre<?php ?></h3> <!-- Le nom de la matière -->
            <hr>
            <p>
                Description : <?php ?><br>
                Date de Création : <?php ?><br>
                Date de Fin : <?php ?><br>
            </p>
        </div>

        <div class="add">
            <form action="" method="post">
                <input type="submit" value="add" name="new"> <!-- Affiche si l'utilisateur est sur sa page de profile -->
            </form>
        </div>
    </body>
</html>