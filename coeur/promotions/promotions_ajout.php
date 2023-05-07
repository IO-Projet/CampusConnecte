<?php
    session_start();

    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Ajouter une promotion</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/promotion.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">

        <script>
            // Fonction pour activer/désactiver le champ date_fin
            function toggleDateEnd() {
                let durable = document.getElementById('durable');
                let dateFin = document.getElementById('date_fin');

                dateFin.disabled = durable.checked;
            }
        </script>
    </head>

    <body>
        <a href="promotions_gestion.php">Retour</a>

        <h1>Ajouter une promotion</h1>

        <?php
            if(isset($_SESSION['erreur'])) {
                switch($_SESSION['erreur']) {
                    case 1:
                        echo '<p>Veuillez remplir le titre.</p>';
                        break;

                    case 2:
                        echo '<p>Veuillez fournir une description.</p>';
                        break;

                    case 3:
                        echo '<p>Veuillez choisir le type.</p>';
                        break;

                    case 4:
                        echo '<p>La date d\'expiration doit être postérieure à la date actuelle.</p>';
                        break;
                }
            }
            unset($_SESSION['erreur']);

            // Récupération des valeurs précédemment saisies
            $titre = isset($_SESSION['titre']) ? $_SESSION['titre'] : '';
            $description = isset($_SESSION['description']) ? $_SESSION['description'] : '';
            $date_fin = isset($_SESSION['date_fin']) ? $_SESSION['date_fin'] : date('Y-m-d', strtotime('+1 month'));
            $filtre = isset($_SESSION['filtre']) ? $_SESSION['filtre'] : '';

            // Suppression des valeurs précédemment saisies
            unset($_SESSION['titre']);
            unset($_SESSION['description']);
            unset($_SESSION['date_fin']);
            unset($_SESSION['filtre']);
        ?>

        <form method="post" action="../formulaires/formulaire_promotions_ajout.php">
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre" maxlength="20" value="<?php echo htmlspecialchars($titre); ?>"><br>

            <label for="description">Description</label><br>
            <textarea name="description" id="description" maxlength="200"><?php echo htmlspecialchars($description); ?></textarea><br>

            <input type="radio" name="filtre" id="ephemere" value="E"<?php if($filtre == 'E' || $filtre == '') echo ' coché'; ?> onclick="toggleDateEnd()">
            <label for="ephemere">Ephémère</label><br>
            <input type="radio" name="filtre" id="durable" value="D"<?php if($filtre == 'D') echo ' coché'; ?> onclick="toggleDateEnd()">
            <label for="durable">Durable</label><br>

            <label for="date_fin">Date d'expiration </label>
            <input type="date" name="date_fin" id="date_fin" value="<?php echo htmlspecialchars($date_fin); ?>"><br>

            <input type="submit" value="Ajouter">
        </form>

        <!-- Désactivation du champ date_end si la case Durable est cochée -->
        <script>
            toggleDateEnd();
        </script>
    </body>
</html>