<?php
    session_start();
    require_once '../config.php';

    if(isset($_POST['go'])) {
        $sexe = htmlspecialchars($_POST['sexe']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $date_naissance = htmlspecialchars($_POST['date_naissance']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);

        // trouver une méthode qui hache le mdp
        $mdp = $_POST['mot_de_passe'];
        $confirm_mdp = $_POST['confirm_mot_de_passe'];

        if(empty($sexe) || empty($nom) || empty($prenom) || empty($date_naissance) || empty($pseudo) || empty($email) || empty($mdp) || empty($confirm_mdp)) {
            exit("Tous les champs doivent être complétés !");
        }

        if(strlen($pseudo) > 30) {
            exit("Votre pseudo est trop long !");
        }

        if(!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            exit("Votre adresse email n'est pas valide !");
        }

        $req_mail =
            // on prepare la requete pour obtenir tous les utilisateurs dont l'email correspond à ceux de la bdd
            $pdo -> prepare("SELECT * FROM users WHERE email=?");
        $req_mail -> execute(array($email));
        $exist_mail = $req_mail -> rowCount(); // #rowCount() combien d'informations avec cet email existe

        if($exist_mail != 0) {
            exit("Cette adresse email est déjà utilisée !");
        }

        if($mdp != $confirm_mdp) {
            exit("Vos mots de passe ne correspondent pas !");
        }

        foreach($sexe as $s) {
            $insert_user = $pdo -> prepare("INSERT INTO users (sexe, nom, prenom, date_de_naissance, pseudo, email, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_user -> execute(array($s, $nom, $prenom, $date_naissance, $pseudo, $email, $mdp));

            $_SESSION['compte_cree'] = "Votre compte a bien été créé !";
            header('Location: accueil.php');
        }
    }
?>