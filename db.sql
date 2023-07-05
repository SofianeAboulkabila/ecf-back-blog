-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 02 juil. 2023 à 15:23
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecf_back`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `confirmation_token` varchar(60) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONS POUR LA TABLE `users`:
--

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `confirmation_token`, `role`, `confirmed`) VALUES
(1, 'taryn27', 'ZvahNGD`9', 'johns.angeline@sporer.info', 'baeb02dc-fe63-31c1-ad09-1ec8ce30122e', 'ROLE_ADMIN', 1),
(2, 'jfeeney', '{-tsER@myBF87,L(I[6', 'wunsch.milo@graham.net', '0d85f32a-9b43-3f62-8b0c-320f30198e5f', 'ROLE_USER', 1),
(3, 'bkohler', 'X_/z+sy\'!|&', 'cbergnaum@schimmel.org', '5cba12e0-7123-3884-858a-bcf90993299a', 'ROLE_ADMIN', 0),
(4, 'fay.monserrate', '[A#jEoz18c|+F+P_D\\D', 'rudy98@gmail.com', '1599f781-8bcd-3806-8bb1-f865f72e0509', 'ROLE_USER', 0),
(5, 'telly.kuphal', '~7t~RHmN0', 'jarred74@hills.org', 'b72cd351-e2b3-338b-b397-a75b3d241c49', 'ROLE_ADMIN', 1),
(6, 'chahn', 'z6}[r$xLg+', 'corkery.gustave@kuphal.com', '0ea8958e-2b5b-3a2f-af24-0f3cb506c86f', 'ROLE_USER', 0),
(7, 'aryanna.hackett', '4sUv/p-.CZ!VPVL^wB7', 'elody.lockman@hoeger.com', '8196c54e-5df1-38dd-8d95-ed09c8bafd49', 'ROLE_USER', 1),
(8, 'adella.cartwright', '9CV7%Bs+z8\"*@', 'osanford@gmail.com', '72ae85e3-5e67-356a-93a7-5df3a38d158e', 'ROLE_MOD', 0),
(9, 'aubree94', '2,QN<jUCM[PC^8[MWG', 'larson.gerry@gmail.com', '75204cf4-f9e4-377b-92dc-2f3c58f3942f', 'ROLE_ADMIN', 0),
(10, 'janice.rutherford', 'cLb>M3ZFz', 'ferne.quigley@becker.com', '948d05db-56c6-3d1b-89d4-c6a985659fab', 'ROLE_ADMIN', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
