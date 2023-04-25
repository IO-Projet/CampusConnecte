<?php
session_start();
require_once '../config.php';

if(isset($_POST['go'])) {
    $email = htmlspecialchars($_POST['email']);
    $mdp = htmlspecialchars($_POST['mot_de_passe']);

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

    if($exist_mail = 0) {
        header("Location: inscription.php?email=$email");
        die();
    }

    $req_mdp = $pdo->prepare("SELECT mot_de_passe FROM users WHERE email=$email");
    if(!$req_mdp){
        header("Location: connexion.php?erreur=mdp");
        die();
    }

    if($mdp==$req_mdp){
        $_SESSION['compte_cree'] = "Bonjour !";
        header('Location: tableau_aides.php');
        die();
    }
}
?>