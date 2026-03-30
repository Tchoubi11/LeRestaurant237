-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 26 mars 2026 à 19:54
-- Version du serveur : 8.0.38
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lerestaurant237`
--

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

CREATE TABLE `allergene` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `allergene`
--

INSERT INTO `allergene` (`id`, `nom`) VALUES
(1, 'Gluten'),
(2, 'Arachides'),
(3, 'Fruits à coque'),
(4, 'Lait'),
(5, 'Œufs'),
(6, 'Soja'),
(7, 'Poisson'),
(8, 'Crustacés');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int NOT NULL,
  `commentaire` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int NOT NULL,
  `date_avis` datetime NOT NULL,
  `commande_id` int NOT NULL,
  `user_id` int NOT NULL,
  `valide` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `commentaire`, `note`, `date_avis`, `commande_id`, `user_id`, `valide`) VALUES
(1, 'Service impeccable et ponctuel , je recommande fort.', 3, '2026-03-12 20:08:51', 4, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int NOT NULL,
  `nom_client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_commande` datetime NOT NULL,
  `menu_id` int NOT NULL,
  `nombre_personnes` int NOT NULL,
  `prix_total` double NOT NULL,
  `prix_livraison` double NOT NULL,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `motif_annulation` longtext COLLATE utf8mb4_unicode_ci,
  `mode_contact` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `nom_client`, `email`, `telephone`, `date_commande`, `menu_id`, `nombre_personnes`, `prix_total`, `prix_livraison`, `statut`, `user_id`, `motif_annulation`, `mode_contact`) VALUES
(1, 'Betayene', 'cathy@vitetgourmand.com', '0123456789', '2026-02-26 14:16:37', 2, 0, 0, 0, '', NULL, NULL, NULL),
(2, 'Lise Tchoubaye', 'lise@vitetgourmand.com', '0123456789', '2026-03-02 18:16:55', 2, 20, 635, 5, '', NULL, NULL, NULL),
(3, 'Lise Tchoubaye', 'lise@vitetgourmand.com', '0123654789', '2026-03-04 16:14:25', 3, 30, 1655, 5, 'annulee', 3, NULL, NULL),
(4, 'Lise Tchoubaye', 'lise@vitetgourmand.com', '0123654789', '2026-03-12 09:45:58', 2, 16, 565, 5, 'terminee', 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commande_historique`
--

CREATE TABLE `commande_historique` (
  `id` int NOT NULL,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_modification` datetime NOT NULL,
  `commande_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commande_historique`
--

INSERT INTO `commande_historique` (`id`, `statut`, `date_modification`, `commande_id`) VALUES
(1, 'en_attente', '2026-03-12 09:45:59', 4),
(2, 'accepte', '2026-03-12 19:35:16', 4),
(3, 'en_preparation', '2026-03-12 19:36:29', 4),
(4, 'en_livraison', '2026-03-12 19:37:17', 4),
(5, 'livre', '2026-03-12 19:49:13', 4),
(6, 'retour_materiel', '2026-03-12 19:58:59', 4),
(7, 'terminee', '2026-03-12 20:04:55', 4);

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260216141538', '2026-02-16 14:17:49', 133),
('DoctrineMigrations\\Version20260216143031', '2026-02-16 14:30:35', 771),
('DoctrineMigrations\\Version20260216143335', '2026-02-16 14:33:40', 54),
('DoctrineMigrations\\Version20260217180558', '2026-02-17 18:06:02', 328),
('DoctrineMigrations\\Version20260218104233', '2026-02-18 10:42:37', 1287),
('DoctrineMigrations\\Version20260218194011', '2026-02-18 19:40:15', 302),
('DoctrineMigrations\\Version20260219140824', '2026-02-19 14:08:27', 106),
('DoctrineMigrations\\Version20260219142822', '2026-02-19 14:28:24', 179),
('DoctrineMigrations\\Version20260225095815', '2026-02-25 09:58:20', 776),
('DoctrineMigrations\\Version20260227132025', '2026-02-27 13:20:29', 1160),
('DoctrineMigrations\\Version20260302183216', '2026-03-02 18:32:20', 500),
('DoctrineMigrations\\Version20260303134316', '2026-03-03 13:43:40', 832),
('DoctrineMigrations\\Version20260304144633', '2026-03-04 14:46:39', 859),
('DoctrineMigrations\\Version20260304151333', '2026-03-04 15:13:35', 32),
('DoctrineMigrations\\Version20260304155919', '2026-03-04 15:59:22', 59),
('DoctrineMigrations\\Version20260305153250', '2026-03-05 15:32:54', 682),
('DoctrineMigrations\\Version20260310162043', '2026-03-10 16:21:01', 1669);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_id` int NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `menu_id`, `updated_at`) VALUES
(1, 'redd-francisco-o1sdskce8ie-unsplash-699e1f2d1db88180716656.jpg', 1, '2026-02-24 21:59:09'),
(2, 'spencer-davis-5dszncvdhd0-unsplash-699ec03c0487b145069737.jpg', 2, '2026-02-25 09:26:20'),
(3, 'artem-beliaikin-tss-1aqorxe-unsplash-699ec12be15ea929916717.jpg', 3, '2026-02-25 09:30:19');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `date_creation` datetime NOT NULL,
  `nombre_personnes_minimum` int NOT NULL,
  `theme` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `regime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conditions_menu` longtext COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `titre`, `description`, `prix`, `date_creation`, `nombre_personnes_minimum`, `theme`, `regime`, `conditions_menu`, `stock`) VALUES
