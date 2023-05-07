<?php
    class ClasseNavigation {
        /**
         * Envoie le menu de navigation
         * 
         * @param string $string
         * @param int $isAdmin
         * @return void
         */
        public function sendMenu(string $string, int $isAdmin) {
            $titre = "Profil - $string";

            echo '<a href="profil/profil.php" title='. $titre .'><img src="../icones/user.png" alt='. $titre .' width="32" height="32"></a>';
            echo '<a href="message_prives.php" title="Messages privés"><img src="../icones/message.png" alt="Messages privés" width="32" height="32"></a>';
            echo '<a href="aides/aides.php" title="Aides"><img src="../icones/aide.png" alt="Aides" width="32" height="32"></a>';
            echo '<a href="promotions/promotions.php" title="Promotions"><img src="../icones/promotion.png" alt="Promotions" width="32" height="32"></a>';

            if($isAdmin == 1) {
                echo '<a href="admin_pannel.php" title="Panneau d\'administration"><img src="../icones/admin.png" alt="Panneau d\'administration" width="32" height="32"></a>';
            } else {
                echo '<a href="contact.php" title="Contact Administrateurs"><img src="../icones/contact.png" alt="Contact Administrateurs" width="32" height="32"></a>';
            }

            echo '<a href="deconnexion.php" title="Déconnexion"><img src="../icones/deconnexion.png" alt="Déconnexion" width="32" height="32"></a>';
        }

        public function sendMenuPlusUn(string $string, int $isAdmin) {
            $titre = "Profil - $string";

            echo '<a href="../profil/profil.php" title='. $titre .'><img src="../../icones/user.png" alt='. $titre .' width="32" height="32"></a>';
            echo '<a href="../message_prives.php" title="Messages privés"><img src="../../icones/message.png" alt="Messages privés" width="32" height="32"></a>';
            echo '<a href="../aides/aides.php" title="Aides"><img src="../../icones/aide.png" alt="Aides" width="32" height="32"></a>';
            echo '<a href="../promotions/promotions.php" title="Promotions"><img src="../../icones/promotion.png" alt="Promotions" width="32" height="32"></a>';

            if($isAdmin == 1) {
                echo '<a href="../admin_pannel.php" title="Panneau d\'administration"><img src="../../icones/admin.png" alt="Panneau d\'administration" width="32" height="32"></a>';
            } else {
                echo '<a href="../contact.php" title="Contact Administrateurs"><img src="../../icones/contact.png" alt="Contact Administrateurs" width="32" height="32"></a>';
            }

            echo '<a href="../deconnexion.php" title="Déconnexion"><img src="../../icones/deconnexion.png" alt="Déconnexion" width="32" height="32"></a>';
        }
    }
?>
