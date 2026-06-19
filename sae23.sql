-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2026 at 05:31 
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sae23`
--
CREATE DATABASE IF NOT EXISTS `sae23` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sae23`;

-- --------------------------------------------------------

--
-- Table structure for table `Administration`
--

CREATE TABLE IF NOT EXISTS `Administration` (
  `login` varchar(30) NOT NULL DEFAULT 'Admin',
  `mdp` varchar(30) NOT NULL DEFAULT 'typrshit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Administration`
--

INSERT INTO `Administration` (`login`, `mdp`) VALUES
('Admin', 'typrshit');

-- --------------------------------------------------------

--
-- Table structure for table `Batiment`
--

CREATE TABLE IF NOT EXISTS `Batiment` (
`id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `login_gestionnaire` varchar(50) NOT NULL,
  `mdp_gestionnaire` varchar(32) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Batiment`
--

INSERT INTO `Batiment` (`id`, `nom`, `login_gestionnaire`, `mdp_gestionnaire`) VALUES
(1, 'Batiment RT', 'gestionnaire_rt', 'passgestion');

-- --------------------------------------------------------

--
-- Table structure for table `Capteur`
--

CREATE TABLE IF NOT EXISTS `Capteur` (
  `nom` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `unite` varchar(10) NOT NULL,
  `nom_salle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Capteur`
--

INSERT INTO `Capteur` (`nom`, `type`, `unite`, `nom_salle`) VALUES
('CO2_E208', 'CO2', 'ppm', 'E208'),
('Temp_E105', 'Temperature', '°C', 'E105');

-- --------------------------------------------------------

--
-- Table structure for table `Mesure`
--

CREATE TABLE IF NOT EXISTS `Mesure` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `horaire` time NOT NULL,
  `valeur` decimal(10,2) NOT NULL,
  `nom_capteur` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Mesure`
--

INSERT INTO `Mesure` (`id`, `date`, `horaire`, `valeur`, `nom_capteur`) VALUES
(1, '2026-06-07', '14:00:00', '21.50', 'Temp_E105');

-- --------------------------------------------------------

--
-- Table structure for table `Salle`
--

CREATE TABLE IF NOT EXISTS `Salle` (
  `nom` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT 'Cours',
  `capacite` int(11) DEFAULT '30',
  `id_batiment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Salle`
--

INSERT INTO `Salle` (`nom`, `type`, `capacite`, `id_batiment`) VALUES
('E105', 'TP RT', 24, 1),
('E208', 'Cours', 30, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Administration`
--
ALTER TABLE `Administration`
 ADD PRIMARY KEY (`login`);

--
-- Indexes for table `Batiment`
--
ALTER TABLE `Batiment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Capteur`
--
ALTER TABLE `Capteur`
 ADD PRIMARY KEY (`nom`), ADD KEY `nom_salle` (`nom_salle`);

--
-- Indexes for table `Mesure`
--
ALTER TABLE `Mesure`
 ADD PRIMARY KEY (`id`), ADD KEY `nom_capteur` (`nom_capteur`);

--
-- Indexes for table `Salle`
--
ALTER TABLE `Salle`
 ADD PRIMARY KEY (`nom`), ADD KEY `id_batiment` (`id_batiment`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Batiment`
--
ALTER TABLE `Batiment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Mesure`
--
ALTER TABLE `Mesure`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Capteur`
--
ALTER TABLE `Capteur`
ADD CONSTRAINT `Capteur_ibfk_1` FOREIGN KEY (`nom_salle`) REFERENCES `Salle` (`nom`) ON DELETE CASCADE;

--
-- Constraints for table `Mesure`
--
ALTER TABLE `Mesure`
ADD CONSTRAINT `Mesure_ibfk_1` FOREIGN KEY (`nom_capteur`) REFERENCES `Capteur` (`nom`) ON DELETE CASCADE;

--
-- Constraints for table `Salle`
--
ALTER TABLE `Salle`
ADD CONSTRAINT `Salle_ibfk_1` FOREIGN KEY (`id_batiment`) REFERENCES `Batiment` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
