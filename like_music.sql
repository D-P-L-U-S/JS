-- phpMyAdmin SQL Dump
-- version 5.2.0-dev+20220313.dac7e34f93
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 05 mai 2023 à 15:38
-- Version du serveur : 5.7.34
-- Version de PHP : 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `like_music`
--

-- --------------------------------------------------------

--
-- Structure de la table `journal_secret`
--

CREATE TABLE `journal_secret` (
  `ID` int(11) NOT NULL,
  `Titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Contenu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Dates` datetime NOT NULL,
  `Auteur` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `journal_secret`
--


-- --------------------------------------------------------

--
-- Structure de la table `user_js`
--

CREATE TABLE `user_js` (
  `ID` int(11) NOT NULL,
  `Pseudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Nom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Prenom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Date_inscription` datetime NOT NULL,
  `Theme` int(11) NOT NULL DEFAULT '2',
  `Statut` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user_js`
--


--
-- Index pour les tables déchargées
--

--
-- Index pour la table `journal_secret`
--
ALTER TABLE `journal_secret`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `user_js`
--
ALTER TABLE `user_js`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `journal_secret`
--
ALTER TABLE `journal_secret`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT pour la table `user_js`
--
ALTER TABLE `user_js`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