(1, 'Menu Élégance Nuptiale', '<div>Le Menu Élégance Nuptiale est conçu pour sublimer les mariages et grandes réceptions.<br>&nbsp;Il propose une expérience gastronomique raffinée alliant produits frais, présentation élégante et saveurs équilibrées.<br><br></div><div>Ce menu comprend une entrée délicate, un plat principal généreux accompagné de garnitures de saison, un dessert raffiné ainsi qu’une sélection de boissons adaptées à l’événement.<br><br></div><div>Chaque détail est pensé pour offrir aux mariés et à leurs invités un moment inoubliable placé sous le signe de l’excellence culinaire et du raffinement.<br><br></div>', 85, '2026-02-24 21:59:08', 80, 'prestige', 'classique', '<div>&nbsp;Service inclus pendant 5 heures. Confirmation 15 jours avant l\'événement.&nbsp;</div>', 10),
(2, 'Menu Fête Gourmande', '<div>Le Menu Fête Gourmande est idéal pour les anniversaires et célébrations familiales.<br>&nbsp;Il propose des plats généreux, savoureux et conviviaux qui raviront petits et grands.<br><br></div><div>Ce menu comprend une entrée fraîche et légère, un plat principal riche en saveurs accompagné d’une garniture traditionnelle, ainsi qu’un dessert festif parfaitement adapté aux grandes occasions.<br><br></div><div>L’équilibre entre qualité, quantité et convivialité en fait un choix parfait pour partager un moment chaleureux avec vos proches.<br><br></div>', 35, '2026-02-25 09:26:18', 15, 'anniversaire', 'halal', '<div>&nbsp;Commande minimum 72h à l’avance. Livraison en option.&nbsp;</div>', 25),
(3, 'Menu Business Excellence', '<div>Le Menu Business Excellence a été spécialement conçu pour les événements professionnels tels que séminaires, conférences et réunions d’entreprise.<br><br></div><div>Il propose une cuisine moderne, équilibrée et élégamment présentée afin de satisfaire les attentes d’un public professionnel exigeant.<br><br></div><div>Chaque formule comprend une entrée raffinée, un plat principal léger mais savoureux, un dessert soigné ainsi qu’une sélection de boissons non alcoolisées.<br><br></div><div>L’objectif est d’offrir un repas de qualité tout en respectant les contraintes de temps propres aux événements d’entreprise.<br><br></div>', 55, '2026-02-25 09:30:19', 30, 'entreprise', 'vegetarien', '<div>&nbsp;Réservation obligatoire 7 jours à l’avance. Installation buffet incluse.&nbsp;</div>', 20);

-- --------------------------------------------------------

--
-- Structure de la table `menu_plat`
--

