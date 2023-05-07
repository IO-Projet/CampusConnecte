# 🌐 - Campus Connecté

## 📝 - Présentation
- Vous êtes étudiant et vous cherchez un endroit pour obtenir de l’aide pour vos devoirs, partager des informations et des offres spéciales pour les étudiants ? Ne cherchez plus, Campus Connecté est là pour vous ! Notre site, créé par des étudiants pour des étudiants, offre un espace de partage et d’entraide unique. 
Avec notre coin “aide aux devoirs”, vous pouvez demander de l’aide en postant une annonce et obtenir des réponses de la part d’autres étudiants. 
De plus, notre système d’information vous tient au courant des dernières offres et promotions spéciales pour les étudiants. 
Mais ce n’est pas tout ! 
Campus Connecté offre également d’autres options pour vous aider dans votre vie étudiante, telles que des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant. 

Rejoignez notre communauté dès maintenant et profitez de tous les avantages que Campus Connecté a à offrir !

## 🤔 - Pourquoi choisir notre site ?
- Créé par des étudiants pour des étudiants.
- Coin “aide aux devoirs” pour demander de l’aide en postant une annonce.
- Système d’information pour les offres et promotions spéciales pour les étudiants.
- Possibilité pour les utilisateurs d’ajouter des annonces pour informer les autres utilisateurs d’une promotion spéciale étudiant.
- Espace de partage et d’entraide unique pour les étudiants.

## 💻 - Installation
Pour installer notre site, suivez les étapes suivantes:
1. Copiez le fichier `config-exemple.php` en `config.php`.
    1. Via le terminal: dans le répertoire où est `config-exemple.php`, exécutez la commande suivante:
        ```bash
        cp config-example.php config.php
        ```
    2. Via l'explorateur de fichiers: faites un copier-coller du fichier `config-exemple.php` puis renommez-le en `config.php`.
2. Renseignez les informations de connexion à la base de données dans le fichier `config.php`. La ligne à modifier est: `$pdo = new PDO("mysql:host=;dbname=","","");`. Remplacez les valeurs suivantes:
    1. `host=`: l'adresse de votre base de données (si c'est un serveur local, alors l'adresse est `localhost`).
    2. `dbname=`: le nom de votre base de données.
    3. Dans les premières `""`: l'identifiant de l'administrateur de la base de données.
    4. Dans les secondes `""`: le mot de passe de l'administrateur de la base de données.
3. Importez les tables de base dans votre base de données. Le fichier à importer est `tables-exemple.sql`. Vous trouverez des instructions sur la façon d'importer les tables dans phpMyAdmin pour XAMPP et MAMP.
