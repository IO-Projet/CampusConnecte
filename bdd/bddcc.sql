-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 09 mai 2023 à 14:15
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bddcc`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces_aides`
--

CREATE TABLE `annonces_aides` (
  `id` int(11) NOT NULL,
  `theme` int(11) DEFAULT NULL,
  `auteur_aide` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `annonces_aides`
--

INSERT INTO `annonces_aides` (`id`, `theme`, `auteur_aide`, `description`, `date_debut`, `date_fin`) VALUES
(1, 1, 2, 'J\'ai besoin d\'aide pour les Matrices.', '2023-05-09', '2023-06-09'),
(2, 3, 2, 'Je ne comprend pas les COI et COD.', '2023-05-09', '2023-06-09'),
(3, 2, 2, 'Pouvez vous m\'aider pour les verbe irrégulier', '2023-05-09', '2023-06-09'),
(4, 1, 2, 'Je ne comprend pas les nombre complexe.', '2023-05-09', '2023-06-09'),
(7, 1, 2, 'Je ne comprend pas les suites.', '2023-05-09', '2023-06-09');

-- --------------------------------------------------------

--
-- Structure de la table `annonces_aides_likes`
--

CREATE TABLE `annonces_aides_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `annonces_aides_likes`
--

INSERT INTO `annonces_aides_likes` (`id`, `user_id`, `annonce_id`) VALUES
(1, 4, 3),
(2, 4, 7);

-- --------------------------------------------------------

--
-- Structure de la table `annonces_promotions`
--

CREATE TABLE `annonces_promotions` (
  `id` int(11) NOT NULL,
  `auteur_promotion` int(11) NOT NULL,
  `titre` text NOT NULL,
  `description` text NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `filtre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `annonces_promotions`
--

INSERT INTO `annonces_promotions` (`id`, `auteur_promotion`, `titre`, `description`, `date_debut`, `date_fin`, `filtre`) VALUES
(1, 4, 'Louvre', 'GRATUIT pour les étudiant.', '2023-05-09', '2024-05-09', 'D'),
(2, 4, 'Starbucks', '-5%.\r\nLes Lundi et Vendredi.', '2023-05-09', '2023-05-16', 'E'),
(3, 2, 'Haribo', '-10%.\r\nSur le site web.', '2023-05-09', '2023-05-12', 'E');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`) VALUES
(1, 'Mathématiques'),
(2, 'Anglais'),
(3, 'Français');

-- --------------------------------------------------------

--
-- Structure de la table `message_admin`
--

CREATE TABLE `message_admin` (
  `id` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_admin`
--

INSERT INTO `message_admin` (`id`, `auteur`, `message`) VALUES
(1, 2, 'Pouvez vous ajouter la matière HTML, s\'il vous plait.'),
(2, 2, 'Pouvez vous ajouter la matière CSS, s\'il vous plait.'),
(3, 2, 'Pouvez vous ajouter la matière PHP, s\'il vous plait.'),
(4, 2, 'Pouvez vous ajouter la matière IO2, s\'il vous plait.');

-- --------------------------------------------------------

--
-- Structure de la table `message_prives`
--

CREATE TABLE `message_prives` (
  `id` int(11) NOT NULL,
  `expediteur` int(11) NOT NULL,
  `destinataire` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_prives`
--

INSERT INTO `message_prives` (`id`, `expediteur`, `destinataire`, `date`, `message`) VALUES
(1, 1, 2, '2023-05-09 13:16:22', 'Bonjour, ceci est un message  qui provient d\'un Administrateur.');

-- --------------------------------------------------------

--
-- Structure de la table `profil_commentaires`
--

CREATE TABLE `profil_commentaires` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commentateur` int(11) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profil_commentaires`
--

INSERT INTO `profil_commentaires` (`id`, `user_id`, `commentateur`, `message`) VALUES
(1, 2, 1, 'Ceci est un commentaire.');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `pseudo` text NOT NULL,
  `sexe` text NOT NULL,
  `date` date DEFAULT NULL,
  `email` text NOT NULL,
  `mdp` text NOT NULL,
  `bio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `admin`, `nom`, `prenom`, `pseudo`, `sexe`, `date`, `email`, `mdp`, `bio`) VALUES
(1, 1, '', '', 'root', '', '0000-00-00', 'root@root.com', '$2y$10$6x50gdqq5u6gtfxke0Sifu5ekkSWKb16m99XOGKXAqSqn.flBtqFC', ''),
(2, 0, 'cellier', 'kerrian', 'kerrianc', 'M', '2003-07-29', 'kerrian.cellier@etu.u-paris.fr', '$2y$10$RLR/s1zmg9KDQiGHbYxkQOXiwpFptaQOWRkoDfox6pi1mXlPjjINS', 'Ceci est une biographie.'),
(4, 0, '', '', 'tq', '', '0000-00-00', 't.q@gmail.com', '$2y$10$iaSMG648DJCEa5kd5pB5Tuo3CAOqeCk.a4LLlKuQzqsmAKJ5rIkYm', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces_aides`
--
ALTER TABLE `annonces_aides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_idx` (`theme`),
  ADD KEY `auteur_idx` (`auteur_aide`);

--
-- Index pour la table `annonces_aides_likes`
--
ALTER TABLE `annonces_aides_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx` (`user_id`),
  ADD KEY `annonce_id_idx` (`annonce_id`);

--
-- Index pour la table `annonces_promotions`
--
ALTER TABLE `annonces_promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_idx` (`auteur_promotion`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message_admin`
--
ALTER TABLE `message_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auteur_idx` (`auteur`);

--
-- Index pour la table `message_prives`
--
ALTER TABLE `message_prives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expediteur_idx` (`expediteur`,`destinataire`),
  ADD KEY `destinataire` (`destinataire`);

--
-- Index pour la table `profil_commentaires`
--
ALTER TABLE `profil_commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_idx` (`user_id`,`commentateur`),
  ADD KEY `commentateur` (`commentateur`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces_aides`
--
ALTER TABLE `annonces_aides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `annonces_aides_likes`
--
ALTER TABLE `annonces_aides_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `annonces_promotions`
--
ALTER TABLE `annonces_promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `message_admin`
--
ALTER TABLE `message_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `message_prives`
--
ALTER TABLE `message_prives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `profil_commentaires`
--
ALTER TABLE `profil_commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces_aides`
--
ALTER TABLE `annonces_aides`
  ADD CONSTRAINT `auteur_aide` FOREIGN KEY (`auteur_aide`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `theme` FOREIGN KEY (`theme`) REFERENCES `matieres` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `annonces_aides_likes`
--
ALTER TABLE `annonces_aides_likes`
  ADD CONSTRAINT `annonce_id` FOREIGN KEY (`annonce_id`) REFERENCES `annonces_aides` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id_aides_likes` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `annonces_promotions`
--
ALTER TABLE `annonces_promotions`
  ADD CONSTRAINT `auteur_promotion` FOREIGN KEY (`auteur_promotion`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `message_admin`
--
ALTER TABLE `message_admin`
  ADD CONSTRAINT `auteur` FOREIGN KEY (`auteur`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `message_prives`
--
ALTER TABLE `message_prives`
  ADD CONSTRAINT `destinataire` FOREIGN KEY (`destinataire`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `expediteur` FOREIGN KEY (`expediteur`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `profil_commentaires`
--
ALTER TABLE `profil_commentaires`
  ADD CONSTRAINT `commentateur` FOREIGN KEY (`commentateur`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_id_profil_commentaires` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