CREATE TABLE `menu_plat` (
  `menu_id` int NOT NULL,
  `plat_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `menu_plat`
--

INSERT INTO `menu_plat` (`menu_id`, `plat_id`) VALUES
(1, 2),
(1, 4),
(1, 9),
(1, 11),
(2, 1),
(2, 5),
(2, 8),
(2, 12),
(3, 3),
(3, 6),
(3, 10),
(3, 11);

-- --------------------------------------------------------

--
-- Structure de la table `plat`
--

CREATE TABLE `plat` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plat`
--

INSERT INTO `plat` (`id`, `nom`, `type`, `description`) VALUES
(1, 'Salade César Premium', 'entree', '<div>&nbsp;Salade fraîche composée de laitue croquante, parmesan affiné, croûtons maison et sauce César artisanale.&nbsp;</div>'),
(2, 'Carpaccio de Bœuf', 'entree', '<div>&nbsp;Fines tranches de bœuf assaisonnées d’huile d’olive extra vierge, copeaux de parmesan et roquette fraîche.&nbsp;</div>'),
(3, 'Velouté de Potiron', 'entree', '<div>&nbsp;Soupe onctueuse de potiron parfumée à la noix de muscade et éclats de noisettes grillées.&nbsp;</div>'),
(4, 'Filet de Bœuf Sauce Morilles', 'plat', '<div>&nbsp;Filet de bœuf tendre accompagné d’une sauce crémeuse aux morilles et légumes de saison.&nbsp;</div>'),
(5, 'Poulet Rôti Fermier', 'plat', '<div>Poulet fermier rôti au four, servi avec pommes de terre fondantes et légumes sautés.<br><br></div>'),
(6, 'Pavé de Saumon Grilllé', 'plat', '<div>&nbsp;Saumon grillé à la perfection, servi avec riz parfumé et sauce citronnée.&nbsp;</div>'),
(7, 'Risotto aux Champignons', 'plat', '<div>&nbsp;Risotto crémeux aux champignons frais et parmesan affiné.&nbsp;</div>'),
(8, 'Tiramisu Maison', 'dessert', '<div>&nbsp;Dessert italien traditionnel au mascarpone et café intense.<br><br></div>'),
(9, 'Fondant au Chocolat', 'dessert', '<div>&nbsp;Gâteau au chocolat cœur coulant servi tiède.&nbsp;</div>'),
(10, 'Salade de Fruits Frais', 'dessert', '<div>&nbsp;Mélange de fruits de saison frais et légèrement sucrés.&nbsp;</div>'),
(11, 'Cocktail Sans Alcool Maison', 'boisson', '<div>&nbsp;Mélange rafraîchissant de jus de fruits frais et menthe.&nbsp;</div>'),
(12, 'Jus de Bissap', 'boisson', '<div>&nbsp; Boisson traditionnelle à base d’hibiscus légèrement sucrée.&nbsp;</div>');

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gsm` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `actif` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `gsm`, `adresse`, `actif`) VALUES
(1, 'koubi@vitetgourmand.com', '[\"ROLE_EMPLOYE\"]', '$2y$13$rDP/WV7jPWaJZe7rW9QqO.OSczUr7z.uHD3t72.6FnbECsW7re/IK', 'Tchoubaye', 'Frank', '0600000000', '1220 Avenue Biot', 0),
(2, 'cathy@vitetgourmand.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]', '$2y$13$kjBZ3EneJ1p2/I.r7YmG.epYcYl.YVMwNEAxnRH5OLkHkuww/od6e', 'Tchoubaye', 'cathy', '0600000000', '1220 Avenue Biot', 0),
(3, 'lise@vitetgourmand.com', '[\"ROLE_USER\"]', '$2y$13$Z5/pQ08If3fac2dTxavwVOmgh1oPRyPhsoSQn4KFPuqSmkOP2OLEK', 'Tchoubaye', 'Lise', '0600000000', '1220 Avenue Biot', 0),
(4, 'employe1@vitetgourmand.com', '[\"ROLE_EMPLOYE\"]', '$2y$13$7v5LX/6xvNehWEeN694ub.4i.CpfsgpEIvx/x1.DwHkGE5qlewCxy', 'Employe1', 'Employe1', '012345678', '62 rue hermant', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8F91ABF082EA2E54` (`commande_id`),
  ADD KEY `IDX_8F91ABF0A76ED395` (`user_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67DCCD7E912` (`menu_id`),
  ADD KEY `IDX_6EEAA67DA76ED395` (`user_id`);

--
-- Index pour la table `commande_historique`
--
ALTER TABLE `commande_historique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_757DF90A82EA2E54` (`commande_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045FCCD7E912` (`menu_id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD PRIMARY KEY (`menu_id`,`plat_id`),
  ADD KEY `IDX_E8775249CCD7E912` (`menu_id`),
  ADD KEY `IDX_E8775249D73DB560` (`plat_id`);

--
-- Index pour la table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande_historique`
--
ALTER TABLE `commande_historique`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `plat`
--
ALTER TABLE `plat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `FK_8F91ABF082EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_8F91ABF0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6EEAA67DCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Contraintes pour la table `commande_historique`
--
ALTER TABLE `commande_historique`
  ADD CONSTRAINT `FK_757DF90A82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045FCCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Contraintes pour la table `menu_plat`
--
ALTER TABLE `menu_plat`
  ADD CONSTRAINT `FK_E8775249CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_E8775249D73DB560` FOREIGN KEY (`plat_id`) REFERENCES `plat` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
