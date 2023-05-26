-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 26 mai 2023 à 08:47
-- Version du serveur :  5.7.17
-- Version de PHP :  7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet2quiz`
--

-- --------------------------------------------------------

--
-- Structure de la table `copies`
--

CREATE TABLE `copies` (
  `id` int(11) NOT NULL,
  `iduser_id` int(11) NOT NULL,
  `idquiz_id` int(11) NOT NULL,
  `annotation_globale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_max_quiz` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `copies`
--

INSERT INTO `copies` (`id`, `iduser_id`, `idquiz_id`, `annotation_globale`, `note_max_quiz`) VALUES
(2, 1, 11, ' A bien repondu', 14);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `titre`) VALUES
(1, 'SIO'),
(2, 'SAM'),
(3, 'IMO');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note_maximalequestion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `note_maximalequestion`) VALUES
(21, 11, 'Dans quelle ville allemande le mur de Berlin est-il tombé en 1989, mettant fin à la guerre froide et à la division de l\'Allemagne ?', 5),
(22, 11, 'Dans quelle ville allemande le mur de Berlin est-il tombé en 1989, mettant fin à la guerre froide et à la division de l\'Allemagne ?', 5),
(23, 11, 'Quel célèbre explorateur a mené une expédition à travers l\'Amérique du Sud au début du XXe siècle et est connu pour avoir découvert les ruines de la cité inca de Machu Picchu ?', 5),
(24, 11, 'Manger des bonbons abime les dents ?', 5);

-- --------------------------------------------------------

--
-- Structure de la table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `formation_id` int(11) NOT NULL,
  `formateur_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `note_maximale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `quiz`
--

INSERT INTO `quiz` (`id`, `formation_id`, `formateur_id`, `titre`, `description`, `date_debut`, `date_fin`, `note_maximale`) VALUES
(11, 1, 2, 'Test reponse', 'Test', '2023-05-05 10:01:00', '2023-05-05 13:00:00', 20);

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

CREATE TABLE `reponses` (
  `id` int(11) NOT NULL,
  `apprenant_id` int(11) NOT NULL,
  `id_question_id` int(11) NOT NULL,
  `reponse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_reponse` int(11) DEFAULT NULL,
  `annotation_question` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `copie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `reponses`
--

INSERT INTO `reponses` (`id`, `apprenant_id`, `id_question_id`, `reponse`, `note_reponse`, `annotation_question`, `copie_id`) VALUES
(34, 1, 21, 'Test', 5, 'Vrai', 2),
(35, 1, 22, 'Test 1', 0, 'Faut', 2),
(36, 1, 23, 'Test 2', 4, 'oui', 2),
(37, 1, 24, 'Test 3', 5, 'oui', 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idFormation` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `idFormation`) VALUES
(1, 'Elorenzini', '[\"ROLE_APPRE\"]', '$2y$13$O25E5cniKn0QLunO8MC1z.AvS7TJkI22LM5PMqoKPnzf7LyABVsc.', 1),
(2, 'Proche', '[\"ROLE_FORMA\"]', '$2y$13$kzMvtDidxddfvW8jfSIvNebKrRjxGZpqEEm1GDuK6tpho6QC3hHk2', NULL),
(3, 'Nkira', '[\"ROLE_APPRE\"]', '$2y$13$zefzkEAzcHGtdgtLw2o9G.MXGwPlrJjSbeqwxBL1evGWqkitI5cDu', 2),
(4, 'Tdupond', '[\"ROLE_APPRE\"]', '$2y$13$zefzkEAzcHGtdgtLw2o9G.MXGwPlrJjSbeqwxBL1evGWqkitI5cDu', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `copies`
--
ALTER TABLE `copies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D97FD39C786A81FB` (`iduser_id`),
  ADD KEY `IDX_D97FD39C5A38831B` (`idquiz_id`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8ADC54D5853CD175` (`quiz_id`);

--
-- Index pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A412FA925200282E` (`formation_id`),
  ADD KEY `IDX_A412FA92155D8F51` (`formateur_id`);

--
-- Index pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1E512EC6C5697D6D` (`apprenant_id`),
  ADD KEY `IDX_1E512EC66353B48` (`id_question_id`),
  ADD KEY `IDX_1E512EC64C4047D3` (`copie_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD KEY `IDX_8D93D649BCAA0AE9` (`idFormation`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `copies`
--
ALTER TABLE `copies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT pour la table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `copies`
--
ALTER TABLE `copies`
  ADD CONSTRAINT `FK_D97FD39C5A38831B` FOREIGN KEY (`idquiz_id`) REFERENCES `quiz` (`id`),
  ADD CONSTRAINT `FK_D97FD39C786A81FB` FOREIGN KEY (`iduser_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `FK_8ADC54D5853CD175` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`);

--
-- Contraintes pour la table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `FK_A412FA92155D8F51` FOREIGN KEY (`formateur_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A412FA925200282E` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`);

--
-- Contraintes pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD CONSTRAINT `FK_1E512EC64C4047D3` FOREIGN KEY (`copie_id`) REFERENCES `copies` (`id`),
  ADD CONSTRAINT `FK_1E512EC66353B48` FOREIGN KEY (`id_question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `FK_1E512EC6C5697D6D` FOREIGN KEY (`apprenant_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649BCAA0AE9` FOREIGN KEY (`idFormation`) REFERENCES `formation` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
