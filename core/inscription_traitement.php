<?php
    session_start();
    require_once '../config.php';

    if(isset($_POST['go'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $mdp = htmlspecialchars($_POST['mot_de_passe']);

        if(strlen($pseudo) < 1 ) {
            header("Location: inscription.php?erreur=emptypseudo");
            die();
        }
        if(strlen($pseudo) > 30) {
            header("Location: inscription.php?erreur=pseudomax");
            die();
        }
        if(empty($email)) {
            header("Location: inscription.php?erreur=emptyemail");
            die();
        }
        if(empty($mdp)) {
            header("Location: inscription.php?erreur=emptymdp");
            die();
        }
        if(!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            header("Location: inscription.php?erreur=email");
            die();
        }

        $req_mail = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $req_mail->execute(array($email));
        $exist_mail = $req_mail->rowCount(); // #rowCount() combien d'informations avec cet email existe

        if($exist_mail != 0) {
            header("Location: inscription.php?erreur=existemail");
            die();
        }



        $insert_user = $pdo->prepare("INSERT INTO users (pseudo, email, mot_de_passe) VALUES (?, ?, ?)");
        $insert_user->execute(array($pseudo, $email, $mdp));

        $_SESSION['compte_cree'] = "Votre compte a bien été créé !";
        header('Location: tableau_aides.php');
    }
?>