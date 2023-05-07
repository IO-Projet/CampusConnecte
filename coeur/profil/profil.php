<?php
    session_start();
    // Inclusion du fichier de configuration
    require '../../config.php';
    require '../classes/ClasseNavigation.php';
    require '../classes/ClasseVerification.php';

    $verif = new ClasseVerification();
    $verif -> isNotSet("../connexion.php");

    $montrer_bouton_modif = false;
    $montrer_bouton_contact = false;
    
    if(isset($_GET['pseudo'])) {
        $pseudo = $_GET['pseudo'];
        $req = $pdo -> prepare('SELECT id FROM users WHERE pseudo = :pseudo');
        $req -> execute([':pseudo' => $pseudo]);
        $user_id = $req -> fetchColumn();
        
        if($user_id === false) {
            // Si l'utilisateur n'existe pas, afficher une page d'erreur 404
            header("HTTP/1.0 404 Not Found");
            echo "Error 404";
            exit;
        }
    
        $req = $pdo -> prepare("SELECT id, pseudo FROM users WHERE id = :id");
        $req -> execute([':id' => $_SESSION['user_id']]);
        $user_connecte = $req -> fetch(PDO::FETCH_ASSOC);
    
        if($_SESSION['user_id'] == $user_id) {
            $montrer_bouton_modif = true;
        } else {
            $montrer_bouton_contact = true;
        }
    } else {
        $montrer_bouton_modif = true;
        $user_id = $_SESSION['user_id'];
    }
    
    if(isset($user_id)) {
        $req = $pdo -> prepare('SELECT * FROM users WHERE id = :id');
        $req -> execute([':id' => $user_id]);
        $user = $req -> fetch(PDO::FETCH_ASSOC);
    }

    $nom = "N.A.";
    if(isset($user['nom']) && htmlspecialchars($user['nom']) != "") {
        $nom = strtoupper(htmlspecialchars($user['nom']));
    }

    $prenom = "N.A.";
    if(isset($user['prenom']) && htmlspecialchars($user['prenom']) != "") {
        $prenom = ucfirst(htmlspecialchars($user['prenom']));
    }

    $pseudo = ucfirst(htmlspecialchars($user['pseudo']));

    $sexe = "N.A.";
    if(isset($user['sexe']) && htmlspecialchars($user['sexe']) != "") {
        $sexe = ucfirst(htmlspecialchars($user['sexe']));
    }

    if($user['date'] == '0000-00-00' || $user['date'] == NULL) {
        $date_de_naissance = 'N.A.';
    } else {
        $date = date_create($user['date']);
        $date_de_naissance = $date -> format('d-m-Y');
    }

    $biographie = "";
    if(!empty($user['bio'])) {
        $biographie = "Biographie: ".ucfirst(htmlspecialchars($user['bio']));
    }
    
    // Récupération des commentaires de la table 'profil_commentaires'
    $query = '
            SELECT profil_commentaires.message, users.pseudo 
            FROM profil_commentaires 
            INNER JOIN users 
            ON profil_commentaires.commentateur = users.id
            WHERE profil_commentaires.user_id = :user_id
        ';
    $req = $pdo -> prepare($query);
    $req -> execute([':user_id' => $user['id']]);
    $comments = $req -> fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Profil - <?php echo $pseudo ?></title>
        <meta charset="utf-8">
        <link rel="icon" href="../../icones/user.png" type="image/png">
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    
    <body>
        <!-- Menu -->
        <?php
            $nav = new ClasseNavigation();
            $nav -> sendMenuPlusUn(
                isset($user_connecte['pseudo']) ? $user_connecte['pseudo'] : $pseudo,
                (isset($user['admin']) ? $user['admin'] : $user_connecte['admin'])
            );
        ?>
        <h1>Profil - <?php echo $pseudo; if($user['admin'] == 1) echo " [Administrateur]"; ?></h1>
        <p> Nom : <?php echo $nom ?><br>
            Prénom : <?php echo $prenom ?><br>
            Sexe : <?php echo $sexe ?><br>
            Date de naissance : <?php echo $date_de_naissance ?>
        </p>

        <p>
            Statut : Etudiant
            <?php
                if($sexe == 'F') {
                    echo "e";
                }
            ?>
            <br>

            <br><?php echo $biographie ?>
        </p>
        
        <?php if($montrer_bouton_modif): ?>
            <form action="profil_modif.php">
                <input type="submit" value="Modifier">
            </form>
        <?php endif; ?>
        
        <?php if($montrer_bouton_contact): ?>
            <a href="../message_prives.php?pseudo=<?= $pseudo ?>">Contacter</a><br>
        
            <form action="../formulaires/formulaire_profil_commentaires.php" method="post">
                <br>
                <h2>Ajouter un commantaire</h2>
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <input type="hidden" name="user_id_connecte" value="<?php echo $user_connecte['id']; ?>">
                <textarea name="message" id="message" maxlength="2000"></textarea><br>
                <input type="submit" value="Envoyer">
            </form>
        <?php endif; ?>
        
        <h2>Commentaires</h2>
        <?php foreach ($comments as $comment): ?>
            <p><?php echo $comment['pseudo']; ?> a écrit : <br>
                <?php echo $comment['message']; ?></p>
        <?php endforeach; ?>
    </body>
</html>