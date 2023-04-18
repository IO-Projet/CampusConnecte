<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Message Prive</title>
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

        <!-- En tete des amies -->
        <div class="en-tete">
            <h3>MESSAGE PRIVER</h3>
            <hr>

            <!-- Model Utilisateurs -->
            <div class="utilisateurs">
                <form action="" method="post">
                    <input type="button" value=<?php ?>> <!-- le nom de l'utilisateur -->
                </form>
            </div>

        </div>

        <div class="add">
            <form action="" method="post">
                <input type="submit" value="add" name="new"> <!-- Affiche si l'utilisateur est sur sa page de profile -->
            </form>
        </div>

        <div class="messagerie">
            <div class="en-tete">
                <h3><?php ?></h3> <!-- le nom de l'utilisateur -->
                <hr>

                <!-- Model Messages -->
                <div class="utilisateur_message">
                    <p><?php ?></p> <!-- les derniers messages -->
                </div>

                <div>
                    <form action="" method="post">
                        <input type="text" name="message">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>