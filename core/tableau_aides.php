<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Aides</title>
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
        <div>
            <form action="" method="get">
                <h3>Légende</h3>
                <input type="checkbox" name="test" value="all">Tous/Rien<?php ?><br> <!-- pas sur pour le value -->
                <input type="checkbox" name="1" value="1">Matiere 1<?php ?><br>
                <input type="checkbox" name="2" value="2">Matiere 2<?php ?><br>
                <input type="checkbox" name="3" value="3">Matiere 3<?php ?><br>
                <input type="checkbox" name="fav" value="fav">Favori(s)<?php ?><br>
                <!-- ... -->
            </form>
        </div>

        <!-- Model Post It -->
        <div class="postit">
            <form action="" method="post">
                <h3>THEME<?php ?></h3> <!-- Le nom de la matière -->
                <hr>
                <p>
                    Nom, Prenom : <?php ?><br>
                    Description : <?php ?><br>
                    Date de Création : <?php ?><br>
                    Date de Fin : <?php ?><br>
                    <input type="submit" value="favori" name="fav">
                </p>
            </form>
        </div>

        <div>
            <form action="" method="form">
                <input type="submit" value="add" name="new"> <!-- Affiche si l'utilisateur est sur sa page de profile -->
            </form>
        </div>
    </body>
</html>