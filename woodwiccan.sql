-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 13 août 2025 à 16:15
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
  `level_7` int(11) UNSIGNED DEFAULT NULL,
  `level_8` int(11) UNSIGNED DEFAULT NULL,
  `level_9` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cauldron`
--

INSERT INTO `cauldron` (`id`, `target`, `status`, `name`, `recipe`, `data`, `priority`, `creator`, `created`, `modificator`, `modified`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`, `level_8`, `level_9`) VALUES
(1, NULL, NULL, 'ROOT', 'root', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 15:08:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'admin', 'ww-site-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:13:42', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'ww-user', 'ww-recipe-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:15:12', 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Administrateur', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:14', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, 1, 1, NULL, NULL, NULL),
(7, NULL, NULL, 'Jean', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:44', 1, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, 1, 1, NULL, NULL, NULL),
(149, NULL, NULL, 'folder', 'ww-recipe-folder', NULL, 0, NULL, '2024-10-08 15:37:41', NULL, '2024-11-27 16:15:09', 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(408, NULL, NULL, 'identifier', 'ww-recipe-folder', NULL, 0, NULL, '2025-01-10 16:21:37', NULL, '2025-01-10 16:21:37', 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(770, NULL, NULL, 'link', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-06 17:17:29', NULL, '2025-03-06 17:17:29', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(773, NULL, NULL, 'ww-file', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-10 09:19:15', NULL, '2025-03-10 09:19:15', 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(795, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-05-07 16:35:06', NULL, '2025-05-07 16:35:06', 1, 1, 2, 3, NULL, NULL, NULL, NULL, NULL),
(800, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-06-12 10:42:37', NULL, '2025-06-12 10:42:37', 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL),
(802, NULL, NULL, 'connexion', 'ww-connexion', '{\"connector\":\"user__connexion\",\"table\":\"user__connexion\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:43:28', NULL, '2025-08-13 16:14:44', 1, 1, 1, 3, 2, 1, NULL, NULL, NULL),
(803, NULL, NULL, 'profiles', 'ww-profiles', '{\"connector\":\"ww-profiles\",\"table\":\"user__profiles\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:44:48', NULL, '2025-08-13 16:14:44', 1, 1, 1, 3, 2, 2, NULL, NULL, NULL),
(804, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-06-12 10:44:56', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, NULL, NULL, NULL, NULL, NULL),
(805, 4, b'1', 'Administrateur', 'ww-user', NULL, 0, NULL, '2025-06-12 10:44:56', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, 1, NULL, NULL, NULL, NULL),
(806, NULL, NULL, 'connexion', 'ww-connexion', '{\"connector\":\"user__connexion\",\"table\":\"user__connexion\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:47:16', NULL, '2025-06-12 10:48:21', 1, 1, 2, 5, NULL, NULL, NULL, NULL, NULL),
(807, NULL, NULL, 'profiles', 'ww-profiles', '{\"connector\":\"ww-profiles\",\"table\":\"user__profiles\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:48:11', NULL, '2025-06-12 10:48:21', 1, 1, 2, 6, NULL, NULL, NULL, NULL, NULL),
(808, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-06-12 10:48:21', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, NULL, NULL, NULL, NULL, NULL),
(809, 7, b'1', 'Jean', 'ww-user', NULL, 0, NULL, '2025-06-12 10:48:21', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, 1, NULL, NULL, NULL, NULL),
(935, NULL, NULL, 'demo-rubrique', 'ww-recipe-folder', NULL, 0, NULL, '2025-06-19 13:52:30', NULL, '2025-06-19 13:52:30', 1, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(946, NULL, NULL, 'demo-article', 'ww-recipe-folder', NULL, 0, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 15:17:13', 1, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1005, NULL, NULL, 'GourmetHaven', 'folder', NULL, 0, NULL, '2025-08-07 13:40:31', NULL, '2025-08-11 15:03:24', 1, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(1006, NULL, NULL, 'gh-hero', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:28:00', NULL, '2025-08-10 15:28:00', 1, 8, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1007, NULL, NULL, 'Home', 'gh-hero', NULL, 0, NULL, '2025-08-10 15:28:00', NULL, '2025-08-10 15:28:08', 1, 8, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1008, NULL, NULL, 'gh-menu', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:31:10', NULL, '2025-08-10 15:31:10', 1, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1009, NULL, NULL, 'Menu', 'gh-menu', NULL, 0, NULL, '2025-08-10 15:31:10', NULL, '2025-08-10 15:31:16', 1, 9, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1010, NULL, NULL, 'gh-page', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:33:50', NULL, '2025-08-10 15:33:50', 1, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1011, NULL, NULL, 'About', 'gh-page', NULL, 0, NULL, '2025-08-10 15:33:51', NULL, '2025-08-10 15:33:56', 1, 10, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1012, NULL, NULL, 'gh-testimonials', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:36:15', NULL, '2025-08-10 15:36:15', 1, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1013, NULL, NULL, 'Testimonials', 'gh-testimonials', NULL, 0, NULL, '2025-08-10 15:36:15', NULL, '2025-08-10 15:36:19', 1, 11, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1014, NULL, NULL, 'gh-contact', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:38:33', NULL, '2025-08-10 15:38:33', 1, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1015, NULL, NULL, 'Contact', 'gh-contact', NULL, 0, NULL, '2025-08-10 15:38:33', NULL, '2025-08-11 11:10:41', 1, 12, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1016, NULL, NULL, 'gh-newsletter', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-10 15:40:13', NULL, '2025-08-10 15:40:13', 1, 13, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1017, NULL, NULL, 'Newsletter', 'gh-newsletter', NULL, 0, NULL, '2025-08-10 15:40:13', NULL, '2025-08-11 10:37:06', 1, 13, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1018, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-10 16:19:29', NULL, '2025-08-10 16:19:29', 1, 8, 1, 1, NULL, NULL, NULL, NULL, NULL),
(1020, NULL, NULL, 'actions', 'folder', NULL, 100, NULL, '2025-08-10 16:23:01', NULL, '2025-08-11 14:25:37', 1, 8, 1, 2, 2, 1, NULL, NULL, NULL),
(1022, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-10 16:24:08', NULL, '2025-08-11 14:25:37', 1, 8, 1, 2, 2, 1, 1, NULL, NULL),
(1023, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-10 16:25:09', NULL, '2025-08-11 14:25:37', 1, 8, 1, 2, 2, 1, 2, NULL, NULL),
(1024, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-10 16:25:39', NULL, '2025-08-10 16:25:39', 1, 8, 1, 2, NULL, NULL, NULL, NULL, NULL),
(1025, 1007, b'1', 'Home', 'gh-hero', NULL, 0, NULL, '2025-08-10 16:25:39', NULL, '2025-08-10 16:25:39', 1, 8, 1, 2, 1, NULL, NULL, NULL, NULL),
(1026, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 07:17:31', NULL, '2025-08-11 07:17:31', 1, 9, 1, 1, NULL, NULL, NULL, NULL, NULL),
(1028, NULL, NULL, 'dishes', 'folder', NULL, 100, NULL, '2025-08-11 07:32:09', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, NULL, NULL, NULL),
(1029, NULL, NULL, 'dish', 'gh-dish', NULL, 100, NULL, '2025-08-11 07:32:28', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 1, NULL, NULL),
(1030, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:32:28', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 1, 1, NULL),
(1031, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:32:28', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 1, 1, 1),
(1032, NULL, NULL, 'dish', 'gh-dish', NULL, 100, NULL, '2025-08-11 07:36:15', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 2, NULL, NULL),
(1033, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:36:15', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 2, 1, NULL),
(1034, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:36:15', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, 1, 2, 1, 1),
(1035, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 07:39:51', NULL, '2025-08-11 07:39:51', 1, 9, 1, 2, NULL, NULL, NULL, NULL, NULL),
(1036, 1009, b'1', 'Menu', 'gh-menu', NULL, 0, NULL, '2025-08-11 07:39:51', NULL, '2025-08-11 07:39:51', 1, 9, 1, 2, 1, NULL, NULL, NULL, NULL),
(1037, NULL, NULL, 'gh-dish', 'ww-recipe-folder', NULL, 0, NULL, '2025-08-11 07:41:16', NULL, '2025-08-11 07:41:16', 1, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(1038, NULL, NULL, 'Prime Ribeye Steak', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:41:16', NULL, '2025-08-11 07:42:44', 1, 14, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(1039, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:41:16', NULL, '2025-08-11 07:41:16', 1, 14, 1, 1, NULL, NULL, NULL, NULL, NULL),
(1040, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:41:16', NULL, '2025-08-11 07:41:16', 1, 14, 1, 1, 1, NULL, NULL, NULL, NULL),
(1041, NULL, NULL, 'Truffle Mushroom Pasta', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:43:25', NULL, '2025-08-11 07:44:18', 1, 14, 2, NULL, NULL, NULL, NULL, NULL, NULL),
(1042, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:43:25', NULL, '2025-08-11 07:43:25', 1, 14, 2, 1, NULL, NULL, NULL, NULL, NULL),
(1043, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:43:25', NULL, '2025-08-11 07:43:25', 1, 14, 2, 1, 1, NULL, NULL, NULL, NULL),
(1044, 1009, b'1', 'Menu', 'gh-menu', NULL, 0, NULL, '2025-08-11 07:44:36', NULL, '2025-08-11 07:44:36', 1, 9, 1, 2, 2, NULL, NULL, NULL, NULL),
(1045, NULL, NULL, 'Pan-Seared Salmon', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:45:08', NULL, '2025-08-11 07:46:23', 1, 14, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(1046, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:45:08', NULL, '2025-08-11 07:45:08', 1, 14, 3, 1, NULL, NULL, NULL, NULL, NULL),
(1047, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:45:08', NULL, '2025-08-11 07:45:08', 1, 14, 3, 1, 1, NULL, NULL, NULL, NULL),
(1048, NULL, NULL, 'Harvest Salad', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:47:08', NULL, '2025-08-11 07:48:07', 1, 14, 4, NULL, NULL, NULL, NULL, NULL, NULL),
(1049, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:47:08', NULL, '2025-08-11 09:02:34', 1, 14, 4, 3, 1, 1, NULL, NULL, NULL),
(1050, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:47:08', NULL, '2025-08-11 09:02:34', 1, 14, 4, 3, 1, 1, 1, NULL, NULL),
(1051, NULL, NULL, 'Chocolate Soufflé', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:49:20', NULL, '2025-08-11 07:50:23', 1, 14, 5, NULL, NULL, NULL, NULL, NULL, NULL),
(1052, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:49:20', NULL, '2025-08-11 07:49:20', 1, 14, 5, 1, NULL, NULL, NULL, NULL, NULL),
(1053, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:49:20', NULL, '2025-08-11 07:49:20', 1, 14, 5, 1, 1, NULL, NULL, NULL, NULL),
(1054, NULL, NULL, 'Signature Cocktails', 'gh-dish', NULL, 0, NULL, '2025-08-11 07:50:56', NULL, '2025-08-11 07:52:08', 1, 14, 6, NULL, NULL, NULL, NULL, NULL, NULL),
(1055, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 07:50:56', NULL, '2025-08-11 07:50:56', 1, 14, 6, 1, NULL, NULL, NULL, NULL, NULL),
(1056, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 07:50:56', NULL, '2025-08-11 07:50:56', 1, 14, 6, 1, 1, NULL, NULL, NULL, NULL),
(1057, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 09:02:22', NULL, '2025-08-11 09:02:22', 1, 14, 4, 2, NULL, NULL, NULL, NULL, NULL),
(1058, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 09:02:33', NULL, '2025-08-11 09:02:33', 1, 14, 4, 3, NULL, NULL, NULL, NULL, NULL),
(1059, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 09:02:34', NULL, '2025-08-11 09:02:34', 1, 14, 4, 4, NULL, NULL, NULL, NULL, NULL),
(1060, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 09:02:34', NULL, '2025-08-11 09:02:34', 1, 14, 4, 4, 1, NULL, NULL, NULL, NULL),
(1061, 1048, b'1', 'Harvest Salad', 'gh-dish', NULL, 0, NULL, '2025-08-11 09:02:34', NULL, '2025-08-11 09:02:34', 1, 14, 4, 3, 1, NULL, NULL, NULL, NULL),
(1062, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 09:03:41', NULL, '2025-08-11 09:03:41', 1, 10, 1, 1, NULL, NULL, NULL, NULL, NULL),
(1064, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-08-11 09:03:59', NULL, '2025-08-11 09:29:39', 1, 10, 1, 2, 2, 1, NULL, NULL, NULL),
(1065, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 09:03:59', NULL, '2025-08-11 09:29:39', 1, 10, 1, 2, 2, 1, 1, NULL, NULL),
(1066, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 09:22:23', NULL, '2025-08-11 09:22:23', 1, 10, 1, 2, NULL, NULL, NULL, NULL, NULL),
(1067, 1011, b'1', 'About', 'gh-page', NULL, 0, NULL, '2025-08-11 09:22:23', NULL, '2025-08-11 09:22:23', 1, 10, 1, 2, 1, NULL, NULL, NULL, NULL),
(1069, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-08-11 09:26:05', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 1, NULL, NULL, NULL),
(1070, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 09:26:05', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 1, 1, NULL, NULL),
(1071, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:26:05', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 2, NULL, NULL, NULL),
(1072, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:27:24', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 3, NULL, NULL, NULL),
(1073, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:28:17', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 4, NULL, NULL, NULL),
(1074, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:28:33', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, 5, NULL, NULL, NULL),
(1075, 1011, b'1', 'About', 'gh-page', NULL, 0, NULL, '2025-08-11 09:29:39', NULL, '2025-08-11 09:29:39', 1, 10, 1, 2, 2, NULL, NULL, NULL, NULL),
(1076, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 1, NULL, NULL, NULL),
(1077, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 1, 1, NULL, NULL),
(1078, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 2, NULL, NULL, NULL),
(1079, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 3, NULL, NULL, NULL),
(1080, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 4, NULL, NULL, NULL),
(1081, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, 5, NULL, NULL, NULL),
(1082, 1011, b'1', 'About', 'gh-page', NULL, 0, NULL, '2025-08-11 09:35:40', NULL, '2025-08-11 09:35:40', 1, 10, 1, 2, 3, NULL, NULL, NULL, NULL),
(1083, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 09:55:51', NULL, '2025-08-11 09:55:51', 1, 11, 1, 1, NULL, NULL, NULL, NULL, NULL),
(1085, NULL, NULL, 'reviews', 'folder', NULL, 100, NULL, '2025-08-11 09:57:07', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, NULL, NULL, NULL),
(1086, NULL, NULL, 'Sarah J.', 'gh-review', NULL, 100, NULL, '2025-08-11 09:57:36', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 1, NULL, NULL),
(1087, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 09:57:36', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 1, 1, NULL),
(1088, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 09:57:36', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 1, 1, 1),
(1089, NULL, NULL, 'Michael T.', 'gh-review', NULL, 100, NULL, '2025-08-11 10:00:00', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 2, NULL, NULL),
(1090, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 10:00:00', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 2, 1, NULL),
(1091, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:00:00', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 2, 1, 1),
(1092, NULL, NULL, 'Jennifer L.', 'gh-review', NULL, 100, NULL, '2025-08-11 10:01:45', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 3, NULL, NULL),
(1093, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 10:01:45', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 3, 1, NULL),
(1094, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:01:45', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, 1, 3, 1, 1),
(1095, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 10:02:55', NULL, '2025-08-11 10:02:55', 1, 11, 1, 2, NULL, NULL, NULL, NULL, NULL),
(1096, 1013, b'1', 'Testimonials', 'gh-testimonials', NULL, 0, NULL, '2025-08-11 10:02:55', NULL, '2025-08-11 10:02:55', 1, 11, 1, 2, 1, NULL, NULL, NULL, NULL),
(1097, NULL, NULL, 'reviews', 'folder', NULL, 100, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, NULL, NULL, NULL, NULL, NULL),
(1098, NULL, NULL, 'Sarah J.', 'gh-review', NULL, 100, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 1, NULL, NULL, NULL, NULL),
(1099, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 1, 1, NULL, NULL, NULL),
(1100, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 1, 1, 1, NULL, NULL),
(1101, NULL, NULL, 'Michael T.', 'gh-review', NULL, 100, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 2, NULL, NULL, NULL, NULL),
(1102, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 2, 1, NULL, NULL, NULL),
(1103, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 2, 1, 1, NULL, NULL),
(1104, NULL, NULL, 'Jennifer L.', 'gh-review', NULL, 100, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 3, NULL, NULL, NULL, NULL),
(1105, NULL, NULL, 'photo', 'image', NULL, 400, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 3, 1, NULL, NULL, NULL),
(1106, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 4, 3, 1, 1, NULL, NULL),
(1107, 1013, b'1', 'Testimonials', 'gh-testimonials', NULL, 0, NULL, '2025-08-11 10:03:39', NULL, '2025-08-11 10:03:39', 1, 11, 1, 2, 2, NULL, NULL, NULL, NULL),
(1109, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 1, NULL, NULL, NULL),
(1110, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 1, 1, NULL, NULL),
(1111, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 2, NULL, NULL, NULL),
(1112, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 3, NULL, NULL, NULL),
(1113, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 4, NULL, NULL, NULL),
(1114, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:46:08', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, 5, NULL, NULL, NULL),
(1115, 1011, b'1', 'About', 'gh-page', NULL, 0, NULL, '2025-08-11 10:47:37', NULL, '2025-08-11 10:47:37', 1, 10, 1, 2, 4, NULL, NULL, NULL, NULL),
(1116, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 19, NULL, NULL, NULL, NULL, NULL),
(1117, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 19, 1, NULL, NULL, NULL, NULL),
(1118, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 20, NULL, NULL, NULL, NULL, NULL),
(1119, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 21, NULL, NULL, NULL, NULL, NULL),
(1120, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 22, NULL, NULL, NULL, NULL, NULL),
(1121, NULL, NULL, 'bullet', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 23, NULL, NULL, NULL, NULL, NULL),
(1122, 1011, b'1', 'About', 'gh-page', NULL, 0, NULL, '2025-08-11 10:54:56', NULL, '2025-08-11 10:54:56', 1, 10, 1, 2, 5, NULL, NULL, NULL, NULL),
(1123, NULL, NULL, 'bulletpoints', 'folder', NULL, 100, NULL, '2025-08-11 10:59:30', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, 1, NULL, NULL, NULL),
(1124, NULL, NULL, 'address', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 10:59:52', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, 1, 1, NULL, NULL),
(1125, NULL, NULL, 'phone', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:02:35', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, 1, 2, NULL, NULL),
(1126, NULL, NULL, 'email', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:04:00', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, 1, 3, NULL, NULL),
(1127, NULL, NULL, 'hours', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:06:17', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, 1, 4, NULL, NULL),
(1128, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 11:15:35', NULL, '2025-08-11 11:15:35', 1, 12, 1, 2, NULL, NULL, NULL, NULL, NULL),
(1129, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 11:15:48', NULL, '2025-08-11 11:15:48', 1, 12, 1, 3, NULL, NULL, NULL, NULL, NULL),
(1130, NULL, NULL, 'bulletpoints', 'folder', NULL, 100, NULL, '2025-08-11 11:15:48', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, 1, NULL, NULL, NULL),
(1131, NULL, NULL, 'address', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:15:48', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, 1, 1, NULL, NULL),
(1132, NULL, NULL, 'phone', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:15:49', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, 1, 2, NULL, NULL),
(1133, NULL, NULL, 'email', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:15:49', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, 1, 3, NULL, NULL),
(1134, NULL, NULL, 'hours', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:15:49', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, 1, 4, NULL, NULL),
(1135, 1015, b'1', 'Contact', 'gh-contact', NULL, 0, NULL, '2025-08-11 11:15:49', NULL, '2025-08-11 11:15:49', 1, 12, 1, 3, 1, NULL, NULL, NULL, NULL),
(1136, NULL, NULL, 'bulletpoints', 'folder', NULL, 100, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, 1, NULL, NULL, NULL),
(1137, NULL, NULL, 'address', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, 1, 1, NULL, NULL),
(1138, NULL, NULL, 'phone', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, 1, 2, NULL, NULL),
(1139, NULL, NULL, 'email', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, 1, 3, NULL, NULL),
(1140, NULL, NULL, 'hours', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, 1, 4, NULL, NULL),
(1141, 1015, b'1', 'Contact', 'gh-contact', NULL, 0, NULL, '2025-08-11 11:17:27', NULL, '2025-08-11 11:17:27', 1, 12, 1, 3, 2, NULL, NULL, NULL, NULL),
(1142, NULL, NULL, 'bulletpoints', 'folder', NULL, 100, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 6, NULL, NULL, NULL, NULL, NULL),
(1143, NULL, NULL, 'address', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 6, 1, NULL, NULL, NULL, NULL),
(1144, NULL, NULL, 'phone', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 6, 2, NULL, NULL, NULL, NULL),
(1145, NULL, NULL, 'email', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 6, 3, NULL, NULL, NULL, NULL),
(1146, NULL, NULL, 'hours', 'gh-bulletpoint', NULL, 100, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 6, 4, NULL, NULL, NULL, NULL),
(1147, 1015, b'1', 'Contact', 'gh-contact', NULL, 0, NULL, '2025-08-11 11:30:08', NULL, '2025-08-11 11:30:08', 1, 12, 1, 3, 3, NULL, NULL, NULL, NULL),
(1148, NULL, NULL, 'actions', 'folder', NULL, 100, NULL, '2025-08-11 14:25:37', NULL, '2025-08-11 17:27:48', 1, 8, 1, 2, 3, 1, NULL, NULL, NULL),
(1149, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-11 14:25:37', NULL, '2025-08-11 17:27:48', 1, 8, 1, 2, 3, 1, 1, NULL, NULL),
(1150, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-11 14:25:37', NULL, '2025-08-11 17:27:48', 1, 8, 1, 2, 3, 1, 2, NULL, NULL),
(1151, 1007, b'1', 'Home', 'gh-hero', NULL, 0, NULL, '2025-08-11 14:25:37', NULL, '2025-08-11 14:25:37', 1, 8, 1, 2, 2, NULL, NULL, NULL, NULL),
(1152, NULL, NULL, 'menu-call-to-action', 'link', NULL, 100, NULL, '2025-08-11 14:49:13', NULL, '2025-08-11 15:10:14', 1, 2, 2, 4, 1, 1, NULL, NULL, NULL),
(1153, NULL, NULL, 'footer', 'folder', NULL, 100, NULL, '2025-08-11 14:51:21', NULL, '2025-08-11 15:10:14', 1, 2, 2, 4, 1, 2, NULL, NULL, NULL),
(1154, NULL, NULL, 'hours', 'folder', NULL, 100, NULL, '2025-08-11 14:56:51', NULL, '2025-08-11 15:10:14', 1, 2, 2, 4, 1, 2, 1, NULL, NULL),
(1155, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-08-11 15:09:48', NULL, '2025-08-11 15:09:48', 1, 2, 2, 3, NULL, NULL, NULL, NULL, NULL),
(1156, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-08-11 15:10:14', NULL, '2025-08-11 15:10:14', 1, 2, 2, 4, NULL, NULL, NULL, NULL, NULL),
(1157, NULL, NULL, 'menu-call-to-action', 'link', NULL, 100, NULL, '2025-08-11 15:10:14', NULL, '2025-08-11 15:25:50', 1, 2, 2, 4, 2, 1, NULL, NULL, NULL),
(1158, NULL, NULL, 'footer', 'folder', NULL, 100, NULL, '2025-08-11 15:10:14', NULL, '2025-08-11 15:25:50', 1, 2, 2, 4, 2, 2, NULL, NULL, NULL),
(1159, NULL, NULL, 'hours', 'folder', NULL, 100, NULL, '2025-08-11 15:10:14', NULL, '2025-08-11 15:25:50', 1, 2, 2, 4, 2, 2, 1, NULL, NULL),
(1160, 1005, b'1', 'GourmetHaven', 'folder', NULL, 0, NULL, '2025-08-11 15:10:14', NULL, '2025-08-11 15:10:14', 1, 2, 2, 4, 1, NULL, NULL, NULL, NULL),
(1161, NULL, NULL, 'menu-call-to-action', 'link', NULL, 100, NULL, '2025-08-11 15:25:50', NULL, '2025-08-11 15:25:50', 1, 2, 2, 7, NULL, NULL, NULL, NULL, NULL),
(1162, NULL, NULL, 'footer', 'folder', NULL, 100, NULL, '2025-08-11 15:25:50', NULL, '2025-08-11 15:25:50', 1, 2, 2, 8, NULL, NULL, NULL, NULL, NULL),
(1163, NULL, NULL, 'hours', 'folder', NULL, 100, NULL, '2025-08-11 15:25:50', NULL, '2025-08-11 15:25:50', 1, 2, 2, 8, 1, NULL, NULL, NULL, NULL),
(1164, 1005, b'1', 'GourmetHaven', 'folder', NULL, 0, NULL, '2025-08-11 15:25:50', NULL, '2025-08-11 15:25:50', 1, 2, 2, 4, 2, NULL, NULL, NULL, NULL),
(1166, NULL, NULL, 'actions', 'folder', NULL, 100, NULL, '2025-08-11 17:27:23', NULL, '2025-08-11 17:27:48', 1, 8, 1, 5, NULL, NULL, NULL, NULL, NULL),
(1167, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-11 17:27:23', NULL, '2025-08-11 17:27:48', 1, 8, 1, 5, 1, NULL, NULL, NULL, NULL),
(1168, NULL, NULL, 'button', 'link', NULL, 100, NULL, '2025-08-11 17:27:23', NULL, '2025-08-11 17:27:48', 1, 8, 1, 5, 2, NULL, NULL, NULL, NULL),
(1169, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-08-11 17:27:23', NULL, '2025-08-11 17:27:48', 1, 8, 1, 6, NULL, NULL, NULL, NULL, NULL),
(1170, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-08-11 17:27:23', NULL, '2025-08-11 17:27:48', 1, 8, 1, 6, 1, NULL, NULL, NULL, NULL),
(1171, 1007, b'1', 'Home', 'gh-hero', NULL, 0, NULL, '2025-08-11 17:27:48', NULL, '2025-08-11 17:27:48', 1, 8, 1, 2, 3, NULL, NULL, NULL, NULL),
(1172, NULL, NULL, 'connexion', 'ww-connexion', '{\"connector\":\"user__connexion\",\"table\":\"user__connexion\",\"field\":\"id\"}', 100, NULL, '2025-08-13 16:14:44', NULL, '2025-08-13 16:14:44', 1, 1, 1, 6, NULL, NULL, NULL, NULL, NULL),
(1173, NULL, NULL, 'profiles', 'ww-profiles', '{\"connector\":\"ww-profiles\",\"table\":\"user__profiles\",\"field\":\"id\"}', 100, NULL, '2025-08-13 16:14:44', NULL, '2025-08-13 16:14:44', 1, 1, 1, 7, NULL, NULL, NULL, NULL, NULL),
(1174, 4, b'1', 'Administrateur', 'ww-user', NULL, 0, NULL, '2025-08-13 16:14:44', NULL, '2025-08-13 16:14:44', 1, 1, 1, 3, 2, NULL, NULL, NULL, NULL);

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
(122, 1022, 'external', b'0', 100),
(123, 1023, 'external', b'0', 100),
(124, 1149, 'external', b'0', 100),
(125, 1150, 'external', b'0', 100),
(126, 1152, 'external', b'0', 100),
(127, 1157, 'external', b'0', 100),
(128, 1161, 'external', b'0', 100),
(129, 1167, 'external', b'0', 100),
(130, 1168, 'external', b'0', 100);

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
(51, 809, 'testxx', '2024-06-06 07:09:00', 1900),
(63, 1086, 'datetime', '2025-01-01 12:00:00', 300),
(64, 1089, 'datetime', '2025-08-01 13:59:00', 300),
(65, 1092, 'datetime', NULL, 300),
(66, 1098, 'datetime', '2025-01-01 12:00:00', 300),
(67, 1101, 'datetime', '2025-08-01 13:59:00', 300),
(68, 1104, 'datetime', '2022-06-15 13:02:00', 300);

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
(101, 809, 'test float', 12.2222, 2100),
(102, 809, 'test float', 13.333, 2000),
(107, 1029, 'rate', 5, 200),
(108, 1032, 'rate', 4.5, 200),
(109, 1038, 'rate', 5, 200),
(110, 1041, 'rate', 4.5, 200),
(111, 1045, 'rate', 5, 200),
(112, 1061, 'rate', 3, 200),
(113, 1051, 'rate', 5, 200),
(114, 1054, 'rate', 4.5, 200),
(115, 1048, 'rate', 3.5, 200),
(116, 1086, 'rating', 5, 100),
(117, 1089, 'rating', 4, 100),
(118, 1092, 'rating', 4.5, 100),
(119, 1098, 'rating', 5, 100),
(120, 1101, 'rating', 4, 100),
(121, 1104, 'rating', 4.5, 100);

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
(3, 805, 'user__connexion', 1, 0),
(4, 5, 'user__profile', 1, 0),
(5, 8, 'user__profile', 1, 0),
(6, 8, 'user__profile', 2, 0),
(53, 75, 'user__connexion', 2, 500),
(56, 97, 'user__connexion', 2, 500),
(72, 809, 'user__connexion', 2, 1800),
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
(230, 802, 'user__connexion', 5, 0),
(231, 803, 'user__profile', 1, 100),
(232, 806, 'user__connexion', 6, 0),
(233, 807, 'user__profile', 1, 100),
(236, 1029, 'evals_quantity', 24, 100),
(237, 1032, 'evals_quantity', 18, 100),
(238, 1038, 'evals_quantity', 24, 100),
(239, 1041, 'evals_quantity', 18, 100),
(240, 1045, 'evals_quantity', 32, 100),
(241, 1061, 'evals_quantity', 14, 100),
(242, 1051, 'evals_quantity', 42, 100),
(243, 1054, 'evals_quantity', 27, 100),
(244, 1048, 'evals_quantity', 14, 100),
(245, 1172, 'user__connexion', 7, 0),
(246, 1173, 'user__profile', 1, 100);

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

--
-- Déchargement des données de la table `ingredient__price`
--

INSERT INTO `ingredient__price` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(9, 1029, 'price', 38.00, 300),
(10, 1032, 'price', 24.00, 300),
(11, 1038, 'price', 38.00, 300),
(12, 1041, 'price', 24.00, 300),
(13, 1045, 'price', 28.00, 300),
(14, 1061, 'price', 16.00, 300),
(15, 1051, 'price', 12.00, 300),
(16, 1054, 'price', 14.00, 300),
(17, 1048, 'price', 16.00, 300);

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
(1, 805, 'last-name', 'WoodWiccan', 0),
(2, 805, 'fist-name', 'Administrateur', 0),
(113, 809, 'fist-name', 'Jean', 2300),
(114, 809, 'last-name', 'Gromard', 2200),
(1225, 7, 'first-name', 'Jean', 400),
(1226, 7, 'last-name', 'Gromard', 300),
(1229, 1174, 'last-name', 'WoodWiccan', 400),
(1230, 1174, 'first-name', 'Administrateur', 300),
(1648, 1151, 'headline', 'Experience Culinary Excellence', 300),
(1649, 1151, 'description', 'Where passion meets perfection in every dish we serve', 200),
(1651, 1022, 'href', '#menu', 300),
(1652, 1022, 'text', 'View Menu', 200),
(1653, 1023, 'href', '#contact', 300),
(1654, 1023, 'text', 'Book a Table', 200),
(1655, 1044, 'headline', 'Our Signature Dishes', 300),
(1656, 1044, 'description', 'Discover our carefully crafted menu featuring the finest ingredients and innovative culinary techniques.', 200),
(1657, 1029, 'title', 'Prime Ribeye Steak', 600),
(1658, 1029, 'description', 'Grass-fed beef with truffle butter, roasted vegetables, and red wine reduction.', 500),
(1659, 1030, 'name', 'photo-1544025162-d76694265947', 300),
(1660, 1030, 'caption', '', 100),
(1661, 1031, 'storage-path', 'image/avif/8e36b5b241602dd1d9dc93468b3b0424ff0faa2f', 200),
(1662, 1031, 'filename', 'photo-1544025162-d76694265947.avif', 100),
(1663, 1032, 'title', 'Truffle Mushroom Pasta', 600),
(1664, 1032, 'description', 'Handmade fettuccine with wild mushrooms, black truffle, and parmesan cream sauce.', 500),
(1665, 1033, 'name', 'photo-2', 300),
(1666, 1033, 'caption', '', 100),
(1667, 1034, 'storage-path', 'image/avif/79e0467f6e955be6e867d0ee26f9a05c1f90f38c', 200),
(1668, 1034, 'filename', 'photo-2.avif', 100),
(1669, 1038, 'title', 'Prime Ribeye Steak', 600),
(1670, 1038, 'description', 'Grass-fed beef with truffle butter, roasted vegetables, and red wine reduction.', 500),
(1671, 1039, 'name', 'photo-1', 300),
(1672, 1039, 'caption', '', 100),
(1673, 1040, 'storage-path', 'image/avif/8e36b5b241602dd1d9dc93468b3b0424ff0faa2f', 200),
(1674, 1040, 'filename', 'photo-1.avif', 100),
(1675, 1041, 'title', 'Truffle Mushroom Pasta', 600),
(1676, 1041, 'description', 'Handmade fettuccine with wild mushrooms, black truffle, and parmesan cream sauce.', 500),
(1677, 1042, 'name', 'photo-2', 300),
(1678, 1042, 'caption', '', 100),
(1679, 1043, 'storage-path', 'image/avif/79e0467f6e955be6e867d0ee26f9a05c1f90f38c', 200),
(1680, 1043, 'filename', 'photo-2.avif', 100),
(1681, 1009, 'headline', 'Our Signature Dishes', 200),
(1682, 1009, 'description', 'Discover our carefully crafted menu featuring the finest ingredients and innovative culinary techniques.', 100),
(1683, 1045, 'title', 'Pan-Seared Salmon', 600),
(1684, 1045, 'description', 'Wild-caught salmon with lemon beurre blanc, asparagus, and quinoa pilaf.', 500),
(1685, 1046, 'name', 'photo-3', 300),
(1686, 1046, 'caption', '', 100),
(1687, 1047, 'storage-path', 'image/avif/ffd9be8f91dfffd254e2b76bd25d3ec265347a8c', 200),
(1688, 1047, 'filename', 'photo-3.avif', 100),
(1689, 1061, 'title', 'Harvest Salad', 600),
(1690, 1061, 'description', 'Mixed greens, roasted squash, goat cheese, candied pecans, and balsamic vinaigrette.', 500),
(1691, 1049, 'name', 'photo-4', 300),
(1692, 1049, 'caption', '', 100),
(1693, 1050, 'storage-path', 'image/avif/cf6da210fed8635cf61ee06aff4188c3d5e95e7b', 200),
(1694, 1050, 'filename', 'photo-4.avif', 100),
(1695, 1051, 'title', 'Chocolate Soufflé', 600),
(1696, 1051, 'description', 'Warm chocolate soufflé with vanilla bean ice cream and raspberry coulis.', 500),
(1697, 1052, 'name', 'photo-5', 300),
(1698, 1052, 'caption', '', 100),
(1699, 1053, 'storage-path', 'image/avif/526340ae9a7613175871e356647396a48741ed88', 200),
(1700, 1053, 'filename', 'photo-5.avif', 100),
(1701, 1054, 'title', 'Signature Cocktails', 600),
(1702, 1054, 'description', 'Handcrafted cocktails featuring seasonal ingredients and premium spirits.', 500),
(1703, 1055, 'name', 'photo-6', 300),
(1704, 1055, 'caption', '', 100),
(1705, 1056, 'storage-path', 'image/avif/38a51ebbe13477de7ad5507d9734fd03f9e1071d', 200),
(1706, 1056, 'filename', 'photo-6.avif', 100),
(1707, 1048, 'title', 'Harvest Salad', 600),
(1708, 1048, 'description', 'Mixed greens, roasted squash, goat cheese, candied pecans, and balsamic vinaigrette.', 500),
(1709, 1059, 'name', 'photo-4', 300),
(1710, 1059, 'caption', '', 100),
(1711, 1060, 'storage-path', 'image/avif/cf6da210fed8635cf61ee06aff4188c3d5e95e7b', 200),
(1712, 1060, 'filename', 'photo-4.avif', 100),
(1713, 1064, 'name', 'photo-7', 300),
(1714, 1064, 'caption', 'gourmetHaven', 100),
(1715, 1065, 'storage-path', 'image/avif/e818ccbe63b2a42bde858481ebe5bb90057a348a', 200),
(1716, 1065, 'filename', 'photo-7.avif', 100),
(1717, 1075, 'headline', 'Our Culinary Story', 300),
(1718, 1082, 'headline', 'Our Culinary Story', 700),
(1719, 1069, 'name', 'photo-7', 300),
(1720, 1069, 'caption', 'gourmetHaven', 100),
(1721, 1070, 'storage-path', 'image/avif/e818ccbe63b2a42bde858481ebe5bb90057a348a', 200),
(1722, 1070, 'filename', 'photo-7.avif', 100),
(1723, 1071, 'icon_class', 'fa-utensils', 300),
(1724, 1071, 'head', 'Seasonal Ingredients', 200),
(1725, 1071, 'body', 'We source locally and change our menu with the seasons.', 100),
(1726, 1072, 'icon_class', 'fa-wine-glass-alt', 300),
(1727, 1072, 'head', 'Extensive Wine List', 200),
(1728, 1072, 'body', 'Over 300 selections curated by our master sommelier.', 100),
(1729, 1073, 'icon_class', 'fa-award', 300),
(1730, 1073, 'head', 'Award-Winning', 200),
(1731, 1073, 'body', 'Recognized by Food & Wine Magazine and Michelin Guide.', 100),
(1732, 1074, 'icon_class', 'fa-heart', 300),
(1733, 1074, 'head', 'Sustainable Practices', 200),
(1734, 1074, 'body', 'Committed to eco-friendly operations and zero waste.', 100),
(1735, 1115, 'headline', 'Our Culinary Story', 700),
(1736, 1076, 'name', 'photo-7', 300),
(1737, 1076, 'caption', 'gourmetHaven', 100),
(1738, 1077, 'storage-path', 'image/avif/e818ccbe63b2a42bde858481ebe5bb90057a348a', 200),
(1739, 1077, 'filename', 'photo-7.avif', 100),
(1740, 1078, 'icon_class', 'fa-utensils', 300),
(1741, 1078, 'head', 'Seasonal Ingredients', 200),
(1742, 1078, 'body', 'We source locally and change our menu with the seasons.', 100),
(1743, 1079, 'icon_class', 'fa-wine-glass-alt', 300),
(1744, 1079, 'head', 'Extensive Wine List', 200),
(1745, 1079, 'body', 'Over 300 selections curated by our master sommelier.', 100),
(1746, 1080, 'icon_class', 'fa-award', 300),
(1747, 1080, 'head', 'Award-Winning', 200),
(1748, 1080, 'body', 'Recognized by Food & Wine Magazine and Michelin Guide.', 100),
(1749, 1081, 'icon_class', 'fa-heart', 300),
(1750, 1081, 'head', 'Sustainable Practices', 200),
(1751, 1081, 'body', 'Committed to eco-friendly operations and zero waste.', 100),
(1752, 1107, 'headline', 'What Our Guests Say', 300),
(1753, 1107, 'description', 'Don\'t just take our word for it - hear from our valued guests about their experiences at Gourmet Haven.', 200),
(1754, 1086, 'name', 'Sarah J.', 600),
(1755, 1086, 'status', 'Food Critic', 500),
(1756, 1086, 'body', 'The tasting menu was an absolute revelation. Every course was perfectly balanced and beautifully presented. The wine pairings were spot on. Worth every penny!', 200),
(1757, 1087, 'name', '43', 300),
(1758, 1087, 'caption', 'Sarah J.', 100),
(1759, 1088, 'storage-path', 'image/jpeg/41d9ee10c59f9452b9783f698b4c0db70b4bd798', 200),
(1760, 1088, 'filename', '43.jpg', 100),
(1761, 1089, 'name', 'Michael T.', 600),
(1762, 1089, 'status', 'Regular Guest', 500),
(1763, 1089, 'body', 'Celebrated our anniversary here and it was magical. The staff went above and beyond to make our evening special. The ribeye steak was cooked to perfection!', 200),
(1764, 1090, 'name', '32', 300),
(1765, 1090, 'caption', 'Michael T.', 100),
(1766, 1091, 'storage-path', 'image/jpeg/ac49a0ed98082c8f1a4c74b29e814276238dff78', 200),
(1767, 1091, 'filename', '32.jpg', 100),
(1768, 1092, 'name', 'Jennifer L.', 600),
(1769, 1092, 'status', 'Food Blogger', 500),
(1770, 1092, 'body', 'As a vegetarian, I was impressed by the creative plant-based options. The mushroom pasta was divine! The ambiance is elegant yet comfortable.', 200),
(1771, 1093, 'name', '65', 300),
(1772, 1093, 'caption', 'Jennifer L.', 100),
(1773, 1094, 'storage-path', 'image/jpeg/83d2b768d10733689f33eea889154ec2c8d832cb', 200),
(1774, 1094, 'filename', '65.jpg', 100),
(1775, 1013, 'headline', 'What Our Guests Say', 300),
(1776, 1013, 'description', 'Don\'t just take our word for it - hear from our valued guests about their experiences at Gourmet Haven.', 200),
(1777, 1098, 'name', 'Sarah J.', 600),
(1778, 1098, 'status', 'Food Critic', 500),
(1779, 1098, 'body', 'The tasting menu was an absolute revelation. Every course was perfectly balanced and beautifully presented. The wine pairings were spot on. Worth every penny!', 200),
(1780, 1099, 'name', '43', 300),
(1781, 1099, 'caption', 'Sarah J.', 100),
(1782, 1100, 'storage-path', 'image/jpeg/41d9ee10c59f9452b9783f698b4c0db70b4bd798', 200),
(1783, 1100, 'filename', '43.jpg', 100),
(1784, 1101, 'name', 'Michael T.', 600),
(1785, 1101, 'status', 'Regular Guest', 500),
(1786, 1101, 'body', 'Celebrated our anniversary here and it was magical. The staff went above and beyond to make our evening special. The ribeye steak was cooked to perfection!', 200),
(1787, 1102, 'name', '32', 300),
(1788, 1102, 'caption', 'Michael T.', 100),
(1789, 1103, 'storage-path', 'image/jpeg/ac49a0ed98082c8f1a4c74b29e814276238dff78', 200),
(1790, 1103, 'filename', '32.jpg', 100),
(1791, 1104, 'name', 'Jennifer L.', 600),
(1792, 1104, 'status', 'Food Blogger', 500),
(1793, 1104, 'body', 'As a vegetarian, I was impressed by the creative plant-based options. The mushroom pasta was divine! The ambiance is elegant yet comfortable.', 200),
(1794, 1105, 'name', '65', 300),
(1795, 1105, 'caption', 'Jennifer L.', 100),
(1796, 1106, 'storage-path', 'image/jpeg/83d2b768d10733689f33eea889154ec2c8d832cb', 200),
(1797, 1106, 'filename', '65.jpg', 100),
(1798, 1017, 'headline', 'Join Our Newsletter', 200),
(1799, 1017, 'description', 'Subscribe to receive updates on special events, seasonal menus, and exclusive offers.', 100),
(1800, 1122, 'headline', 'Our Culinary Story', 700),
(1801, 1109, 'name', 'photo-7', 300),
(1802, 1109, 'caption', 'gourmetHaven', 100),
(1803, 1110, 'storage-path', 'image/avif/e818ccbe63b2a42bde858481ebe5bb90057a348a', 200),
(1804, 1110, 'filename', 'photo-7.avif', 100),
(1805, 1111, 'icon_class', 'fa-utensils', 400),
(1806, 1111, 'head', 'Seasonal Ingredients', 300),
(1807, 1111, 'body', 'We source locally and change our menu with the seasons.', 200),
(1808, 1112, 'icon_class', 'fa-wine-glass-alt', 400),
(1809, 1112, 'head', 'Extensive Wine List', 300),
(1810, 1112, 'body', 'Over 300 selections curated by our master sommelier.', 200),
(1811, 1113, 'icon_class', 'fa-award', 400),
(1812, 1113, 'head', 'Award-Winning', 300),
(1813, 1113, 'body', 'Recognized by Food & Wine Magazine and Michelin Guide.', 200),
(1814, 1114, 'icon_class', 'fa-heart', 400),
(1815, 1114, 'head', 'Sustainable Practices', 300),
(1816, 1114, 'body', 'Committed to eco-friendly operations and zero waste.', 200),
(1817, 1011, 'headline', 'Our Culinary Story', 700),
(1818, 1116, 'name', 'photo-7', 300),
(1819, 1116, 'caption', 'gourmetHaven', 100),
(1820, 1117, 'storage-path', 'image/avif/e818ccbe63b2a42bde858481ebe5bb90057a348a', 200),
(1821, 1117, 'filename', 'photo-7.avif', 100),
(1822, 1118, 'icon_class', 'fa-utensils', 300),
(1823, 1118, 'head', 'Seasonal Ingredients', 200),
(1824, 1119, 'icon_class', 'fa-wine-glass-alt', 300),
(1825, 1119, 'head', 'Extensive Wine List', 200),
(1826, 1120, 'icon_class', 'fa-award', 300),
(1827, 1120, 'head', 'Award-Winning', 200),
(1828, 1121, 'icon_class', 'fa-heart', 300),
(1829, 1121, 'head', 'Sustainable Practices', 200),
(1830, 1135, 'reservation-headline', 'Make a Reservation', 500),
(1831, 1135, 'reservation-description', 'Reserve your table online or give us a call at (555) 123-4567. For parties of 6 or more, please call to make arrangements.', 400),
(1832, 1135, 'contact-headline', 'Contact Us', 300),
(1833, 1124, 'icon_class', 'fa-map-marker-alt', 300),
(1834, 1124, 'head', 'Address', 200),
(1835, 1125, 'icon_class', 'fa-phone-alt', 300),
(1836, 1125, 'head', 'Phone', 200),
(1837, 1126, 'icon_class', 'fa-envelope', 300),
(1838, 1126, 'head', 'Email', 200),
(1839, 1127, 'icon_class', 'fa-clock', 300),
(1840, 1127, 'head', 'Hours', 200),
(1841, 1141, 'reservation-headline', 'Make a Reservation', 500),
(1842, 1141, 'reservation-description', 'Reserve your table online or give us a call at (555) 123-4567. For parties of 6 or more, please call to make arrangements.', 400),
(1843, 1141, 'contact-headline', 'Contact Us', 300),
(1844, 1131, 'icon_class', 'fa-map-marker-alt', 300),
(1845, 1131, 'head', 'Address', 200),
(1846, 1132, 'icon_class', 'fa-phone-alt', 300),
(1847, 1132, 'head', 'Phone', 200),
(1848, 1133, 'icon_class', 'fa-envelope', 300),
(1849, 1133, 'head', 'Email', 200),
(1850, 1134, 'icon_class', 'fa-clock', 300),
(1851, 1134, 'head', 'Hours', 200),
(1852, 1147, 'reservation-headline', 'Make a Reservation', 500),
(1853, 1147, 'reservation-description', 'Reserve your table online or give us a call at (555) 123-4567. For parties of 6 or more, please call to make arrangements.', 400),
(1854, 1147, 'contact-headline', 'Contact Us', 300),
(1855, 1137, 'icon_class', 'fa-map-marker-alt', 300),
(1856, 1137, 'head', 'Address', 200),
(1857, 1138, 'icon_class', 'fa-phone-alt', 300),
(1858, 1138, 'head', 'Phone', 200),
(1859, 1139, 'icon_class', 'fa-envelope', 300),
(1860, 1139, 'head', 'Email', 200),
(1861, 1140, 'icon_class', 'fa-clock', 300),
(1862, 1140, 'head', 'Hours', 200),
(1863, 1015, 'reservation-headline', 'Make a Reservation', 500),
(1864, 1015, 'reservation-description', 'Reserve your table online or give us a call at (555) 123-4567. For parties of 6 or more, please call to make arrangements.', 400),
(1865, 1015, 'contact-headline', 'Contact Us', 300),
(1866, 1143, 'icon_class', 'fa-map-marker-alt', 300),
(1867, 1143, 'head', 'Address', 200),
(1868, 1144, 'icon_class', 'fa-phone-alt', 300),
(1869, 1144, 'head', 'Phone', 200),
(1870, 1145, 'icon_class', 'fa-envelope', 300),
(1871, 1145, 'head', 'Email', 200),
(1872, 1146, 'icon_class', 'fa-clock', 300),
(1873, 1146, 'head', 'Hours', 200),
(1874, 1171, 'headline', 'Experience Culinary Excellence', 300),
(1875, 1171, 'description', 'Where passion meets perfection in every dish we serve', 200),
(1876, 1149, 'href', '#gh-menu1009', 300),
(1877, 1149, 'text', 'View Menu', 200),
(1878, 1150, 'href', '#gh-contact1015', 300),
(1879, 1150, 'text', 'Book a Table', 200),
(1880, 1152, 'href', '#gh-menu1009', 300),
(1881, 1152, 'text', 'Reservations', 200),
(1882, 1153, 'headline', 'Gourmet Haven', 400),
(1883, 1157, 'href', '#gh-contact1015', 300),
(1884, 1157, 'text', 'Reservations', 200),
(1885, 1158, 'headline', 'Gourmet Haven', 400),
(1886, 1161, 'href', '#gh-contact1015', 300),
(1887, 1161, 'text', 'Reservations', 200),
(1888, 1162, 'headline', 'Gourmet Haven', 400),
(1889, 1007, 'headline', 'Experience Culinary Excellence', 400),
(1890, 1007, 'description', 'Where passion meets perfection in every dish we serve', 300),
(1891, 1167, 'href', '#gh-menu1009', 300),
(1892, 1167, 'text', 'View Menu', 200),
(1893, 1168, 'href', '#gh-contact1015', 300),
(1894, 1168, 'text', 'Book a Table', 200),
(1895, 1169, 'name', 'hero-bckgnd', 300),
(1896, 1169, 'caption', '', 100),
(1897, 1170, 'storage-path', 'image/jpeg/62960b0c9c7ca965fb3a489ce5dadfe79a486354', 200),
(1898, 1170, 'filename', 'hero-bckgnd.jpg', 100),
(1899, 4, 'last-name', 'WoodWiccan', 400),
(1900, 4, 'first-name', 'Administrateur', 300);

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
(183, 1075, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Founded in 2010 by Chef Michael Laurent, Gourmet Haven has been redefining fine dining with its innovative approach to classic cuisine. Our philosophy is simple: source the finest ingredients, let them shine, and create memorable dining experiences.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">With over 15 years of experience in Michelin-starred restaurants across Europe, Chef Laurent brings his passion for perfection to every dish. Our team of talented chefs and sommeliers work tirelessly to ensure each visit exceeds your expectations.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><br></span></p>', 200),
(184, 1082, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Founded in 2010 by Chef Michael Laurent, Gourmet Haven has been redefining fine dining with its innovative approach to classic cuisine. Our philosophy is simple: source the finest ingredients, let them shine, and create memorable dining experiences.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">With over 15 years of experience in Michelin-starred restaurants across Europe, Chef Laurent brings his passion for perfection to every dish. Our team of talented chefs and sommeliers work tirelessly to ensure each visit exceeds your expectations.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><br></span></p>', 600),
(185, 1115, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Founded in 2010 by Chef Michael Laurent, Gourmet Haven has been redefining fine dining with its innovative approach to classic cuisine. Our philosophy is simple: source the finest ingredients, let them shine, and create memorable dining experiences.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">With over 15 years of experience in Michelin-starred restaurants across Europe, Chef Laurent brings his passion for perfection to every dish. Our team of talented chefs and sommeliers work tirelessly to ensure each visit exceeds your expectations.</span></p>', 600),
(186, 1122, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Founded in 2010 by Chef Michael Laurent, Gourmet Haven has been redefining fine dining with its innovative approach to classic cuisine. Our philosophy is simple: source the finest ingredients, let them shine, and create memorable dining experiences.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">With over 15 years of experience in Michelin-starred restaurants across Europe, Chef Laurent brings his passion for perfection to every dish. Our team of talented chefs and sommeliers work tirelessly to ensure each visit exceeds your expectations.</span></p>', 600),
(187, 1111, 'text', '<p>We source locally and change our menu with the seasons.</p>', 100),
(188, 1112, 'text', '<p>Over 300 selections curated by our master sommelier.</p>', 100),
(189, 1113, 'text', '<p>Recognized by Food &amp; Wine Magazine and Michelin Guide.</p>', 100),
(190, 1114, 'text', '<p>Committed to eco-friendly operations and zero waste.</p>', 100),
(191, 1011, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Founded in 2010 by Chef Michael Laurent, Gourmet Haven has been redefining fine dining with its innovative approach to classic cuisine. Our philosophy is simple: source the finest ingredients, let them shine, and create memorable dining experiences.</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">With over 15 years of experience in Michelin-starred restaurants across Europe, Chef Laurent brings his passion for perfection to every dish. Our team of talented chefs and sommeliers work tirelessly to ensure each visit exceeds your expectations.</span></p>', 600),
(192, 1118, 'body', '<p>We source locally and change our menu with the seasons.</p>', 100),
(193, 1119, 'body', '<p>Over 300 selections curated by our master sommelier.</p>', 100),
(194, 1120, 'body', '<p>Recognized by Food &amp; Wine Magazine and Michelin Guide.</p>', 100),
(195, 1121, 'body', '<p>Committed to eco-friendly operations and zero waste.</p>', 100),
(196, 1124, 'body', '<p>123 Gourmet Street</p><p>New York, NY 10001</p>', 100),
(197, 1125, 'body', '<p>(555) 123-4567</p><p>Reservations: (555) 123-45568&nbsp;</p>', 100),
(198, 1126, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: reservations@gourmethaven.com\" target=\"_blank\">reservations@gourmethaven.com</a></span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></span></p>', 100),
(199, 1127, 'body', '<p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Dinner</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Tuesday - Sunday, 5:00 PM - 10:00 PM</span></p><p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Brunch</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Saturday &amp; Sunday, 10:00 AM - 2:00 PM</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Closed Mondays</span></p>', 100),
(200, 1135, 'embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3257.05223914085!2d-73.98517792172409!3d40.72823965776608!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e1!3m2!1sen!2sus!4v1754910591401!5m2!1sen!2sus\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 200),
(201, 1141, 'embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3257.05223914085!2d-73.98517792172409!3d40.72823965776608!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e1!3m2!1sen!2sus!4v1754910591401!5m2!1sen!2sus\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 200),
(202, 1131, 'body', '<p>123 Gourmet Street</p><p>New York, NY 10001</p>', 100),
(203, 1132, 'body', '<p>(555) 123-4567</p><p>Reservations: (555) 123-45568&nbsp;</p>', 100),
(204, 1133, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: reservations@gourmethaven.com\" target=\"_blank\">reservations@gourmethaven.com</a></span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></span></p>', 100),
(205, 1134, 'body', '<p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Dinner</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Tuesday - Sunday, 5:00 PM - 10:00 PM</span></p><p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Brunch</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Saturday &amp; Sunday, 10:00 AM - 2:00 PM</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Closed Mondays</span></p>', 100),
(206, 1147, 'embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2542.4616626947477!2d-73.9837792666074!3d40.72872513792581!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2597641e0b9ed%3A0x90c830050394d642!2sAu%20Za&#39;atar!5e0!3m2!1sen!2sus!4v1754911024361!5m2!1sen!2sus\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 200),
(207, 1137, 'body', '<p>123 Gourmet Street</p><p>New York, NY 10001</p>', 100),
(208, 1138, 'body', '<p>(555) 123-4567</p><p>Reservations: (555) 123-45568&nbsp;</p>', 100),
(209, 1139, 'body', '<p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: reservations@gourmethaven.com\" target=\"_blank\">reservations@gourmethaven.com</a></span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></span></p>', 100);
INSERT INTO `ingredient__text` (`id`, `cauldron_fk`, `name`, `value`, `priority`) VALUES
(210, 1140, 'body', '<p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Dinner</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Tuesday - Sunday, 5:00 PM - 10:00 PM</span></p><p><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><strong>Brunch</strong></span><span class=\"font-medium\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; box-sizing: border-box; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); font-weight: 500; color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\">:</span><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\"><span>&nbsp;</span>Saturday &amp; Sunday, 10:00 AM - 2:00 PM</span></p><p><span style=\"color: rgb(75, 85, 99); font-family: ui-sans-serif, system-ui, sans-serif, \" apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" \"noto=\"\" emoji\";=\"\" font-size:=\"\" medium;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" start;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(255,=\"\" 255,=\"\" 255);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" display:=\"\" inline=\"\" !important;=\"\" float:=\"\" none;\"=\"\">Closed Mondays</span></p>', 100),
(211, 1015, 'embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2542.4616626947477!2d-73.9837792666074!3d40.72872513792581!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2597641e0b9ed%3A0x90c830050394d642!2sAu%20Za\'atar!5e0!3m2!1sen!2sus!4v1754911024361!5m2!1sen!2sus\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 200),
(212, 1143, 'body', '<p>123 Gourmet Street</p><p>New York, NY 10001</p>', 100),
(213, 1144, 'body', '<p>(555) 123-4567</p><p>Reservations: (555) 123-45568&nbsp;</p>', 100),
(214, 1145, 'body', '<p><a href=\"mailto: reservations@gourmethaven.com\" target=\"_blank\">reservations@gourmethaven.com</a></p>\r\n<p><a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></p>', 100),
(215, 1146, 'body', '<p><strong>Dinner</strong>:Tuesday - Sunday, 5:00 PM - 10:00 PM</p>\r\n<p><strong>Brunch</strong>: Saturday &amp; Sunday, 10:00 AM - 2:00 PM</p>\r\n<p>Closed Mondays</p>', 100),
(216, 1153, 'description', '<p>Where passion meets perfection in every dish we serve.</p>', 300),
(217, 1154, 'week', '<p>Tuesday - Thursday</p><p>5:00 PM - 9:30 PM</p>', 400),
(218, 1154, 'week-end', '<p>Friday - Saturday</p><p>5:00 PM - 10:30 PM</p>', 300),
(219, 1154, 'sunday', '<p>Sunday</p><p>5:00 PM - 9:30 PM</p>', 200),
(220, 1154, 'brunch', '<p>Brunch (Sat-Sun)</p><p>10:00 AM - 2:00 PM</p>', 100),
(221, 1153, 'Contact Info', '<p>123 Gourmet Street</p><p>New York, NY 10001</p><p>Phone: (555) 123-4567</p><p>Email : <a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></p>', 100),
(222, 1158, 'description', '<p>Where passion meets perfection in every dish we serve.</p>', 300),
(223, 1158, 'Contact Info', '<p>123 Gourmet Street</p><p>New York, NY 10001</p><p>Phone: (555) 123-4567</p><p>Email : <a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></p>', 200),
(224, 1159, 'week', '<p>Tuesday - Thursday</p><p>5:00 PM - 9:30 PM</p>', 400),
(225, 1159, 'week-end', '<p>Friday - Saturday</p><p>5:00 PM - 10:30 PM</p>', 300),
(226, 1159, 'sunday', '<p>Sunday</p><p>5:00 PM - 9:30 PM</p>', 200),
(227, 1159, 'brunch', '<p>Brunch (Sat-Sun)</p><p>10:00 AM - 2:00 PM</p>', 100),
(228, 1162, 'description', '<p>Where passion meets perfection in every dish we serve.</p>', 300),
(229, 1162, 'contact-info', '<p>123 Gourmet Street</p><p>New York, NY 10001</p><p>Phone: (555) 123-4567</p><p>Email : <a href=\"mailto: info@gourmethaven.com\" target=\"_blank\">info@gourmethaven.com</a></p>', 200),
(230, 1163, 'week', '<p>Tuesday - Thursday</p><p>5:00 PM - 9:30 PM</p>', 400),
(231, 1163, 'week-end', '<p>Friday - Saturday</p><p>5:00 PM - 10:30 PM</p>', 300),
(232, 1163, 'sunday', '<p>Sunday</p><p>5:00 PM - 9:30 PM</p>', 200),
(233, 1163, 'brunch', '<p>Brunch (Sat-Sun)</p><p>10:00 AM - 2:00 PM</p>', 100);

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
  `modifier` int(11) DEFAULT NULL,
  `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creator` int(11) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user__connexion`
--

INSERT INTO `user__connexion` (`id`, `name`, `email`, `login`, `pass_hash`, `modifier`, `modified`, `creator`, `created`) VALUES
(1, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(2, 'Jean', 'jean.de.gromard@gmail.com', 'jean', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', NULL, '2024-03-01 15:46:01', NULL, '2024-03-01 15:46:01'),
(3, 'Administrator', 'adminstrator@woodwiccan', 'admin', '$2y$11$11FgVhXijP654xVeVG/VjeKIQnyRjVx0AsQ2QGQXiEx0VJeWeaGJ.', 1, '2024-04-04 14:58:34', 1, '2024-04-04 14:58:34'),
(4, 'admin', 'admin@nimp.fr', 'bbb', '$2y$10$s3sYPL.8Fukd5gPT49aQGOmddgohaqQC6wGKbRamnhUGN8pwtWU9K', 1, '2024-10-09 16:22:38', 1, '2024-10-09 16:22:38'),
(5, 'Administrateur', 'admin@woodwiccan.fr', 'admin', '$2y$10$HrbGtexod0NuI5.FvMwTt.xFl/bp6rfL2bOKYEkK.3JoyniiPA0IG', NULL, '2025-06-12 10:44:22', NULL, '2025-06-12 10:43:28'),
(6, 'Jean', 'jean.de.gromard@gmail.com', 'jean', '$2y$10$eQKR0F153VZ5UExkbgQS/O9nnUIGGlw4WNQoPeGIG1Xiv53dAEzrG', NULL, '2025-06-12 10:48:02', NULL, '2025-06-12 10:47:16'),
(7, 'Administrateur', 'admin@woodwiccan.fr', 'admin', '$2y$10$hAX.5yocT5fbdaHeJSptg.uEquy4vDnXNAenCoU7JA2qfcq.xnUXW', NULL, '2025-08-13 16:14:44', NULL, '2025-08-13 16:14:44');

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
(4, 2, 'login', NULL, NULL, 0, 0, 0, ''),
(6, 2, '*', NULL, 181, 0, 1, 1, ''),
(8, 2, '*', NULL, 212, 0, 1, 1, ''),
(10, 14, 'gourmetHaven', 8, 212, 0, 1, 1, '');

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
(14, 'test', 'example', '2025-08-09 21:29:09');

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
  `context` varchar(255) DEFAULT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `priority` int(11) NOT NULL DEFAULT 0,
  `level_1` int(10) UNSIGNED DEFAULT NULL,
  `level_2` int(10) UNSIGNED DEFAULT NULL,
  `level_3` int(10) UNSIGNED DEFAULT NULL,
  `level_4` int(10) UNSIGNED DEFAULT NULL,
  `level_5` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `witch`
--

INSERT INTO `witch` (`id`, `name`, `data`, `site`, `url`, `status`, `invoke`, `cauldron`, `cauldron_priority`, `context`, `datetime`, `priority`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`) VALUES
(1, 'Root', 'Ici se trouve la racine de la plateforme. C\'est à partir d\'ici que sont créées les homes de chaque site de la plateforme.', NULL, NULL, 0, NULL, NULL, 0, NULL, '2024-03-01 15:46:01', 0, NULL, NULL, NULL, NULL, NULL),
(2, 'Admin WoodWiccan', 'Site d\'administration', NULL, NULL, 0, NULL, NULL, 0, NULL, '2024-03-01 15:46:01', 400, 1, NULL, NULL, NULL, NULL),
(3, 'Utilisateurs', '', 'admin', 'utilisateurs', 0, '', NULL, 0, NULL, '2024-03-01 15:46:01', 200, 1, 1, NULL, NULL, NULL),
(4, 'Administrateur', '', 'admin', 'utilisateurs/administrateur', 0, '', 4, 200, '', '2024-03-01 15:46:01', 0, 1, 1, 1, NULL, NULL),
(5, 'Home admin ww', 'landing page for ww admin', 'admin', '', 0, 'root', NULL, 0, NULL, '2024-03-01 15:46:01', 0, 1, 2, NULL, NULL, NULL),
(6, 'Logout', 'Module de déconnexion/connexion', 'admin', 'login', 0, 'login', NULL, 0, NULL, '2024-03-01 15:46:01', 600, 1, 2, 10, NULL, NULL),
(7, 'Witch', 'Visualisation des Witches, c\'est a dire de chaque point de l\'arborescence -appelé ici Matriarcat. Chacun de ces points peut être associé à un contenu et/ou à un module exécutable. \r\nOn peut également définir une URL permettant de cibler cette witch.', 'admin', 'view', 0, 'witch', NULL, 0, NULL, '2024-03-01 15:46:01', 500, 1, 2, 2, NULL, NULL),
(10, 'Menu', '', NULL, NULL, 0, NULL, NULL, 0, NULL, '2024-03-01 15:46:01', 100, 1, 2, 5, NULL, NULL),
(11, 'Profiles', 'Permissions handeling is based on user profiles.', 'admin', 'profiles', 0, 'profiles', NULL, 0, NULL, '2024-03-01 15:46:01', 600, 1, 2, 5, 1, NULL),
(13, 'Apply', '', 'admin', 'apply', 0, 'emptyCache', NULL, 0, NULL, '2024-03-01 15:46:28', 300, 1, 2, 5, 3, NULL),
(15, 'Recipes', 'Les données sont stockées sous la forme de structures qui sont éditables ici.', 'admin', 'recipe', 0, 'recipe/list', NULL, 0, NULL, '2024-03-09 15:48:02', 500, 1, 2, 5, 4, NULL),
(16, 'Cauldron', 'test', 'admin', 'cauldron', 0, 'cauldron', NULL, 0, NULL, '2024-04-08 15:04:18', 400, 1, 2, 9, NULL, NULL),
(25, 'View', 'Cauldron\'s inside element\'s structure visualization', 'admin', 'recipe/view', 0, 'recipe/view', NULL, 0, NULL, '2024-06-11 13:57:05', 0, 1, 2, 5, 4, 1),
(26, 'Edit', '', 'admin', 'recipe/edit', 0, 'recipe/edit', NULL, 0, NULL, '2024-06-13 13:54:51', 0, 1, 2, 5, 4, 2),
(27, 'Create', '', 'admin', 'recipe/create', 0, 'recipe/create', NULL, 0, NULL, '2024-08-07 15:05:40', 0, 1, 2, 5, 4, 3),
(30, 'Create', '', 'admin', 'create-witch', 0, 'witch/create', NULL, 0, NULL, '2024-09-24 21:02:27', 100, 1, 2, 2, 2, NULL),
(38, 'clipboard', NULL, 'admin', 'clipboard', 0, 'witch/clipboard', NULL, 0, NULL, '2024-10-01 15:24:28', 0, 1, 2, 2, 1, NULL),
(127, 'Cauldrons', NULL, 'admin', 'cauldrons', 0, 'cauldrons', NULL, 0, NULL, '2024-11-26 15:11:01', 200, 1, 2, 5, 5, NULL),
(129, 'Jean', '', 'admin', NULL, 0, NULL, 7, 0, NULL, '2025-01-10 16:01:18', 0, 1, 1, 2, NULL, NULL),
(212, 'examples', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, '2025-08-07 13:30:34', 200, 4, NULL, NULL, NULL, NULL),
(213, 'GourmetHaven', 'example de film sur un restaurant fictif', 'example', '', 0, 'gourmetHaven', 1005, 0, NULL, '2025-08-07 13:40:31', 0, 4, 1, NULL, NULL, NULL),
(214, 'Home', NULL, 'example', NULL, 0, NULL, 1007, 0, NULL, '2025-08-10 13:33:54', 700, 4, 1, 1, NULL, NULL),
(215, 'Menu', NULL, 'example', NULL, 0, NULL, 1009, 0, NULL, '2025-08-10 13:34:50', 600, 4, 1, 2, NULL, NULL),
(216, 'About', NULL, 'example', NULL, 0, NULL, 1011, 0, NULL, '2025-08-10 15:33:51', 500, 4, 1, 3, NULL, NULL),
(217, 'Testimonials', NULL, 'example', NULL, 0, NULL, 1013, 0, NULL, '2025-08-10 15:36:15', 400, 4, 1, 4, NULL, NULL),
(218, 'Contact', NULL, 'example', NULL, 0, NULL, 1015, 0, NULL, '2025-08-10 15:38:33', 300, 4, 1, 5, NULL, NULL),
(219, 'Newsletter', NULL, 'example', NULL, 0, NULL, 1017, 0, NULL, '2025-08-10 15:40:13', 200, 4, 1, 6, NULL, NULL),
(220, 'Prime Ribeye Steak', NULL, 'example', NULL, 0, NULL, 1038, 0, NULL, '2025-08-11 07:41:16', 700, 4, 1, 2, 1, NULL),
(221, 'Truffle Mushroom Pasta', NULL, 'example', NULL, 0, NULL, 1041, 0, NULL, '2025-08-11 07:43:25', 600, 4, 1, 2, 2, NULL),
(222, 'Pan-Seared Salmon', NULL, 'example', NULL, 0, NULL, 1045, 0, NULL, '2025-08-11 07:45:08', 500, 4, 1, 2, 3, NULL),
(223, 'Harvest Salad', NULL, 'example', NULL, 0, NULL, 1048, 0, NULL, '2025-08-11 07:47:08', 400, 4, 1, 2, 4, NULL),
(224, 'Chocolate Soufflé', NULL, 'example', NULL, 0, NULL, 1051, 0, NULL, '2025-08-11 07:49:20', 300, 4, 1, 2, 5, NULL),
(225, 'Signature Cocktails', NULL, 'example', NULL, 0, NULL, 1054, 0, NULL, '2025-08-11 07:50:56', 200, 4, 1, 2, 6, NULL);

--
-- Index pour les tables déchargées
--

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
-- Index pour la table `witch`
--
ALTER TABLE `witch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_level_1` (`level_1`),
  ADD KEY `IDX_level_2` (`level_2`),
  ADD KEY `IDX_level_3` (`level_3`),
  ADD KEY `IDX_level_4` (`level_4`),
  ADD KEY `IDX_level_5` (`level_5`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cauldron`
--
ALTER TABLE `cauldron`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1175;

--
-- AUTO_INCREMENT pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT pour la table `ingredient__datetime`
--
ALTER TABLE `ingredient__datetime`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `ingredient__float`
--
ALTER TABLE `ingredient__float`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pour la table `ingredient__integer`
--
ALTER TABLE `ingredient__integer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1901;

--
-- AUTO_INCREMENT pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `user__policy`
--
ALTER TABLE `user__policy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user__profile`
--
ALTER TABLE `user__profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `witch`
--
ALTER TABLE `witch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;
COMMIT;
