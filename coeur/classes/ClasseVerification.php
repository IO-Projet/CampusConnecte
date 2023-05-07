<?php
    class ClasseVerification {
        /**
         * Vérification de la connexion de l'utilisateur
         *
         * @param string $localisation
         * @return void
         */
        public function isSet(string $localisation) {
            if(isset($_SESSION['user_id'])) {
                header('Location: '. $localisation);
                exit;
            }
        }
        /**
         * Vérification de la connexion de l'utilisateur
         *
         * @param string $localisation
         * @return void
         */
        public function isNotSet(string $localisation) {
            if(!(isset($_SESSION['user_id']))) {
                header('Location: '. $localisation);
                exit;
            }
        }

        /**
         * Envoi un message d'erreur le cas échéant
         *
         * @return void
         */
        public function ifError() {
            if(isset($_SESSION['erreur_message'])) {
                echo '<p>'. $_SESSION['erreur_message'] .'</p>';
                unset($_SESSION['erreur_message']);
            }
        }

        /**
         * Vérification du formulaire s'il a été soumis
         *
         * @return bool
         */
        public function verifForm() {
            return isset($_POST['nom']) || isset($_POST['prenom']) || isset($_POST['pseudo']) || isset($_POST['sexe']) || isset($_POST['email']) || isset($_POST['nouveau_mdp']) || isset($_POST['date_de_naissance']) || isset($_POST['biographie']);
        }
    }
?>
