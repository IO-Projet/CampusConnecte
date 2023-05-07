# Nom du site 🌐

## Présentation 📝
- [Présentation du site web]

## Pourquoi choisir notre site? 🤔
- [Listez les raisons pour lesquelles les utilisateurs devraient choisir votre site]

## Installation 💻
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
