-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mar. 01 juil. 2025 à 09:36
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
  `level_7` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cauldron`
--

INSERT INTO `cauldron` (`id`, `target`, `status`, `name`, `recipe`, `data`, `priority`, `creator`, `created`, `modificator`, `modified`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `level_6`, `level_7`) VALUES
(1, NULL, NULL, 'ROOT', 'root', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 15:08:36', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, 'admin', 'ww-site-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:13:42', 1, NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, NULL, 'ww-user', 'ww-recipe-folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2024-11-27 16:15:12', 1, 1, NULL, NULL, NULL, NULL, NULL),
(4, NULL, NULL, 'Administrateur', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:14', 1, 1, 1, NULL, NULL, NULL, NULL),
(5, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, 1, 1, NULL),
(7, NULL, NULL, 'Jean', 'ww-user', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-01-08 15:53:44', 1, 1, 2, NULL, NULL, NULL, NULL),
(8, NULL, NULL, 'profiles', 'folder', NULL, 0, NULL, '2024-06-11 15:42:06', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, 1, 1, NULL),
(149, NULL, NULL, 'folder', 'ww-recipe-folder', NULL, 0, NULL, '2024-10-08 15:37:41', NULL, '2024-11-27 16:15:09', 1, 2, NULL, NULL, NULL, NULL, NULL),
(408, NULL, NULL, 'identifier', 'ww-recipe-folder', NULL, 0, NULL, '2025-01-10 16:21:37', NULL, '2025-01-10 16:21:37', 1, 3, NULL, NULL, NULL, NULL, NULL),
(770, NULL, NULL, 'link', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-06 17:17:29', NULL, '2025-03-06 17:17:29', 1, 4, NULL, NULL, NULL, NULL, NULL),
(773, NULL, NULL, 'ww-file', 'ww-recipe-folder', NULL, 0, NULL, '2025-03-10 09:19:15', NULL, '2025-03-10 09:19:15', 1, 5, NULL, NULL, NULL, NULL, NULL),
(795, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-05-07 16:35:06', NULL, '2025-05-07 16:35:06', 1, 1, 2, 3, NULL, NULL, NULL),
(800, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-06-12 10:42:37', NULL, '2025-06-12 10:42:37', 1, 1, 1, 2, NULL, NULL, NULL),
(802, NULL, NULL, 'connexion', 'ww-connexion', '{\"connector\":\"user__connexion\",\"table\":\"user__connexion\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:43:28', NULL, '2025-06-12 10:44:56', 1, 1, 1, 4, NULL, NULL, NULL),
(803, NULL, NULL, 'profiles', 'ww-profiles', '{\"connector\":\"ww-profiles\",\"table\":\"user__profiles\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:44:48', NULL, '2025-06-12 10:44:56', 1, 1, 1, 5, NULL, NULL, NULL),
(804, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-06-12 10:44:56', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, NULL, NULL, NULL),
(805, 4, b'1', 'Administrateur', 'ww-user', NULL, 0, NULL, '2025-06-12 10:44:56', NULL, '2025-06-12 10:44:56', 1, 1, 1, 3, 1, NULL, NULL),
(806, NULL, NULL, 'connexion', 'ww-connexion', '{\"connector\":\"user__connexion\",\"table\":\"user__connexion\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:47:16', NULL, '2025-06-12 10:48:21', 1, 1, 2, 5, NULL, NULL, NULL),
(807, NULL, NULL, 'profiles', 'ww-profiles', '{\"connector\":\"ww-profiles\",\"table\":\"user__profiles\",\"field\":\"id\"}', 100, NULL, '2025-06-12 10:48:11', NULL, '2025-06-12 10:48:21', 1, 1, 2, 6, NULL, NULL, NULL),
(808, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-06-12 10:48:21', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, NULL, NULL, NULL),
(809, 7, b'1', 'Jean', 'ww-user', NULL, 0, NULL, '2025-06-12 10:48:21', NULL, '2025-06-12 10:48:21', 1, 1, 2, 4, 1, NULL, NULL),
(819, NULL, NULL, 'Home', 'folder', NULL, 0, NULL, '2025-06-17 14:46:16', NULL, '2025-06-17 14:58:42', 1, 2, 1, NULL, NULL, NULL, NULL),
(820, NULL, NULL, 'logo', 'image', NULL, 100, NULL, '2025-06-17 14:48:28', NULL, '2025-06-18 15:08:34', 1, 2, 1, 6, 1, 1, NULL),
(821, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-17 14:48:28', NULL, '2025-06-17 14:48:28', 1, 2, 1, 1, 1, NULL, NULL),
(822, NULL, NULL, 'contact-email', 'link', NULL, 100, NULL, '2025-06-17 14:53:56', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 1, NULL),
(823, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-17 14:55:05', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 2, NULL),
(824, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-17 14:57:07', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 3, NULL),
(825, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-17 14:57:07', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 3, 1),
(826, NULL, NULL, 'DRAFTS', 'ww-drafts-folder', NULL, 0, NULL, '2025-06-18 15:07:49', NULL, '2025-06-18 15:07:49', 1, 2, 1, 5, NULL, NULL, NULL),
(829, NULL, NULL, 'ARCHIVES', 'ww-archives-folder', NULL, 0, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 15:08:34', 1, 2, 1, 6, NULL, NULL, NULL),
(830, NULL, NULL, 'image', 'image', NULL, 400, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 4, NULL),
(831, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 4, 1),
(832, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 5, NULL),
(833, NULL, NULL, 'link', 'link', NULL, 200, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 6, NULL),
(834, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 7, NULL),
(835, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, 7, 1),
(836, 819, b'1', 'Home', 'folder', NULL, 0, NULL, '2025-06-18 15:08:34', NULL, '2025-06-18 15:08:34', 1, 2, 1, 6, 1, NULL, NULL),
(910, NULL, NULL, 'image', 'image', NULL, 400, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 1, NULL),
(911, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 1, 1),
(912, NULL, NULL, 'link', 'link', NULL, 200, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 2, NULL),
(913, NULL, NULL, 'contact-email', 'link', NULL, 100, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 3, NULL),
(914, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 4, NULL),
(915, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 5, NULL),
(916, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 5, 1),
(917, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 6, NULL),
(918, NULL, NULL, 'image', 'image', NULL, 100, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 7, NULL),
(919, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, 7, 1),
(920, 819, b'1', 'Home', 'folder', NULL, 0, NULL, '2025-06-18 16:04:25', NULL, '2025-06-18 16:04:25', 1, 2, 1, 6, 2, NULL, NULL),
(921, NULL, NULL, 'image', 'image', NULL, 400, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 1, NULL),
(922, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 1, 1),
(923, NULL, NULL, 'contact-email', 'link', NULL, 100, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 2, NULL),
(924, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 3, NULL),
(925, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 4, NULL),
(926, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, 4, 1),
(927, 819, b'1', 'Home', 'folder', NULL, 0, NULL, '2025-06-18 16:05:39', NULL, '2025-06-18 16:05:39', 1, 2, 1, 6, 3, NULL, NULL),
(928, NULL, NULL, 'logo', 'image', NULL, 400, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 1, NULL),
(929, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 1, 1),
(930, NULL, NULL, 'contact-email', 'link', NULL, 100, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 2, NULL),
(931, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 3, NULL),
(932, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 4, NULL),
(933, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-18 16:09:34', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, 4, 1),
(934, 819, b'1', 'Home', 'folder', NULL, 0, NULL, '2025-06-18 16:09:34', NULL, '2025-06-18 16:09:34', 1, 2, 1, 6, 4, NULL, NULL),
(935, NULL, NULL, 'demo-rubrique', 'ww-recipe-folder', NULL, 0, NULL, '2025-06-19 13:52:30', NULL, '2025-06-19 13:52:30', 1, 6, NULL, NULL, NULL, NULL, NULL),
(936, NULL, NULL, 'Le CMS', 'demo-rubrique', NULL, 0, NULL, '2025-06-19 13:52:30', NULL, '2025-06-19 13:53:44', 1, 6, 1, NULL, NULL, NULL, NULL),
(937, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-19 13:52:30', NULL, '2025-06-19 13:52:30', 1, 6, 1, 1, NULL, NULL, NULL),
(938, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-19 13:52:30', NULL, '2025-06-19 13:52:30', 1, 6, 1, 1, 1, NULL, NULL),
(939, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 26, NULL, NULL, NULL),
(940, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 26, 1, NULL, NULL),
(941, NULL, NULL, 'contact-email', 'link', NULL, 100, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 27, NULL, NULL, NULL),
(942, NULL, NULL, 'call-to-action', 'link', NULL, 100, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 28, NULL, NULL, NULL),
(943, NULL, NULL, 'logo', 'image', NULL, 400, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 29, NULL, NULL, NULL),
(944, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 29, 1, NULL, NULL),
(945, 819, b'1', 'Home', 'folder', NULL, 0, NULL, '2025-06-23 13:57:45', NULL, '2025-06-23 13:57:45', 1, 2, 1, 6, 5, NULL, NULL),
(946, NULL, NULL, 'demo-article', 'ww-recipe-folder', NULL, 0, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 15:17:13', 1, 7, NULL, NULL, NULL, NULL, NULL),
(947, NULL, NULL, 'REPRENEZ LE CONTRÔLE', 'demo-article', NULL, 0, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 16:55:34', 1, 7, 1, NULL, NULL, NULL, NULL),
(948, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 15:17:13', 1, 7, 1, 1, NULL, NULL, NULL),
(949, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 15:17:13', 1, 7, 1, 1, 1, NULL, NULL),
(950, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-23 15:17:13', NULL, '2025-06-23 15:17:13', 1, 7, 1, 2, NULL, NULL, NULL),
(951, NULL, NULL, 'Woody CMS en quelques mots', 'demo-article', NULL, 0, NULL, '2025-06-23 17:05:46', NULL, '2025-06-23 17:09:35', 1, 7, 2, NULL, NULL, NULL, NULL),
(952, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-23 17:05:46', NULL, '2025-06-23 17:05:46', 1, 7, 2, 1, NULL, NULL, NULL),
(953, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-23 17:05:46', NULL, '2025-06-23 17:05:46', 1, 7, 2, 1, 1, NULL, NULL),
(954, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-23 17:05:47', NULL, '2025-06-23 17:05:47', 1, 7, 2, 2, NULL, NULL, NULL),
(955, NULL, NULL, 'Technologies', 'demo-rubrique', NULL, 0, NULL, '2025-06-24 08:00:18', NULL, '2025-06-24 08:00:58', 1, 6, 2, NULL, NULL, NULL, NULL),
(956, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-24 08:00:18', NULL, '2025-06-24 08:00:18', 1, 6, 2, 1, NULL, NULL, NULL),
(957, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-24 08:00:18', NULL, '2025-06-24 08:00:18', 1, 6, 2, 1, 1, NULL, NULL),
(958, NULL, NULL, 'FONCTIONNEMENT GLOBAL', 'demo-article', NULL, 0, NULL, '2025-06-24 08:03:02', NULL, '2025-06-24 08:06:26', 1, 7, 3, NULL, NULL, NULL, NULL),
(959, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-24 08:03:02', NULL, '2025-06-24 08:03:02', 1, 7, 3, 1, NULL, NULL, NULL),
(960, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-24 08:03:02', NULL, '2025-06-24 08:03:02', 1, 7, 3, 1, 1, NULL, NULL),
(961, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-24 08:03:02', NULL, '2025-06-24 08:03:02', 1, 7, 3, 2, NULL, NULL, NULL),
(962, NULL, NULL, 'EMPLACEMENT MATRICIEL', 'demo-article', NULL, 0, NULL, '2025-06-25 16:43:36', NULL, '2025-06-25 16:44:49', 1, 7, 4, NULL, NULL, NULL, NULL),
(963, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-25 16:43:36', NULL, '2025-06-25 16:43:36', 1, 7, 4, 1, NULL, NULL, NULL),
(964, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-25 16:43:36', NULL, '2025-06-25 16:43:36', 1, 7, 4, 1, 1, NULL, NULL),
(965, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-25 16:43:36', NULL, '2025-06-25 16:43:36', 1, 7, 4, 2, NULL, NULL, NULL),
(966, NULL, NULL, 'CONTENU AJUSTABLE', 'demo-article', NULL, 0, NULL, '2025-06-25 16:47:03', NULL, '2025-06-25 16:48:10', 1, 7, 5, NULL, NULL, NULL, NULL),
(967, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-25 16:47:03', NULL, '2025-06-25 16:47:03', 1, 7, 5, 1, NULL, NULL, NULL),
(968, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-25 16:47:03', NULL, '2025-06-25 16:47:03', 1, 7, 5, 1, 1, NULL, NULL),
(969, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-25 16:47:03', NULL, '2025-06-25 16:47:03', 1, 7, 5, 2, NULL, NULL, NULL),
(970, NULL, NULL, 'NOMMAGE STRUCTUREL DES CHAMPS', 'demo-article', NULL, 0, NULL, '2025-06-25 16:49:33', NULL, '2025-06-25 16:50:43', 1, 7, 6, NULL, NULL, NULL, NULL),
(971, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-25 16:49:33', NULL, '2025-06-25 16:49:33', 1, 7, 6, 1, NULL, NULL, NULL),
(972, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-25 16:49:33', NULL, '2025-06-25 16:49:33', 1, 7, 6, 1, 1, NULL, NULL),
(973, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-25 16:49:33', NULL, '2025-06-25 16:49:33', 1, 7, 6, 2, NULL, NULL, NULL),
(974, NULL, NULL, 'À Propos', 'demo-rubrique', NULL, 0, NULL, '2025-06-25 16:52:32', NULL, '2025-06-25 16:53:16', 1, 6, 3, NULL, NULL, NULL, NULL),
(975, NULL, NULL, 'background', 'image', NULL, 100, NULL, '2025-06-25 16:52:32', NULL, '2025-06-25 16:52:32', 1, 6, 3, 1, NULL, NULL, NULL),
(976, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-25 16:52:32', NULL, '2025-06-25 16:52:32', 1, 6, 3, 1, 1, NULL, NULL),
(977, NULL, NULL, 'WITCH CASE EN BREF', 'demo-article', NULL, 0, NULL, '2025-06-25 16:56:31', NULL, '2025-06-25 16:58:14', 1, 7, 7, NULL, NULL, NULL, NULL),
(978, NULL, NULL, 'image', 'image', NULL, 800, NULL, '2025-06-25 16:56:31', NULL, '2025-06-25 16:56:31', 1, 7, 7, 1, NULL, NULL, NULL),
(979, NULL, NULL, 'file', 'ww-file', NULL, 200, NULL, '2025-06-25 16:56:31', NULL, '2025-06-25 16:56:31', 1, 7, 7, 1, 1, NULL, NULL),
(980, NULL, NULL, 'link', 'link', NULL, 100, NULL, '2025-06-25 16:56:31', NULL, '2025-06-25 16:56:31', 1, 7, 7, 2, NULL, NULL, NULL);

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
(71, 822, 'external', b'1', 100),
(72, 823, 'external', b'1', 100),
(73, 832, 'external', b'1', 100),
(74, 833, 'external', b'1', 100),
(99, 912, 'external', b'1', 100),
(100, 913, 'external', b'1', 100),
(101, 914, 'external', b'1', 100),
(102, 917, 'external', b'1', 100),
(103, 923, 'external', b'1', 100),
(104, 924, 'external', b'1', 100),
(105, 930, 'external', b'1', 100),
(106, 931, 'external', b'1', 100),
(107, 941, 'external', b'1', 100),
(108, 942, 'external', b'1', 100),
(109, 950, 'external', b'0', 100),
(110, 954, 'external', b'1', 100),
(111, 961, 'external', b'0', 100),
(112, 965, 'external', b'0', 100),
(113, 969, 'external', b'0', 100),
(114, 973, 'external', b'0', 100),
(115, 980, 'external', b'1', 100);

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
(51, 809, 'testxx', '2024-06-06 07:09:00', 1900);

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
(102, 809, 'test float', 13.333, 2000);

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
(233, 807, 'user__profile', 1, 100);

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
(1, 805, 'last-name', 'WoodWiccan', 0),
(2, 805, 'fist-name', 'Administrateur', 0),
(113, 809, 'fist-name', 'Jean', 2300),
(114, 809, 'last-name', 'Gromard', 2200),
(1225, 7, 'first-name', 'Jean', 400),
(1226, 7, 'last-name', 'Gromard', 300),
(1229, 4, 'last-name', 'WoodWiccan', 400),
(1230, 4, 'first-name', 'Administrateur', 300),
(1242, 836, 'meta-title', 'WoodWiccan', 800),
(1243, 836, 'meta-description', 'description du site WoodWiccan', 700),
(1244, 836, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 600),
(1245, 820, 'name', 'wc-logo', 300),
(1246, 820, 'caption', '', 100),
(1247, 821, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1248, 821, 'filename', 'wc-logo.jpg', 100),
(1249, 822, 'href', ' mailto:info@witchcase.com', 300),
(1250, 822, 'text', 'info@witchcase.com', 200),
(1251, 823, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1252, 823, 'text', 'Github repository', 200),
(1253, 824, 'name', 'img_fond_contact', 300),
(1254, 824, 'caption', 'witch on broom shadow', 100),
(1255, 825, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1256, 825, 'filename', 'img_fond_contact.jpg', 100),
(1263, 920, 'meta-title', 'WoodWiccan', 900),
(1264, 920, 'meta-description', 'description du site WoodWiccan', 800),
(1265, 920, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 700),
(1266, 920, 'string', 'WoodWiccan', 600),
(1267, 830, 'name', 'wc-logo', 300),
(1268, 830, 'caption', '', 100),
(1269, 831, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1270, 831, 'filename', 'wc-logo.jpg', 100),
(1271, 832, 'href', ' mailto:info@witchcase.com', 300),
(1272, 832, 'text', 'info@witchcase.com', 200),
(1273, 833, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1274, 833, 'text', 'Github repository', 200),
(1275, 834, 'name', 'img_fond_contact', 300),
(1276, 834, 'caption', 'witch on broom shadow', 100),
(1277, 835, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1278, 835, 'filename', 'img_fond_contact.jpg', 100),
(1443, 927, 'meta-title', 'WoodWiccan', 1200),
(1444, 927, 'meta-description', 'description du site WoodWiccan', 1100),
(1445, 927, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 1000),
(1446, 927, 'headline', 'WoodWiccan', 900),
(1447, 910, 'name', 'wc-logo', 300),
(1448, 910, 'caption', '', 100),
(1449, 911, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1450, 911, 'filename', 'wc-logo.jpg', 100),
(1451, 912, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1452, 912, 'text', 'Github repository', 200),
(1453, 913, 'href', ' mailto:info@witchcase.com', 300),
(1454, 913, 'text', 'info@witchcase.com', 200),
(1455, 914, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1456, 914, 'text', 'Github repository', 200),
(1457, 915, 'name', 'img_fond_contact', 300),
(1458, 915, 'caption', 'witch on broom shadow', 100),
(1459, 916, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1460, 916, 'filename', 'img_fond_contact.jpg', 100),
(1461, 917, 'href', ' mailto:info@witchcase.com', 300),
(1462, 917, 'text', 'info@witchcase.com', 200),
(1463, 918, 'name', 'img_fond_contact', 300),
(1464, 918, 'caption', 'witch on broom shadow', 100),
(1465, 919, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1466, 919, 'filename', 'img_fond_contact.jpg', 100),
(1467, 934, 'meta-title', 'WoodWiccan', 900),
(1468, 934, 'meta-description', 'description du site WoodWiccan', 800),
(1469, 934, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 700),
(1470, 934, 'headline', 'WoodWiccan', 600),
(1471, 921, 'name', 'wc-logo', 300),
(1472, 921, 'caption', '', 100),
(1473, 922, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1474, 922, 'filename', 'wc-logo.jpg', 100),
(1475, 923, 'href', ' mailto:info@witchcase.com', 300),
(1476, 923, 'text', 'info@witchcase.com', 200),
(1477, 924, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1478, 924, 'text', 'Github repository', 200),
(1479, 925, 'name', 'img_fond_contact', 300),
(1480, 925, 'caption', 'witch on broom shadow', 100),
(1481, 926, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1482, 926, 'filename', 'img_fond_contact.jpg', 100),
(1483, 945, 'meta-title', 'WoodWiccan', 900),
(1484, 945, 'meta-description', 'description du site WoodWiccan', 800),
(1485, 945, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 700),
(1486, 945, 'headline', 'WoodWiccan', 600),
(1487, 928, 'name', 'wc-logo', 300),
(1488, 928, 'caption', '', 100),
(1489, 929, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1490, 929, 'filename', 'wc-logo.jpg', 100),
(1491, 930, 'href', ' mailto:info@witchcase.com', 300),
(1492, 930, 'text', 'info@witchcase.com', 200),
(1493, 931, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1494, 931, 'text', 'Github repository', 200),
(1495, 932, 'name', 'img_fond_contact', 300),
(1496, 932, 'caption', 'witch on broom shadow', 100),
(1497, 933, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1498, 933, 'filename', 'img_fond_contact.jpg', 100),
(1499, 936, 'headline', 'Le CMS', 300),
(1500, 937, 'name', 'ecorce', 300),
(1501, 937, 'caption', '', 100),
(1502, 938, 'storage-path', 'image/jpeg/07dc80a48b17f3ddd11a7a3fbfbedfcc29afad02', 200),
(1503, 938, 'filename', 'img_fond_home.jpg', 100),
(1504, 819, 'headline', 'WoodWiccan', 900),
(1505, 819, 'meta-title', 'WoodWiccan', 400),
(1506, 819, 'meta-description', 'description du site WoodWiccan', 300),
(1507, 819, 'meta-keywords', ' woodwiccan, cms, ecologie, arborescence ', 200),
(1508, 939, 'name', 'img_fond_contact', 300),
(1509, 939, 'caption', 'witch on broom shadow', 100),
(1510, 940, 'storage-path', 'image/jpeg/77f2fbf841a9e524934edd24a15b620f837b37a8', 200),
(1511, 940, 'filename', 'img_fond_contact.jpg', 100),
(1512, 941, 'href', ' mailto:info@witchcase.com', 300),
(1513, 941, 'text', 'info@witchcase.com', 200),
(1514, 942, 'href', 'https://github.com/Jean2Grom/woodwiccan-local', 300),
(1515, 942, 'text', 'Github repository', 200),
(1516, 943, 'name', 'wc-logo', 300),
(1517, 943, 'caption', '', 100),
(1518, 944, 'storage-path', 'image/jpeg/a89030cfb808c802a358df70ae1240f95e0df3c6', 200),
(1519, 944, 'filename', 'wc-logo.jpg', 100),
(1520, 947, 'headline', 'REPRENEZ LE CONTRÔLE', 1000),
(1521, 947, 'headline-left', 'Pour les développeurs', 700),
(1522, 947, 'headline-center', 'Pour les utilisateurs', 500),
(1523, 947, 'headline-right', 'Pour les administrateurs', 300),
(1524, 948, 'name', 'logo_woody', 300),
(1525, 948, 'caption', '', 100),
(1526, 949, 'storage-path', 'image/png/c7d76356065d615e091273f8e87e83a084255ef7', 200),
(1527, 949, 'filename', 'logo_woody.png', 100),
(1528, 950, 'href', '', 300),
(1529, 950, 'text', '', 200),
(1530, 951, 'headline', 'Woody CMS en quelques mots', 1000),
(1531, 951, 'headline-left', 'Malléabilité', 700),
(1532, 951, 'headline-center', 'Spécificité', 500),
(1533, 951, 'headline-right', 'Le projet', 300),
(1534, 952, 'name', 'WoodyEcran', 300),
(1535, 952, 'caption', 'Capture d\'écran de l\'interface d\'administration', 100),
(1536, 953, 'storage-path', 'image/png/ead5d19d7bb2e795438be9673a3238d54fbfe79b', 200),
(1537, 953, 'filename', 'WoodyEcran.png', 100),
(1538, 954, 'href', 'https://github.com/Jean2Grom/witchcase-local', 300),
(1539, 954, 'text', 'Contribuez sur GitHub', 200),
(1540, 955, 'headline', 'Technologies', 300),
(1541, 956, 'name', 'img_fond_technologies', 300),
(1542, 956, 'caption', '', 100),
(1543, 957, 'storage-path', 'image/jpeg/3555edac7c6f332bd287589be6a8c1590bd195df', 200),
(1544, 957, 'filename', 'img_fond_technologies.jpg', 100),
(1545, 958, 'headline', 'FONCTIONNEMENT GLOBAL', 1000),
(1546, 958, 'headline-left', 'Partie <em>View</em>', 700),
(1547, 958, 'headline-center', 'Partie <em>Controller</em>', 500),
(1548, 958, 'headline-right', 'Partie <em>Model</em>', 300),
(1549, 959, 'name', 'MVC-schema-full', 300),
(1550, 959, 'caption', 'schema MVC', 100),
(1551, 960, 'storage-path', 'image/jpeg/d6d89fcffcff022de2ad193be23931a16f2cacd6', 200),
(1552, 960, 'filename', 'MVC-schema-full.jpg', 100),
(1553, 961, 'href', '', 300),
(1554, 961, 'text', '', 200),
(1555, 962, 'headline', 'EMPLACEMENT MATRICIEL', 1000),
(1556, 962, 'headline-left', '', 700),
(1557, 962, 'headline-center', '', 500),
(1558, 962, 'headline-right', '', 300),
(1559, 963, 'name', 'donnees_arbo_schema-full', 300),
(1560, 963, 'caption', '', 100),
(1561, 964, 'storage-path', 'image/jpeg/8f1f9ebcc71f4b12517d4ed664b3cace42f82ad8', 200),
(1562, 964, 'filename', 'donnees_arbo_schema-full.jpg', 100),
(1563, 965, 'href', '', 300),
(1564, 965, 'text', '', 200),
(1565, 966, 'headline', 'CONTENU AJUSTABLE', 1000),
(1566, 966, 'headline-left', '', 700),
(1567, 966, 'headline-center', '', 500),
(1568, 966, 'headline-right', '', 300),
(1569, 967, 'name', 'solution_courante_schema-full', 300),
(1570, 967, 'caption', '', 100),
(1571, 968, 'storage-path', 'image/jpeg/1b8db0dc9fc00f9dd6fea0dc1363a316f0cef982', 200),
(1572, 968, 'filename', 'solution_courante_schema-full.jpg', 100),
(1573, 969, 'href', '', 300),
(1574, 969, 'text', '', 200),
(1575, 970, 'headline', 'NOMMAGE STRUCTUREL DES CHAMPS', 1000),
(1576, 970, 'headline-left', '', 700),
(1577, 970, 'headline-center', '', 500),
(1578, 970, 'headline-right', '', 300),
(1579, 971, 'name', 'nomage_schema_recadre-full', 300),
(1580, 971, 'caption', '', 100),
(1581, 972, 'storage-path', 'image/jpeg/b28fbc61f9a4f8ccf203d53601a6f4f66c4eeeea', 200),
(1582, 972, 'filename', 'nomage_schema_recadre-full.jpg', 100),
(1583, 973, 'href', '', 300),
(1584, 973, 'text', '', 200),
(1585, 974, 'headline', 'À Propos', 300),
(1586, 975, 'name', 'img_fond_apropos', 300),
(1587, 975, 'caption', '', 100),
(1588, 976, 'storage-path', 'image/jpeg/ba08c2c3e6b700117aea2a7b4be4fb77632f29aa', 200),
(1589, 976, 'filename', 'img_fond_apropos.jpg', 100),
(1590, 977, 'headline', 'WITCH CASE EN BREF', 1000),
(1591, 977, 'headline-left', 'Pourquoi ?', 700),
(1592, 977, 'headline-center', 'Les valeurs', 500),
(1593, 977, 'headline-right', 'Et après...', 300),
(1594, 978, 'name', '', 300),
(1595, 978, 'caption', '', 100),
(1596, 979, 'storage-path', '', 200),
(1597, 979, 'filename', '', 100),
(1598, 980, 'href', 'https://github.com/Jean2Grom/witchcase-local', 300),
(1599, 980, 'text', 'Contribuez sur GitHub', 200);

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
(133, 836, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 500),
(135, 920, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 500),
(145, 927, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 800),
(146, 934, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 500),
(147, 945, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 500),
(148, 936, 'body', '<p>L\'outil qui s\'adapte aux métiers</p>', 200),
(149, 819, 'body', '<p>le gestionnaire de contenus web qui s\'adapte aux métiers</p>', 800),
(150, 947, 'body', '', 900),
(151, 947, 'body-left', '<p>Woody CMS est développé sur des technologies purement&nbsp;<strong>PHP/MySQL, en licence GPL</strong>. Le langage PHP est la seule connaissance requise pour y développer un site. Le concept de Woody CMS est une simplification fondamentale du stockage de données&nbsp;:&nbsp;<strong>plus de cache</strong>&nbsp;sur la visualisation des contenus, et aucune perte de temps due au renouvellement de ses fichiers pour valider ses développements. Les éléments contextuels (le menu par exemple) sont cachés par un système très simple et très accessible laissé aux soins du développeur.<br>De plus Woody CMS est une&nbsp;<strong>plateforme multisites</strong>&nbsp;avec un système d\'héritage et laisse une grande liberté dans le développement des modules.<br>En somme Woody CMS fonctionne comme une véritable&nbsp;<strong>\"boite à outils\"</strong>&nbsp;pour les développeurs.</p>', 600),
(152, 947, 'body-center', '<p>A l’heure actuelle, la contribution d’un site web implique un temps de visualisation de son travail en ligne avant de considérer ou non cette tâche achevée. Ce temps de validation doit prendre en compte la gestion du cache qui, suivant son niveau de complexité, peut prendre jusqu\'à 20 minutes pour une seule contribution. Un site implémenté avec Woody CMS permet la&nbsp;<strong>mise en ligne immédiate</strong>&nbsp;d’une information grâce à l’absence de cache de visualisation. Les contributeurs peuvent ainsi valider immédiatement leur travail. De plus Woody CMS est multisite et gère efficacement le&nbsp;<strong>multi-positionnement de contenus</strong>. Les contributeurs peuvent gérer différents sites via&nbsp;<strong>une seule et même interface</strong>, où la modification d’un contenu multipositionné est effective automatiquement à l’ensemble du site. Les visiteurs consultent alors une information instantanément à jour, sans s’impatienter devant un site ralenti par la régénération des fichiers de cache.</p>', 400),
(153, 947, 'body-right', '<p>Woody CMS est un gestionnaire de contenu&nbsp;<strong>malléable</strong>&nbsp;: la forme et les emplacements sont déterminés par les administrateurs de la plateforme. L’administrateur peut créer et modifier à loisir autant de structures de contenus qu\'il souhaite, suivant la forme qu’il recherche. Par exemple la structure&nbsp;<em>article</em>&nbsp;peut contenir une introduction, une image, un lien ou encore un diaporama afin de correspondre exactement à ses besoins. Afin que les nécessités métier ne s’adaptent pas à une structure rigide, Woody CMS range les différents éléments qui constituent le site, suivant une&nbsp;<strong>arborescence choisie et construite en fonction des besoins requis</strong>. Pour les webmaster, Woody CMS propose nativement une gestion multisites avec héritage et une multiposition des éléments, afin de faciliter l\'administration. Pour les administrateurs réseau, une&nbsp;<strong>prise en compte immédiate et sans cache de la configuration</strong>&nbsp;permet l\'ajout et la suppression de host et de siteaccess \"à chaud\".</p>', 200),
(154, 951, 'body', '<p>Voici une copie d\'écran de l\'interface Woody CMS. Ici nous visualisons l\'élément de la home du site de Witch case. Nous distinguons à gauche les informations sur l\'élément&nbsp;<em>home</em>&nbsp;: le module est&nbsp;<em>view</em>&nbsp;(module de visualisation de contenus), le contenu associé n\'est plus un brouillon et n\'est pas une archive, il est indiqué&nbsp;<em>Content</em>. Le type de structure du contenu est&nbsp;<em>home-demo</em>, c\'est-à-dire la page d\'accueil. En dessous, nous avons la partie dédiée à l’emplacement, et plus en dessous encore le tableau des sous-éléments. A droite nous avons une visualisation des attributs du contenu.</p>', 900),
(155, 951, 'body-left', '<p>Woody CMS est un gestionnaire dont l’arborescence et la&nbsp;<strong>structure des contenus sont gérés à 100% par ses utilisateurs</strong>. Cela permet d\'adapter ce CMS aux besoins métiers et non l\'inverse. Woody CMS permet la gestion multisites avec héritage. Dans le cas de plusieurs sites à structure identique, le nouveau site héritera des codes des précédents sites. Il ne restera plus qu’à développer la spécificité du nouveau site. Il permet également la multiposition d\'éléments, afin de&nbsp;<strong>modifier en une seule action</strong>&nbsp;un élément présent sur plusieurs emplacements. Chacune des positions est associée à un module (un fichier PHP), et peut être associé, ou non, à un contenu. Cela permet d\'ajouter facilement un nouveau traitement par le biais d\'un nouveau module. Woody CMS est une véritable \"boite à outils\" qui&nbsp;<strong>offre une grande liberté</strong>&nbsp;au développement et à l\'administration.</p>', 600),
(156, 951, 'body-center', '<p>Woody CMS est fondamentalement orienté vers les professionnels du web, tout en étant fondamentalement flexible aux besoins métiers, tant dans sa structure de données que dans sa gestion de l\'arborescence. Il est tout d’abord dirigé vers le développement&nbsp;: en supprimant la majorité des fichiers de cache, il&nbsp;<strong>diminue significativement les temps</strong>&nbsp;de validation des codes développés. L\'accès au développement de modules spécifique est aussi simplifié&nbsp;: toute la structure des codes est en MVC mais reste à&nbsp;<strong>100% PHP5</strong>. L\'absence de cache de visualisation permet également de réduire les temps de contribution et d\'administration en ayant&nbsp;<strong>instantanément accès à l\'information</strong>&nbsp;par une récupération systématique des données en base.</p>', 400),
(157, 951, 'body-right', '<p>Woody CMS est actuellement un prototype. De nombreuses améliorations sont en cours de développement. Nous ne saurons que trop vous remercier si vous souhaitez&nbsp;<strong>contribuer à son développement</strong>.<br>Il est sous&nbsp;<strong>licence GPL</strong>&nbsp;et développé à&nbsp;<strong>100% en PHP5</strong>, sans utilisation de templating (nous utilisons du code PHP dans les pages de visualisation).<br>Le développement de modules d\'utilisation courante est en cours, l\'ajout d\'attributs possible est également en cours, ainsi que des améliorations au niveau du moteur.<br>Le projet est là et bien vivant,&nbsp;<strong>nous n\'attendons plus que vous !</strong></p>', 200),
(158, 955, 'body', '<p>Les technologies développées par le projet Witchcase</p>', 200),
(159, 958, 'body', '<p>Schéma séquence MVC (simplifié)</p>', 900),
(160, 958, 'body-left', '<p><strong>1.</strong>&nbsp;Pour qu\'une page s\'affiche, nous envoyons une requête HTTP (typiquement avec son navigateur web).<br><strong>8.</strong>&nbsp;Nous avons alors toutes les informations nécessaires pour les traitements du module. Nous récupérons alors le fichier \"design\" du module, qui produira l\'apparence du contenu de la page web (le code HTML de la partie évolutive du site, souvent centrale).&nbsp;<br><strong>10.</strong>&nbsp;Nous récupérons alors le fichier \"design\" (voir plus haut) lié au contexte, on y inclut le résultat du module et nous affichons la page ainsi générée.&nbsp;</p>', 600),
(161, 958, 'body-center', '<p><strong>2.</strong>&nbsp;Le serveur qui reçoit la requête identifie en premier lieu l\'utilisateur (logé ou non).&nbsp;<br><strong>3.</strong>&nbsp;Le système détermine via le fichier de configuration à quel site la requête fait appel, Woody CMS étant un système multi-sites.<br><strong>5.</strong>&nbsp;Cette dernière requête a récupéré l’information du \"module\" souhaité, le système exécute alors le fichier correspondant à ce module.&nbsp;<br><strong>9.</strong>&nbsp;Une fois ce code récupéré, il est stocké et nous déterminons quel sera le contexte (c\'est-à-dire la partie de la page HTML qui est partagée par plusieurs pages et qui revient donc régulièrement), les traitements de ce contexte sont effectués.&nbsp;</p>', 400),
(162, 958, 'body-right', '<p><strong>4.</strong>&nbsp;Une fois le site et l\'URL identifiés, nous déterminons l\'emplacement dans l\'arborescence du contenu désiré en envoyant une requête sur la table des emplacements.&nbsp;<br><strong>6.</strong>&nbsp;La requête d\'emplacement a également fourni le contenu cible appelé ici \"target\". Nous envoyons alors une requête simple sur la structure de la table en base de données qui contient ce contenu.&nbsp;<br><strong>7.</strong>&nbsp;Nous obtenons la structure du contenu. Nous regardons alors si d\'autres informations sont souhaitées et nous ajustons si besoin la requête qui doit récupérer ce contenu.</p>', 200),
(163, 962, 'body', '<p>Le premier schéma nous montre un enregistrement de la table d\'emplacement, \"n\" étant la profondeur maximum de l\'arborescence. C\'est la traduction à l\'échelle unitaire du stockage des coordonnées matricielles. Le schéma suivant démontre la possibilité de situer tout emplacement dans l\'arborescence, en utilisant un champ par niveau de profondeur. Si l’on veut ajouter un élément dont la profondeur dans l\'arborescence est plus grande d\'un niveau que celle prévue par le nombre de champs, le problème est que nous ne pouvons plus situer cet élément dans ce nouveau niveau d\'arborescence. La solution est d\'ajouter un champ \"à chaud\", avec une valeur nulle par défaut. L\'ajout de ce champ n\'altère donc en rien l\'intégrité des données déjà présentes et résout le problème.</p>', 900),
(164, 962, 'body-left', '', 600),
(165, 962, 'body-center', '', 400),
(166, 962, 'body-right', '', 200),
(167, 966, 'body', '<p>Pour un CMS malléable, la solution actuelle et courante de stockage des contenus, comme le montre le premier schéma, est comme le montre&nbsp;le premier schéma : chaque rectangle représente un enregistrement dans une table séparée des autres carrés (ceci est le fonctionnement global, des exceptions peuvent exister notamment si on est en présence de deux attributs de même type). La solution développée pour Woody CMS : une seule table est nécessaire pour stocker l\'ensemble des contenus. Nous noterons que cette innovation est fortement liée à la suivante, qui est de placer la structure dans le titre des champs de la table.</p>', 900),
(168, 966, 'body-left', '', 600),
(169, 966, 'body-center', '', 400),
(170, 966, 'body-right', '', 200),
(171, 970, 'body', '<p>Les noms de champs sont nommés de façon à ce que leur simple lecture permette la structuration complète de la donnée, et ainsi de pouvoir ajuster la requête qui doit récupérer le contenu. Nous avons besoin que d\'une seule requête pour récupérer tous les contenus utiles en base de données.</p>', 900),
(172, 970, 'body-left', '', 600),
(173, 970, 'body-center', '', 400),
(174, 970, 'body-right', '', 200),
(175, 974, 'body', '<p>Réconcilier l\'informatique avec ses utilisateurs</p>', 200),
(176, 977, 'body', '', 900),
(177, 977, 'body-left', '<p>C’est en constatant que le développement informatique relève d’une&nbsp;<strong>nouvelle forme d’artisanat</strong>, contrairement à l’évolution actuelle du métier de développeur, que Witch case est née. L\'industrialisation et la normalisation des besoins actuellement en cours dans la profession nuit à l’<strong>adaptabilité</strong>, alors qu’elle représente une question&nbsp;<strong>primordiale</strong>&nbsp;dans le domaine de l’informatique, et plus spécifiquement dans celui du web. C’est par la volonté d’affirmer notre vision du métier, en proposant des produits&nbsp;<strong>innovants et de qualité</strong>, que Witch case a été fondé.</p>', 600),
(178, 977, 'body-center', '<p>L\'<strong>intégrité</strong>&nbsp;est sans doute la première des valeurs de Witch case, le respect humain et l\'honnêteté sont des points sur lesquels nous ne pouvons transiger. La&nbsp;<strong>créativité</strong>&nbsp;et la&nbsp;<strong>qualité</strong>&nbsp;sont les valeurs fondamentales de Witch case, elles représentent le cœur de métier et nous sont indispensables pour proposer des solutions fiables, convaincantes et innovantes. Enfin c’est l’<strong>humanisme</strong>&nbsp;qui nous pousse à développer des produits&nbsp;<strong>utiles</strong>&nbsp;et&nbsp;<strong>libres</strong>, Il nous est essentiel que l’ensemble des utilisateurs puissent se réconcilier avec les outils informatiques.</p>', 400),
(179, 977, 'body-right', '<p>Si Woody CMS est la première réalisation de Witch case, il existe déjà d\'autres projets engagés vers une future réalisation. Les technologies développées à travers Woody CMS peuvent se décliner vers d\'autres domaines d\'applications. La vision d’avenir de Witch case est une&nbsp;<strong>coopérative</strong>, avec une répartition&nbsp;<strong>collective</strong>&nbsp;des tâches et des décisions, afin de préserver la&nbsp;<strong>vision artisanale</strong>&nbsp;et non industrielle du métier d\'édition, et donc de garantir un haut niveau de qualité. Si vous êtes intéressé par cette aventure, nous ne saurions que trop vous conseiller de participer aux&nbsp;<strong>développements sur gitHub</strong>&nbsp;et n\'<strong>hésitez pas à nous contacter</strong>&nbsp;si vous le souhaitez, nous vous répondrons avec grand plaisir !</p>', 200);

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
(6, 'Jean', 'jean.de.gromard@gmail.com', 'jean', '$2y$10$eQKR0F153VZ5UExkbgQS/O9nnUIGGlw4WNQoPeGIG1Xiv53dAEzrG', NULL, '2025-06-12 10:48:02', NULL, '2025-06-12 10:47:16');

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
(6, 2, '*', NULL, 181, 0, 1, 1, '');

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
(2, 'public', '*', '2024-03-01 15:46:01');

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
(3, 'Utilisateurs', '', 'admin', 'utilisateurs', 0, '', NULL, 0, NULL, '2024-03-01 15:46:01', -300, 1, 1, NULL, NULL, NULL),
(4, 'Administrateur', '', 'admin', 'utilisateurs/administrateur', 0, '', 4, 200, '', '2024-03-01 15:46:01', 0, 1, 1, 1, NULL, NULL),
(5, 'Home', '', 'admin', '', 0, 'root', NULL, 0, NULL, '2024-03-01 15:46:01', -400, 1, 2, NULL, NULL, NULL),
(6, 'Login', 'Module de déconnexion/connexion', 'admin', 'login', 0, 'login', NULL, 0, NULL, '2024-03-01 15:46:01', 800, 1, 2, 1, NULL, NULL),
(7, 'Witch', 'Visualisation des Witches, c\'est a dire de chaque point de l\'arborescence -appelé ici Matriarcat. Chacun de ces points peut être associé à un contenu et/ou à un module exécutable. \r\nOn peut également définir une URL permettant de cibler cette witch.', 'admin', 'view', 0, 'witch/view', NULL, 0, NULL, '2024-03-01 15:46:01', 700, 1, 2, 2, NULL, NULL),
(8, 'Edit Witch', '', 'admin', 'edit', 0, 'witch/edit', NULL, 0, NULL, '2024-03-01 15:46:01', 500, 1, 2, 3, NULL, NULL),
(10, 'Menu', '', NULL, NULL, 0, NULL, NULL, 0, NULL, '2024-03-01 15:46:01', 200, 1, 2, 5, NULL, NULL),
(11, 'Profiles', 'Permissions handeling is based on user profiles.', 'admin', 'profiles', 0, 'profiles', NULL, 0, NULL, '2024-03-01 15:46:01', 600, 1, 2, 5, 1, NULL),
(13, 'Apply', '', 'admin', 'apply', 0, 'emptyCache', NULL, 0, NULL, '2024-03-01 15:46:28', 300, 1, 2, 5, 3, NULL),
(15, 'Recipes', 'Les données sont stockées sous la forme de structures qui sont éditables ici.', 'admin', 'recipe', 0, 'recipe/list', NULL, 0, NULL, '2024-03-09 15:48:02', 500, 1, 2, 5, 4, NULL),
(16, 'Cauldron', '', 'admin', 'cauldron', 0, 'cauldron', NULL, 0, NULL, '2024-04-08 15:04:18', 600, 1, 2, 9, NULL, NULL),
(25, 'View Structure', 'Cauldron\'s inside element\'s structure visualization', 'admin', 'recipe/view', 0, 'recipe/view', NULL, 0, NULL, '2024-06-11 13:57:05', 0, 1, 2, 5, 4, 1),
(26, 'Edit Structure', '', 'admin', 'recipe/edit', 0, 'recipe/edit', NULL, 0, NULL, '2024-06-13 13:54:51', 0, 1, 2, 5, 4, 2),
(27, 'Create Structure', '', 'admin', 'recipe/create', 0, 'recipe/create', NULL, 0, NULL, '2024-08-07 15:05:40', 0, 1, 2, 5, 4, 3),
(30, 'Create Witch', '', 'admin', 'create-witch', 0, 'witch/create', NULL, 0, NULL, '2024-09-24 21:02:27', 300, 1, 2, 7, NULL, NULL),
(37, 'Witch', 'Witch Folder', 'admin', NULL, 0, NULL, NULL, 0, NULL, '2024-10-01 13:59:43', 100, 1, 2, 8, NULL, NULL),
(38, 'clipboard', NULL, 'admin', 'clipboard', 0, 'witch/clipboard', NULL, 0, NULL, '2024-10-01 15:24:28', 0, 1, 2, 8, 1, NULL),
(127, 'Cauldrons', NULL, 'admin', 'cauldrons', 0, 'cauldrons', NULL, 0, NULL, '2024-11-26 15:11:01', 200, 1, 2, 5, 5, NULL),
(129, 'Jean', '', 'admin', NULL, 0, NULL, 7, 0, NULL, '2025-01-10 16:01:18', 0, 1, 1, 2, NULL, NULL),
(180, 'Site Demo', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL, '2025-06-17 08:25:10', 0, 3, NULL, NULL, NULL, NULL),
(181, 'Home', 'Home landing page for demo site', 'demo', '', 0, 'blank', 819, 0, NULL, '2025-06-17 08:39:29', 0, 3, 1, NULL, NULL, NULL),
(182, 'Le CMS', NULL, 'demo', 'le-cms', 0, 'default', 936, 0, NULL, '2025-06-19 13:52:30', 500, 3, 1, 1, NULL, NULL),
(183, 'REPRENEZ LE CONTRÔLE', 'Woody CMS est le premier CMS dont la technologie est orientée vers les acteurs du web. Que vous soyez développeur, contributeur, webmaster ou même administrateur réseau, ce gestionnaire de contenu (CMS) a pour objectif de vous simplifier la vie en supprimant les attentes interminables dues au fonctionnement d\'un site web, tout en bénéficiant de la malléabilité exigée d\'un site qui vous représente.', 'demo', NULL, 0, NULL, 947, 0, NULL, '2025-06-23 15:17:13', 0, 3, 1, 1, 1, NULL),
(184, 'Woody CMS en quelques mots', 'Woody CMS est un prototype de gestion de contenu, à partir duquel on développe, contribue et administre un site web. Woody CMS propose un modèle de stockage des données en base, gère la sécurité, assure une partition MVC des codes, et propose une interface web pour administrer son ou ses site(s).', 'demo', NULL, 0, NULL, 951, 0, NULL, '2025-06-23 17:05:47', 0, 3, 1, 1, 2, NULL),
(185, 'Technologies', NULL, 'demo', 'technologies', 0, 'default', 955, 0, NULL, '2025-06-24 08:00:18', 400, 3, 1, 2, NULL, NULL),
(186, 'FONCTIONNEMENT GLOBAL', 'Ici nous représentons les flux avec un diagramme de séquence, depuis la requête du navigateur jusqu\'à la visualisation de la page. Nous avons mis ici en valeur la structure MVC (Model View Controller) de Woody CMS.', 'demo', NULL, 0, NULL, 958, 0, NULL, '2025-06-24 08:03:02', 500, 3, 1, 2, 1, NULL),
(187, 'EMPLACEMENT MATRICIEL', 'Une des innovations utilisées par Woody CMS est la gestion de l\'arborescence avec des coordonnées matricielles. Cette technique permet d’avoir qu\'une unique requête pour déterminer l\'arborescence de l\'emplacement auquel nous accèdons.', 'demo', NULL, 0, NULL, 962, 0, NULL, '2025-06-25 16:43:36', 400, 3, 1, 2, 2, NULL),
(188, 'CONTENU AJUSTABLE', 'Afin de récupérer toutes les informations du contenu à afficher, Witch case a développé une solution qui consiste à ajuster en direct la table correspondant au contenu à afficher. Ainsi, on peut récupérer l\'ensemble des informations en une seule requête.', 'demo', NULL, 0, NULL, 966, 0, NULL, '2025-06-25 16:47:03', 300, 3, 1, 2, 3, NULL),
(189, 'NOMMAGE STRUCTUREL DES CHAMPS', 'Afin de permettre une plus grande complexité des attributs qui composent un contenu, il faut connaitre sa structure et son comportement avant d\'envoyer la requête visant à récupérer les contenus. Pour cela nous identifions les champs en BDD par un nommage structurel.', 'demo', NULL, 0, NULL, 970, 0, NULL, '2025-06-25 16:49:33', 200, 3, 1, 2, 4, NULL),
(190, 'À Propos', NULL, 'demo', 'a-propos', 0, 'default', 974, 0, NULL, '2025-06-25 16:52:32', 300, 3, 1, 3, NULL, NULL),
(191, 'WITCH CASE EN BREF', 'Witch case est une société d\'édition web crée en 2016 par Jean de Gromard. Ingénieur de formation, il a passé 10 ans dans les technologies du web dont 5 à se spécialiser dans l\'intégration de site via des CMS, avec une expertise sur eZPublish. Woody CMS est le premier projet de Witch case. Le prototype a été développé entre 2015 et 2016, sur une durée d\'environ un an.', 'demo', NULL, 0, NULL, 977, 0, NULL, '2025-06-25 16:56:31', 0, 3, 1, 3, 1, NULL),
(192, 'Contacter', NULL, 'demo', 'contacter', 0, 'contact', NULL, 0, NULL, '2025-06-25 17:01:12', 200, 3, 1, 4, NULL, NULL);

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
  ADD KEY `IDX_level_7` (`level_7`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=981;

--
-- AUTO_INCREMENT pour la table `ingredient__boolean`
--
ALTER TABLE `ingredient__boolean`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT pour la table `ingredient__price`
--
ALTER TABLE `ingredient__price`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `ingredient__string`
--
ALTER TABLE `ingredient__string`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1600;

--
-- AUTO_INCREMENT pour la table `ingredient__text`
--
ALTER TABLE `ingredient__text`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT pour la table `user__connexion`
--
ALTER TABLE `user__connexion`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user__policy`
--
ALTER TABLE `user__policy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user__profile`
--
ALTER TABLE `user__profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `witch`
--
ALTER TABLE `witch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;
COMMIT;
