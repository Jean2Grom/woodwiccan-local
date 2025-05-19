-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 19 mai 2025 à 09:28
-- Version du serveur : 11.7.2-MariaDB-ubu2404
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `woodwiccan`
--

-- --------------------------------------------------------

--
-- Structure de la table `archive__test`
--

CREATE TABLE `archive__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `nouvel-attribut-boolean__archive__archive@boolean#value` int(11) DEFAULT NULL,
  `nouvel-attribut-integer__archive__archive@integer#value` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime__archive__archive@datetime#value` datetime DEFAULT NULL,
  `nouvel-attribut-string__archive__archive@string#value` varchar(511) DEFAULT NULL,
  `nouvel-attribut-text__archive__archive@text#value` text DEFAULT NULL,
  `nouvel-attribut-decimal__archive__archive@decimal#value` decimal(10,2) DEFAULT NULL,
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `archive__ww-user`
--

CREATE TABLE `archive__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cauldron`
--

CREATE TABLE `cauldron` (
  `id` int(10) UNSIGNED NOT NULL,
  `target` int(10) UNSIGNED DEFAULT NULL,
  `status` bit(1) DEFAULT NULL COMMENT 'Null for content, 0 for draft, 1 for archive',
  `name` varchar(255) DEFAULT NULL,
  `recipe` varchar(128) NOT NULL DEFAULT 'folder',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `priority` int(11) NOT NULL DEFAULT 0,
  `creator` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modificator` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level_1` int(11) DEFAULT NULL,
  `level_2` int(10) UNSIGNED DEFAULT NULL,
  `level_3` int(10) UNSIGNED DEFAULT NULL,
  `level_4` int(10) UNSIGNED DEFAULT NULL,
  `level_5` int(10) UNSIGNED DEFAULT NULL,
  `level_6` int(10) UNSIGNED DEFAULT NULL,
  `level_7` int(10) UNSIGNED DEFAULT NULL,
  `level_8` int(10) UNSIGNED DEFAULT NULL,
  `level_9` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cauldron`
--

INSERT INTO `cauldron` (`id`, `target`, `status`, `name`, `recipe`, `data`, `priority`, `creator`, `created`, `modificator`, `modified`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`, `level_8`, `level_9`) VALUES
(1, NULL, NULL, 'ROOT', 'root', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 15:08:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'admin', 'ww-site-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:13:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'ww-user', 'ww-recipe-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:15:12', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Administrateur', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:14', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:30', 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(7, NULL, NULL, 'Jean', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:44', 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-28 10:03:52', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL),
(149, NULL, NULL, 'folder', 'ww-recipe-folder', NULL, 0, NULL, '2024-10-08 15:37:41', NULL, '2024-11-27 16:15:09', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(408, NULL, NULL, 'identifier', 'ww-recipe-folder', NULL, 0, NULL, '2025-01-10 16:21:37', NULL, '2025-01-10 16:21:37', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(770, NULL, NULL, 'link', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-06 17:17:29', NULL, '2025-03-06 17:17:29', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(773, NULL, NULL, 'ww-file', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-10 09:19:15', NULL, '2025-03-10 09:19:15', 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(795, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-05-07 16:35:06', NULL, '2025-05-07 16:35:06', 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL),
(796, 7, b'0', 'Jean', 'ww-user', NULL, 0, NULL, '2025-05-07 16:42:33', NULL, '2025-05-07 16:42:33', 1, 1, 2, 3, 1, NULL, NULL, NULL, NULL),
(797, NULL, NULL, 'inner contents', 'folder', NULL, 0, NULL, '2025-05-07 16:42:33', NULL, '2025-05-07 16:43:30', 1, 1, 2, 3, 1, 1, NULL, NULL, NULL),
(798, NULL, NULL, 'profiles', 'ww-profiles-array', NULL, 100, NULL, '2025-05-07 16:43:07', NULL, '2025-05-07 16:43:07', 1, 1, 2, 3, 1, 2, NULL, NULL, NULL),
(799, NULL, NULL, 'vrac', 'folder', NULL, 0, NULL, '2025-05-15 13:28:51', NULL, '2025-05-15 13:30:50', 1, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `content__test`
--

CREATE TABLE `content__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-connexion@connexion#id` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime@datetime#value` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `content__ww-user`
--

CREATE TABLE `content__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `content__ww-user`
--

INSERT INTO `content__ww-user` (`id`, `name`, `creator`, `created`, `modificator`, `modified`, `last-name@string#value`, `first-name@string#value`, `connection@connexion#id`) VALUES
(1, 'Administrateur', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01', 'WoodWiccan', 'Administrateur', 1);

-- --------------------------------------------------------

--
-- Structure de la table `draft__test`
--

CREATE TABLE `draft__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-connexion@connexion#id` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime@datetime#value` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `draft__ww-user`
--

CREATE TABLE `draft__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `draft__ww-user`
--

INSERT INTO `draft__ww-user` (`id`, `name`, `creator`, `created`, `modificator`, `modified`, `content_key`, `last-name@string#value`, `first-name@string#value`, `connection@connexion#id`) VALUES
(1, 'Administrateur', 1, '2024-04-04 14:58:34', 1, '2024-04-04 14:58:34', 1, 'WoodWiccan', 'Administrateur', 3);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__boolean`
--

CREATE TABLE `ingredient__boolean` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` bit(1) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__boolean`
--

INSERT INTO `ingredient__boolean` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 8, 'test_boolean', b'1', 0),
(58, 168, 'test bool', b'1', 100),
(70, 797, 'test_boolean', b'1', 400);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__datetime`
--

CREATE TABLE `ingredient__datetime` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` datetime DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__datetime`
--

INSERT INTO `ingredient__datetime` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(51, 7, 'testxx', '2024-06-06 07:09:00', 1900),
(62, 796, 'testxx', '2024-06-06 07:09:00', 400);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__float`
--

CREATE TABLE `ingredient__float` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` float DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__float`
--

INSERT INTO `ingredient__float` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 68, 'test float', 12.4344, 0),
(2, 68, 'test float', 13.333, 0),
(63, 75, 'test float', 12.2222, 800),
(64, 75, 'test float', 13.333, 700),
(69, 97, 'test float', 12.2222, 900),
(70, 97, 'test float', 13.333, 800),
(101, 7, 'test float', 12.2222, 2100),
(102, 7, 'test float', 13.333, 2000),
(105, 796, 'test float', 12.2222, 600),
(106, 796, 'test float', 13.333, 500);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__integer`
--

CREATE TABLE `ingredient__integer` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__integer`
--

INSERT INTO `ingredient__integer` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(2, 68, 'user__connexion', 2, 0),
(3, 4, 'user__connexion', 1, 0),
(4, 5, 'user__profile', 1, 0),
(5, 8, 'user__profile', 1, 0),
(6, 8, 'user__profile', 2, 0),
(53, 75, 'user__connexion', 2, 500),
(56, 97, 'user__connexion', 2, 500),
(72, 7, 'user__connexion', 2, 1800),
(89, 168, 'id test 2', 7, 200),
(108, 228, 'id to remove', 7, 300),
(116, 223, 'id to remove', 7, 400),
(117, 371, 'id to remove', 7, 400),
(178, 374, 'integer', 7, 300),
(180, 378, 'integer', 7, 300),
(181, 385, 'integer', 7, 300),
(182, 390, 'integer', 7, 300),
(183, 214, 'integer', 7, 300),
(185, 395, 'integer', 7, 600),
(186, 401, 'identifier', 0, 0),
(187, 402, 'identifier', 0, 0),
(188, 403, 'identifier', 0, 0),
(189, 404, 'identifier', 0, 0),
(226, 796, 'user__connexion', 2, 300),
(227, 797, 'user__profile', 1, 300),
(228, 797, 'user__profile', 2, 100),
(229, 798, 'user__profile', 1, 100);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__price`
--

CREATE TABLE `ingredient__price` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__string`
--

CREATE TABLE `ingredient__string` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(511) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__string`
--

INSERT INTO `ingredient__string` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 4, 'last-name', 'WoodWiccan', 0),
(2, 4, 'fist-name', 'Administrateur', 0),
(113, 7, 'fist-name', 'Jean', 2300),
(114, 7, 'last-name', 'Gromard', 2200),
(1225, 796, 'first-name', 'Jean', 800),
(1226, 796, 'last-name', 'Gromard', 700),
(1227, 799, 'title', 'titre', 300),
(1228, 799, 'headline', 'chapeau', 200);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__text`
--

CREATE TABLE `ingredient__text` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__text`
--

INSERT INTO `ingredient__text` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 8, 'text', 'htdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfx', 0),
(44, 228, 'content to remove', '<p><strong>content</strong><br></p>', 100),
(52, 223, 'content to remove', '<p><strong>content</strong><br></p>', 200),
(53, 371, 'content to remove', '<p><strong>content</strong><br></p>', 200),
(115, 374, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(116, 378, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(117, 385, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(118, 390, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(119, 214, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(121, 395, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(128, 797, 'text', 'htdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfx', 200),
(129, 799, 'body', '<p>ceci est un texte quelconque</p>', 100);

-- --------------------------------------------------------

--
-- Structure de la table `user__connexion`
--

CREATE TABLE `user__connexion` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'user signature',
  `email` varchar(511) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `pass_hash` varchar(255) DEFAULT NULL,
  `craft_table` varchar(255) DEFAULT NULL,
  `craft_attribute` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'connexion',
  `craft_attribute_var` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'fk_user__connexion',
  `attribute_name` varchar(511) DEFAULT NULL,
  `modifier` int(11) DEFAULT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__connexion`
--

INSERT INTO `user__connexion` (`id`, `name`, `email`, `login`, `pass_hash`, `craft_table`, `craft_attribute`, `craft_attribute_var`, `attribute_name`, `modifier`, `modified`, `creator`, `created`) VALUES
(1, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'content__ww-user', 'connexion', 'id', 'connection', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(2, 'Jean', 'jean.de.gromard@gmail.com', 'jean', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'content__ww-user', 'connexion', 'id', 'connection', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(3, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'draft__ww-user', 'connexion', 'id', 'connection', 1, '2024-04-04 14:58:34', 1, '2024-04-04 14:58:34'),
(4, 'admin', 'admin@nimp.fr', 'bbb', '$2y$10$s3sYPL.8Fukd5gPT49aQGOmddgohaqQC6wGKbRamnhUGN8pwtWU9K', 'content__test', 'connexion', 'id', 'nouvel-attribut-connexion', 1, '2024-10-09 16:22:38', 1, '2024-10-09 16:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `user__policy`
--

CREATE TABLE `user__policy` (
  `id` int(10) UNSIGNED NOT NULL,
  `fk_profile` int(10) UNSIGNED DEFAULT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'view',
  `status` int(10) UNSIGNED DEFAULT NULL,
  `fk_witch` int(10) UNSIGNED DEFAULT NULL,
  `position_ancestors` tinyint(1) NOT NULL DEFAULT 0,
  `position_included` tinyint(1) NOT NULL DEFAULT 1,
  `position_descendants` tinyint(1) NOT NULL DEFAULT 1,
  `custom_limitation` varchar(31) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__policy`
--

INSERT INTO `user__policy` (`id`, `fk_profile`, `module`, `status`, `fk_witch`, `position_ancestors`, `position_included`, `position_descendants`, `custom_limitation`) VALUES
(1, 1, '*', NULL, NULL, 0, 0, 0, ''),
(2, 2, '404', NULL, NULL, 0, 0, 0, ''),
(3, 2, '403', NULL, NULL, 0, 0, 0, ''),
(4, 2, 'login', NULL, NULL, 0, 0, 0, ''),
(5, 3, '*', 4, 5, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `user__profile`
--

CREATE TABLE `user__profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `site` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '*',
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__profile`
--

INSERT INTO `user__profile` (`id`, `name`, `site`, `created`) VALUES
(1, 'administrator', '*', '2024-03-01 15:46:01'),
(2, 'public', '*', '2024-03-01 15:46:01'),
(3, 'admin for admin', '*', '2025-05-09 12:29:44');

-- --------------------------------------------------------

--
-- Structure de la table `user__rel__connexion__profile`
--

CREATE TABLE `user__rel__connexion__profile` (
  `fk_connexion` int(11) NOT NULL,
  `fk_profile` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `user__rel__connexion__profile`
--

INSERT INTO `user__rel__connexion__profile` (`fk_connexion`, `fk_profile`, `created`) VALUES
(1, 1, '2024-03-01 15:46:01'),
(2, 1, '2024-03-01 15:46:01'),
(3, 1, '2024-04-04 14:58:34'),
(4, 2, '2024-10-09 16:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `witch`
--

CREATE TABLE `witch` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `site` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `url` varchar(1023) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `invoke` varchar(511) DEFAULT NULL,
  `cauldron` int(10) UNSIGNED DEFAULT NULL,
  `cauldron_priority` int(11) NOT NULL DEFAULT 0,
  `craft_table` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `craft_fk` int(10) UNSIGNED DEFAULT NULL,
  `alias` int(11) DEFAULT NULL,
  `is_main` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `context` varchar(255) DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `priority` int(11) NOT NULL DEFAULT 0,
  `level_1` int(10) UNSIGNED DEFAULT NULL,
  `level_2` int(10) UNSIGNED DEFAULT NULL,
  `level_3` int(10) UNSIGNED DEFAULT NULL,
  `level_4` int(10) UNSIGNED DEFAULT NULL,
  `level_5` int(10) UNSIGNED DEFAULT NULL,
  `level_6` int(10) UNSIGNED DEFAULT NULL,
  `level_7` int(10) UNSIGNED DEFAULT NULL,
  `level_8` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `witch`
--

INSERT INTO `witch` (`id`, `name`, `data`, `site`, `url`, `status`, `invoke`, `cauldron`, `cauldron_priority`, `craft_table`, `craft_fk`, `alias`, `is_main`, `context`, `datetime`, `priority`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`, `level_8`) VALUES
(1, 'Root', 'Ici se trouve la racine de la plateforme. C\'est à partir d\'ici que sont créées les homes de chaque site de la plateforme.', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Admin WoodWiccan', 'Site d\'administration', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Utilisateurs', '', 'admin', 'utilisateurs', 0, '', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', -300, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Administrateur', '', 'admin', 'utilisateurs/administrateur', 0, '', 4, 200, 'content__ww-user', 1, NULL, 1, '', '2024-03-01 15:46:01', 0, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(5, 'Home', '', 'admin', '', 0, 'root', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', -400, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Login', 'Module de déconnexion/connexion', 'admin', 'login', 0, 'login', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 800, 1, 2, 1, NULL, NULL, NULL, NULL, NULL),
(7, 'Witch', 'Visualisation des Witches, c\'est a dire de chaque point de l\'arborescence -appelé ici Matriarcat. Chacun de ces points peut être associé à un contenu et/ou à un module exécutable. \r\nOn peut également définir une URL permettant de cibler cette witch.', 'admin', 'view', 0, 'witch/view', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 700, 1, 2, 2, NULL, NULL, NULL, NULL, NULL),
(8, 'Edit Witch', '', 'admin', 'edit', 0, 'witch/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 500, 1, 2, 3, NULL, NULL, NULL, NULL, NULL),
(9, 'Edit Craft', 'This is the draft of craft, you can publish it, save it for later, or remove draft to cancel modification.', 'admin', 'edit-content', 0, 'contents/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, 2, 4, NULL, NULL, NULL, NULL, NULL),
(10, 'Menu', '', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 200, 1, 2, 5, NULL, NULL, NULL, NULL, NULL),
(11, 'Profiles', 'Permissions handeling is based on user profiles.', 'admin', 'profiles', 0, 'profiles', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 600, 1, 2, 5, 1, NULL, NULL, NULL, NULL),
(12, 'Craft Structures', '', 'admin', 'structures-old', 0, 'structures', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, 2, 5, 2, NULL, NULL, NULL, NULL),
(13, 'Apply', '', 'admin', 'apply', 0, 'emptyCache', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:28', 300, 1, 2, 5, 3, NULL, NULL, NULL, NULL),
(15, 'Recipes', 'Les données sont stockées sous la forme de structures qui sont éditables ici.', 'admin', 'recipe', 0, 'recipe/list', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-09 15:48:02', 500, 1, 2, 5, 4, NULL, NULL, NULL, NULL),
(16, 'Cauldron', '', 'admin', 'cauldron', 0, 'cauldron', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-04-08 15:04:18', 600, 1, 2, 9, NULL, NULL, NULL, NULL, NULL),
(25, 'View Structure', 'Cauldron\'s inside element\'s structure visualization', 'admin', 'recipe/view', 0, 'recipe/view', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-06-11 13:57:05', 0, 1, 2, 5, 4, 1, NULL, NULL, NULL),
(26, 'Edit Structure', '', 'admin', 'recipe/edit', 0, 'recipe/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-06-13 13:54:51', 0, 1, 2, 5, 4, 2, NULL, NULL, NULL),
(27, 'Create Structure', '', 'admin', 'recipe/create', 0, 'recipe/create', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-08-07 15:05:40', 0, 1, 2, 5, 4, 3, NULL, NULL, NULL),
(30, 'Create Witch', '', 'admin', 'create-witch', 0, 'witch/create', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-09-24 21:02:27', 300, 1, 2, 7, NULL, NULL, NULL, NULL, NULL),
(37, 'Witch', 'Witch Folder', 'admin', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-10-01 13:59:43', 100, 1, 2, 8, NULL, NULL, NULL, NULL, NULL),
(38, 'clipboard', NULL, 'admin', 'clipboard', 0, 'witch/clipboard', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-10-01 15:24:28', 0, 1, 2, 8, 1, NULL, NULL, NULL, NULL),
(127, 'Cauldrons', NULL, 'admin', 'cauldrons', 0, 'cauldrons', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-11-26 15:11:01', 200, 1, 2, 5, 5, NULL, NULL, NULL, NULL),
(129, 'Jean', '', 'admin', NULL, 0, NULL, 7, 0, NULL, NULL, NULL, 1, NULL, '2025-01-10 16:01:18', 0, 1, 1, 2, NULL, NULL, NULL, NULL, NULL),
(176, 'vrac', NULL, 'admin', NULL, 0, '', 799, 0, NULL, NULL, NULL, 1, NULL, '2025-05-15 13:28:51', 0, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archive__test`
--
ALTER TABLE `archive__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `archive__ww-user`
--
ALTER TABLE `archive__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cauldron`
--
ALTER TABLE `cauldron`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_level_1` (`level_1`),
  ADD KEY `target` (`target`),
  ADD KEY `IDX_level_2` (`level_2`),
  ADD KEY `IDX_level_3` (`level_3`),
  ADD KEY `IDX_level_4` (`level_4`),
  ADD KEY `IDX_level_5` (`level_5`),
  ADD KEY `IDX_level_6` (`level_6`),
  ADD KEY `IDX_level_7` (`level_7`),
  ADD KEY `IDX_level_8` (`level_8`),
  ADD KEY `IDX_level_9` (`level_9`);

--
-- Index pour la table `content__test`
--
ALTER TABLE `content__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `content__ww-user`
--
ALTER TABLE `content__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `draft__test`
--
ALTER TABLE `draft__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `draft__ww-user`
--
ALTER TABLE `draft__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__datetime`
--
ALTER TABLE `ingredient__datetime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__float`
--
ALTER TABLE `ingredient__float`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__integer`
--
ALTER TABLE `ingredient__integer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__policy`
--
ALTER TABLE `user__policy`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__profile`
--
ALTER TABLE `user__profile`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__rel__connexion__profile`
--
ALTER TABLE `user__rel__connexion__profile`
  ADD PRIMARY KEY (`fk_connexion`,`fk_profile`);

--
-- Index pour la table `witch`
--
ALTER TABLE `witch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_level_1` (`level_1`),
  ADD KEY `IDX_level_2` (`level_2`),
  ADD KEY `IDX_level_3` (`level_3`),
  ADD KEY `IDX_level_4` (`level_4`),
  ADD KEY `IDX_level_5` (`level_5`),
  ADD KEY `IDX_level_6` (`level_6`),
  ADD KEY `IDX_level_7` (`level_7`),
  ADD KEY `IDX_level_8` (`level_8`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archive__test`
--
ALTER TABLE `archive__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `archive__ww-user`
--
ALTER TABLE `archive__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cauldron`
--
ALTER TABLE `cauldron`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=800;

--
-- AUTO_INCREMENT pour la table `content__test`
--
ALTER TABLE `content__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `content__ww-user`
--
ALTER TABLE `content__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `draft__test`
--
ALTER TABLE `draft__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `draft__ww-user`
--
ALTER TABLE `draft__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `ingredient__datetime`
--
ALTER TABLE `ingredient__datetime`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `ingredient__float`
--
ALTER TABLE `ingredient__float`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `ingredient__integer`
--
ALTER TABLE `ingredient__integer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1229;

--
-- AUTO_INCREMENT pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user__policy`
--
ALTER TABLE `user__policy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user__profile`
--
ALTER TABLE `user__profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `witch`
--
ALTER TABLE `witch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;
COMMIT;
-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 19 mai 2025 à 09:28
-- Version du serveur : 11.7.2-MariaDB-ubu2404
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `woodwiccan`
--

-- --------------------------------------------------------

--
-- Structure de la table `archive__test`
--

CREATE TABLE `archive__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `nouvel-attribut-boolean__archive__archive@boolean#value` int(11) DEFAULT NULL,
  `nouvel-attribut-integer__archive__archive@integer#value` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime__archive__archive@datetime#value` datetime DEFAULT NULL,
  `nouvel-attribut-string__archive__archive@string#value` varchar(511) DEFAULT NULL,
  `nouvel-attribut-text__archive__archive@text#value` text DEFAULT NULL,
  `nouvel-attribut-decimal__archive__archive@decimal#value` decimal(10,2) DEFAULT NULL,
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `archive__ww-user`
--

CREATE TABLE `archive__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cauldron`
--

CREATE TABLE `cauldron` (
  `id` int(10) UNSIGNED NOT NULL,
  `target` int(10) UNSIGNED DEFAULT NULL,
  `status` bit(1) DEFAULT NULL COMMENT 'Null for content, 0 for draft, 1 for archive',
  `name` varchar(255) DEFAULT NULL,
  `recipe` varchar(128) NOT NULL DEFAULT 'folder',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `priority` int(11) NOT NULL DEFAULT 0,
  `creator` int(10) UNSIGNED DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `modificator` int(10) UNSIGNED DEFAULT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `level_1` int(11) DEFAULT NULL,
  `level_2` int(10) UNSIGNED DEFAULT NULL,
  `level_3` int(10) UNSIGNED DEFAULT NULL,
  `level_4` int(10) UNSIGNED DEFAULT NULL,
  `level_5` int(10) UNSIGNED DEFAULT NULL,
  `level_6` int(10) UNSIGNED DEFAULT NULL,
  `level_7` int(10) UNSIGNED DEFAULT NULL,
  `level_8` int(10) UNSIGNED DEFAULT NULL,
  `level_9` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cauldron`
--

INSERT INTO `cauldron` (`id`, `target`, `status`, `name`, `recipe`, `data`, `priority`, `creator`, `created`, `modificator`, `modified`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`, `level_8`, `level_9`) VALUES
(1, NULL, NULL, 'ROOT', 'root', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 15:08:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'admin', 'ww-site-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:13:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'ww-user', 'ww-recipe-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:15:12', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Administrateur', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:14', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:30', 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(7, NULL, NULL, 'Jean', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:44', 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-28 10:03:52', 1, 1, 2, 2, NULL, NULL, NULL, NULL, NULL),
(149, NULL, NULL, 'folder', 'ww-recipe-folder', NULL, 0, NULL, '2024-10-08 15:37:41', NULL, '2024-11-27 16:15:09', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(408, NULL, NULL, 'identifier', 'ww-recipe-folder', NULL, 0, NULL, '2025-01-10 16:21:37', NULL, '2025-01-10 16:21:37', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(770, NULL, NULL, 'link', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-06 17:17:29', NULL, '2025-03-06 17:17:29', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(773, NULL, NULL, 'ww-file', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-10 09:19:15', NULL, '2025-03-10 09:19:15', 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(795, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-05-07 16:35:06', NULL, '2025-05-07 16:35:06', 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL),
(796, 7, b'0', 'Jean', 'ww-user', NULL, 0, NULL, '2025-05-07 16:42:33', NULL, '2025-05-07 16:42:33', 1, 1, 2, 3, 1, NULL, NULL, NULL, NULL),
(797, NULL, NULL, 'inner contents', 'folder', NULL, 0, NULL, '2025-05-07 16:42:33', NULL, '2025-05-07 16:43:30', 1, 1, 2, 3, 1, 1, NULL, NULL, NULL),
(798, NULL, NULL, 'profiles', 'ww-profiles-array', NULL, 100, NULL, '2025-05-07 16:43:07', NULL, '2025-05-07 16:43:07', 1, 1, 2, 3, 1, 2, NULL, NULL, NULL),
(799, NULL, NULL, 'vrac', 'folder', NULL, 0, NULL, '2025-05-15 13:28:51', NULL, '2025-05-15 13:30:50', 1, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `content__test`
--

CREATE TABLE `content__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-connexion@connexion#id` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime@datetime#value` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `content__ww-user`
--

CREATE TABLE `content__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `content__ww-user`
--

INSERT INTO `content__ww-user` (`id`, `name`, `creator`, `created`, `modificator`, `modified`, `last-name@string#value`, `first-name@string#value`, `connection@connexion#id`) VALUES
(1, 'Administrateur', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01', 'WoodWiccan', 'Administrateur', 1);

-- --------------------------------------------------------

--
-- Structure de la table `draft__test`
--

CREATE TABLE `draft__test` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `nouvel-attribut-link@link#href` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#text` varchar(511) DEFAULT NULL,
  `nouvel-attribut-link@link#external` tinyint(1) DEFAULT 1,
  `nouvel-attribut-file@file#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-file@file#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#file` varchar(511) DEFAULT NULL,
  `nouvel-attribut-image@image#title` varchar(511) DEFAULT NULL,
  `nouvel-attribut-connexion@connexion#id` int(11) DEFAULT NULL,
  `nouvel-attribut-datetime@datetime#value` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `draft__ww-user`
--

CREATE TABLE `draft__ww-user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modificator` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content_key` int(11) DEFAULT NULL,
  `last-name@string#value` varchar(511) DEFAULT NULL,
  `first-name@string#value` varchar(511) DEFAULT NULL,
  `connection@connexion#id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `draft__ww-user`
--

INSERT INTO `draft__ww-user` (`id`, `name`, `creator`, `created`, `modificator`, `modified`, `content_key`, `last-name@string#value`, `first-name@string#value`, `connection@connexion#id`) VALUES
(1, 'Administrateur', 1, '2024-04-04 14:58:34', 1, '2024-04-04 14:58:34', 1, 'WoodWiccan', 'Administrateur', 3);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__boolean`
--

CREATE TABLE `ingredient__boolean` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` bit(1) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__boolean`
--

INSERT INTO `ingredient__boolean` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 8, 'test_boolean', b'1', 0),
(58, 168, 'test bool', b'1', 100),
(70, 797, 'test_boolean', b'1', 400);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__datetime`
--

CREATE TABLE `ingredient__datetime` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` datetime DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__datetime`
--

INSERT INTO `ingredient__datetime` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(51, 7, 'testxx', '2024-06-06 07:09:00', 1900),
(62, 796, 'testxx', '2024-06-06 07:09:00', 400);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__float`
--

CREATE TABLE `ingredient__float` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` float DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__float`
--

INSERT INTO `ingredient__float` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 68, 'test float', 12.4344, 0),
(2, 68, 'test float', 13.333, 0),
(63, 75, 'test float', 12.2222, 800),
(64, 75, 'test float', 13.333, 700),
(69, 97, 'test float', 12.2222, 900),
(70, 97, 'test float', 13.333, 800),
(101, 7, 'test float', 12.2222, 2100),
(102, 7, 'test float', 13.333, 2000),
(105, 796, 'test float', 12.2222, 600),
(106, 796, 'test float', 13.333, 500);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__integer`
--

CREATE TABLE `ingredient__integer` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__integer`
--

INSERT INTO `ingredient__integer` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(2, 68, 'user__connexion', 2, 0),
(3, 4, 'user__connexion', 1, 0),
(4, 5, 'user__profile', 1, 0),
(5, 8, 'user__profile', 1, 0),
(6, 8, 'user__profile', 2, 0),
(53, 75, 'user__connexion', 2, 500),
(56, 97, 'user__connexion', 2, 500),
(72, 7, 'user__connexion', 2, 1800),
(89, 168, 'id test 2', 7, 200),
(108, 228, 'id to remove', 7, 300),
(116, 223, 'id to remove', 7, 400),
(117, 371, 'id to remove', 7, 400),
(178, 374, 'integer', 7, 300),
(180, 378, 'integer', 7, 300),
(181, 385, 'integer', 7, 300),
(182, 390, 'integer', 7, 300),
(183, 214, 'integer', 7, 300),
(185, 395, 'integer', 7, 600),
(186, 401, 'identifier', 0, 0),
(187, 402, 'identifier', 0, 0),
(188, 403, 'identifier', 0, 0),
(189, 404, 'identifier', 0, 0),
(226, 796, 'user__connexion', 2, 300),
(227, 797, 'user__profile', 1, 300),
(228, 797, 'user__profile', 2, 100),
(229, 798, 'user__profile', 1, 100);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__price`
--

CREATE TABLE `ingredient__price` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` decimal(10,2) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__string`
--

CREATE TABLE `ingredient__string` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(511) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__string`
--

INSERT INTO `ingredient__string` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 4, 'last-name', 'WoodWiccan', 0),
(2, 4, 'fist-name', 'Administrateur', 0),
(113, 7, 'fist-name', 'Jean', 2300),
(114, 7, 'last-name', 'Gromard', 2200),
(1225, 796, 'first-name', 'Jean', 800),
(1226, 796, 'last-name', 'Gromard', 700),
(1227, 799, 'title', 'titre', 300),
(1228, 799, 'headline', 'chapeau', 200);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient__text`
--

CREATE TABLE `ingredient__text` (
  `id` int(10) UNSIGNED NOT NULL,
  `cauldron_fk` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredient__text`
--

INSERT INTO `ingredient__text` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(1, 8, 'text', 'htdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfx', 0),
(44, 228, 'content to remove', '<p><strong>content</strong><br></p>', 100),
(52, 223, 'content to remove', '<p><strong>content</strong><br></p>', 200),
(53, 371, 'content to remove', '<p><strong>content</strong><br></p>', 200),
(115, 374, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(116, 378, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(117, 385, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(118, 390, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(119, 214, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(121, 395, 'text', '<p><strong>contentwwwxcwx</strong><br></p>', 400),
(128, 797, 'text', 'htdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfxhtdyj yjt tr hgfs fdsu tjysfx', 200),
(129, 799, 'body', '<p>ceci est un texte quelconque</p>', 100);

-- --------------------------------------------------------

--
-- Structure de la table `user__connexion`
--

CREATE TABLE `user__connexion` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT 'user signature',
  `email` varchar(511) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `pass_hash` varchar(255) DEFAULT NULL,
  `craft_table` varchar(255) DEFAULT NULL,
  `craft_attribute` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'connexion',
  `craft_attribute_var` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'fk_user__connexion',
  `attribute_name` varchar(511) DEFAULT NULL,
  `modifier` int(11) DEFAULT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__connexion`
--

INSERT INTO `user__connexion` (`id`, `name`, `email`, `login`, `pass_hash`, `craft_table`, `craft_attribute`, `craft_attribute_var`, `attribute_name`, `modifier`, `modified`, `creator`, `created`) VALUES
(1, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'content__ww-user', 'connexion', 'id', 'connection', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(2, 'Jean', 'jean.de.gromard@gmail.com', 'jean', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'content__ww-user', 'connexion', 'id', 'connection', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(3, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 'draft__ww-user', 'connexion', 'id', 'connection', 1, '2024-04-04 14:58:34', 1, '2024-04-04 14:58:34'),
(4, 'admin', 'admin@nimp.fr', 'bbb', '$2y$10$s3sYPL.8Fukd5gPT49aQGOmddgohaqQC6wGKbRamnhUGN8pwtWU9K', 'content__test', 'connexion', 'id', 'nouvel-attribut-connexion', 1, '2024-10-09 16:22:38', 1, '2024-10-09 16:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `user__policy`
--

CREATE TABLE `user__policy` (
  `id` int(10) UNSIGNED NOT NULL,
  `fk_profile` int(10) UNSIGNED DEFAULT NULL,
  `module` varchar(255) NOT NULL DEFAULT 'view',
  `status` int(10) UNSIGNED DEFAULT NULL,
  `fk_witch` int(10) UNSIGNED DEFAULT NULL,
  `position_ancestors` tinyint(1) NOT NULL DEFAULT 0,
  `position_included` tinyint(1) NOT NULL DEFAULT 1,
  `position_descendants` tinyint(1) NOT NULL DEFAULT 1,
  `custom_limitation` varchar(31) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__policy`
--

INSERT INTO `user__policy` (`id`, `fk_profile`, `module`, `status`, `fk_witch`, `position_ancestors`, `position_included`, `position_descendants`, `custom_limitation`) VALUES
(1, 1, '*', NULL, NULL, 0, 0, 0, ''),
(2, 2, '404', NULL, NULL, 0, 0, 0, ''),
(3, 2, '403', NULL, NULL, 0, 0, 0, ''),
(4, 2, 'login', NULL, NULL, 0, 0, 0, ''),
(5, 3, '*', 4, 5, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `user__profile`
--

CREATE TABLE `user__profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `site` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '*',
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__profile`
--

INSERT INTO `user__profile` (`id`, `name`, `site`, `created`) VALUES
(1, 'administrator', '*', '2024-03-01 15:46:01'),
(2, 'public', '*', '2024-03-01 15:46:01'),
(3, 'admin for admin', '*', '2025-05-09 12:29:44');

-- --------------------------------------------------------

--
-- Structure de la table `user__rel__connexion__profile`
--

CREATE TABLE `user__rel__connexion__profile` (
  `fk_connexion` int(11) NOT NULL,
  `fk_profile` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `user__rel__connexion__profile`
--

INSERT INTO `user__rel__connexion__profile` (`fk_connexion`, `fk_profile`, `created`) VALUES
(1, 1, '2024-03-01 15:46:01'),
(2, 1, '2024-03-01 15:46:01'),
(3, 1, '2024-04-04 14:58:34'),
(4, 2, '2024-10-09 16:22:38');

-- --------------------------------------------------------

--
-- Structure de la table `witch`
--

CREATE TABLE `witch` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `site` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `url` varchar(1023) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `invoke` varchar(511) DEFAULT NULL,
  `cauldron` int(10) UNSIGNED DEFAULT NULL,
  `cauldron_priority` int(11) NOT NULL DEFAULT 0,
  `craft_table` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `craft_fk` int(10) UNSIGNED DEFAULT NULL,
  `alias` int(11) DEFAULT NULL,
  `is_main` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `context` varchar(255) DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `priority` int(11) NOT NULL DEFAULT 0,
  `level_1` int(10) UNSIGNED DEFAULT NULL,
  `level_2` int(10) UNSIGNED DEFAULT NULL,
  `level_3` int(10) UNSIGNED DEFAULT NULL,
  `level_4` int(10) UNSIGNED DEFAULT NULL,
  `level_5` int(10) UNSIGNED DEFAULT NULL,
  `level_6` int(10) UNSIGNED DEFAULT NULL,
  `level_7` int(10) UNSIGNED DEFAULT NULL,
  `level_8` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `witch`
--

INSERT INTO `witch` (`id`, `name`, `data`, `site`, `url`, `status`, `invoke`, `cauldron`, `cauldron_priority`, `craft_table`, `craft_fk`, `alias`, `is_main`, `context`, `datetime`, `priority`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`, `level_8`) VALUES
(1, 'Root', 'Ici se trouve la racine de la plateforme. C\'est à partir d\'ici que sont créées les homes de chaque site de la plateforme.', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Admin WoodWiccan', 'Site d\'administration', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Utilisateurs', '', 'admin', 'utilisateurs', 0, '', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', -300, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Administrateur', '', 'admin', 'utilisateurs/administrateur', 0, '', 4, 200, 'content__ww-user', 1, NULL, 1, '', '2024-03-01 15:46:01', 0, 1, 1, 1, NULL, NULL, NULL, NULL, NULL),
(5, 'Home', '', 'admin', '', 0, 'root', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', -400, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Login', 'Module de déconnexion/connexion', 'admin', 'login', 0, 'login', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 800, 1, 2, 1, NULL, NULL, NULL, NULL, NULL),
(7, 'Witch', 'Visualisation des Witches, c\'est a dire de chaque point de l\'arborescence -appelé ici Matriarcat. Chacun de ces points peut être associé à un contenu et/ou à un module exécutable. \r\nOn peut également définir une URL permettant de cibler cette witch.', 'admin', 'view', 0, 'witch/view', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 700, 1, 2, 2, NULL, NULL, NULL, NULL, NULL),
(8, 'Edit Witch', '', 'admin', 'edit', 0, 'witch/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 500, 1, 2, 3, NULL, NULL, NULL, NULL, NULL),
(9, 'Edit Craft', 'This is the draft of craft, you can publish it, save it for later, or remove draft to cancel modification.', 'admin', 'edit-content', 0, 'contents/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, 2, 4, NULL, NULL, NULL, NULL, NULL),
(10, 'Menu', '', NULL, NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 200, 1, 2, 5, NULL, NULL, NULL, NULL, NULL),
(11, 'Profiles', 'Permissions handeling is based on user profiles.', 'admin', 'profiles', 0, 'profiles', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 600, 1, 2, 5, 1, NULL, NULL, NULL, NULL),
(12, 'Craft Structures', '', 'admin', 'structures-old', 0, 'structures', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:01', 400, 1, 2, 5, 2, NULL, NULL, NULL, NULL),
(13, 'Apply', '', 'admin', 'apply', 0, 'emptyCache', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-01 15:46:28', 300, 1, 2, 5, 3, NULL, NULL, NULL, NULL),
(15, 'Recipes', 'Les données sont stockées sous la forme de structures qui sont éditables ici.', 'admin', 'recipe', 0, 'recipe/list', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-03-09 15:48:02', 500, 1, 2, 5, 4, NULL, NULL, NULL, NULL),
(16, 'Cauldron', '', 'admin', 'cauldron', 0, 'cauldron', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-04-08 15:04:18', 600, 1, 2, 9, NULL, NULL, NULL, NULL, NULL),
(25, 'View Structure', 'Cauldron\'s inside element\'s structure visualization', 'admin', 'recipe/view', 0, 'recipe/view', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-06-11 13:57:05', 0, 1, 2, 5, 4, 1, NULL, NULL, NULL),
(26, 'Edit Structure', '', 'admin', 'recipe/edit', 0, 'recipe/edit', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-06-13 13:54:51', 0, 1, 2, 5, 4, 2, NULL, NULL, NULL),
(27, 'Create Structure', '', 'admin', 'recipe/create', 0, 'recipe/create', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-08-07 15:05:40', 0, 1, 2, 5, 4, 3, NULL, NULL, NULL),
(30, 'Create Witch', '', 'admin', 'create-witch', 0, 'witch/create', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-09-24 21:02:27', 300, 1, 2, 7, NULL, NULL, NULL, NULL, NULL),
(37, 'Witch', 'Witch Folder', 'admin', NULL, 0, NULL, NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-10-01 13:59:43', 100, 1, 2, 8, NULL, NULL, NULL, NULL, NULL),
(38, 'clipboard', NULL, 'admin', 'clipboard', 0, 'witch/clipboard', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-10-01 15:24:28', 0, 1, 2, 8, 1, NULL, NULL, NULL, NULL),
(127, 'Cauldrons', NULL, 'admin', 'cauldrons', 0, 'cauldrons', NULL, 0, NULL, NULL, NULL, 1, NULL, '2024-11-26 15:11:01', 200, 1, 2, 5, 5, NULL, NULL, NULL, NULL),
(129, 'Jean', '', 'admin', NULL, 0, NULL, 7, 0, NULL, NULL, NULL, 1, NULL, '2025-01-10 16:01:18', 0, 1, 1, 2, NULL, NULL, NULL, NULL, NULL),
(176, 'vrac', NULL, 'admin', NULL, 0, '', 799, 0, NULL, NULL, NULL, 1, NULL, '2025-05-15 13:28:51', 0, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `archive__test`
--
ALTER TABLE `archive__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `archive__ww-user`
--
ALTER TABLE `archive__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cauldron`
--
ALTER TABLE `cauldron`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_level_1` (`level_1`),
  ADD KEY `target` (`target`),
  ADD KEY `IDX_level_2` (`level_2`),
  ADD KEY `IDX_level_3` (`level_3`),
  ADD KEY `IDX_level_4` (`level_4`),
  ADD KEY `IDX_level_5` (`level_5`),
  ADD KEY `IDX_level_6` (`level_6`),
  ADD KEY `IDX_level_7` (`level_7`),
  ADD KEY `IDX_level_8` (`level_8`),
  ADD KEY `IDX_level_9` (`level_9`);

--
-- Index pour la table `content__test`
--
ALTER TABLE `content__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `content__ww-user`
--
ALTER TABLE `content__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `draft__test`
--
ALTER TABLE `draft__test`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `draft__ww-user`
--
ALTER TABLE `draft__ww-user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__datetime`
--
ALTER TABLE `ingredient__datetime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__float`
--
ALTER TABLE `ingredient__float`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__integer`
--
ALTER TABLE `ingredient__integer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CAULDRON` (`cauldron_fk`);

--
-- Index pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__policy`
--
ALTER TABLE `user__policy`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__profile`
--
ALTER TABLE `user__profile`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user__rel__connexion__profile`
--
ALTER TABLE `user__rel__connexion__profile`
  ADD PRIMARY KEY (`fk_connexion`,`fk_profile`);

--
-- Index pour la table `witch`
--
ALTER TABLE `witch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_level_1` (`level_1`),
  ADD KEY `IDX_level_2` (`level_2`),
  ADD KEY `IDX_level_3` (`level_3`),
  ADD KEY `IDX_level_4` (`level_4`),
  ADD KEY `IDX_level_5` (`level_5`),
  ADD KEY `IDX_level_6` (`level_6`),
  ADD KEY `IDX_level_7` (`level_7`),
  ADD KEY `IDX_level_8` (`level_8`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archive__test`
--
ALTER TABLE `archive__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `archive__ww-user`
--
ALTER TABLE `archive__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cauldron`
--
ALTER TABLE `cauldron`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=800;

--
-- AUTO_INCREMENT pour la table `content__test`
--
ALTER TABLE `content__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `content__ww-user`
--
ALTER TABLE `content__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `draft__test`
--
ALTER TABLE `draft__test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `draft__ww-user`
--
ALTER TABLE `draft__ww-user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `ingredient__datetime`
--
ALTER TABLE `ingredient__datetime`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT pour la table `ingredient__float`
--
ALTER TABLE `ingredient__float`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `ingredient__integer`
--
ALTER TABLE `ingredient__integer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1229;

--
-- AUTO_INCREMENT pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user__policy`
--
ALTER TABLE `user__policy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user__profile`
--
ALTER TABLE `user__profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `witch`
--
ALTER TABLE `witch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;
COMMIT;
