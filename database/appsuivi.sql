-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 04, 2023 at 12:13 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appsuivi`
--

-- --------------------------------------------------------

--
-- Table structure for table `cnss`
--

DROP TABLE IF EXISTS `cnss`;
CREATE TABLE IF NOT EXISTS `cnss` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cs`
--

DROP TABLE IF EXISTS `cs`;
CREATE TABLE IF NOT EXISTS `cs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foncier`
--

DROP TABLE IF EXISTS `foncier`;
CREATE TABLE IF NOT EXISTS `foncier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `i_s`
--

DROP TABLE IF EXISTS `i_s`;
CREATE TABLE IF NOT EXISTS `i_s` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `trimester` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `i_s`
--

INSERT INTO `i_s` (`id`, `id_society`, `notified`, `paied`, `trimester`, `year`, `file`) VALUES
(1, 1, 0, 0, 1, 2023, NULL),
(2, 1, 0, 0, 2, 2023, NULL),
(3, 1, 0, 0, 3, 2023, NULL),
(4, 1, 0, 0, 4, 2023, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salarier`
--

DROP TABLE IF EXISTS `salarier`;
CREATE TABLE IF NOT EXISTS `salarier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `society`
--

DROP TABLE IF EXISTS `society`;
CREATE TABLE IF NOT EXISTS `society` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `society`
--

INSERT INTO `society` (`id`, `name`) VALUES
(1, 'htm');

-- --------------------------------------------------------

--
-- Table structure for table `tc`
--

DROP TABLE IF EXISTS `tc`;
CREATE TABLE IF NOT EXISTS `tc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tp`
--

DROP TABLE IF EXISTS `tp`;
CREATE TABLE IF NOT EXISTS `tp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tva_mens`
--

DROP TABLE IF EXISTS `tva_mens`;
CREATE TABLE IF NOT EXISTS `tva_mens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `month` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tva_mens`
--

INSERT INTO `tva_mens` (`id`, `id_society`, `notified`, `paied`, `month`, `year`, `file`) VALUES
(24, 1, 0, 0, 12, 2023, NULL),
(23, 1, 0, 0, 11, 2023, NULL),
(22, 1, 0, 0, 10, 2023, NULL),
(21, 1, 1, 0, 9, 2023, NULL),
(20, 1, 0, 0, 8, 2023, NULL),
(19, 1, 0, 0, 7, 2023, NULL),
(18, 1, 0, 0, 6, 2023, NULL),
(17, 1, 0, 0, 5, 2023, NULL),
(16, 1, 0, 0, 4, 2023, NULL),
(15, 1, 0, 0, 3, 2023, NULL),
(14, 1, 0, 0, 2, 2023, NULL),
(13, 1, 0, 0, 1, 2023, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tva_tri`
--

DROP TABLE IF EXISTS `tva_tri`;
CREATE TABLE IF NOT EXISTS `tva_tri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_society` int NOT NULL,
  `notified` tinyint(1) NOT NULL,
  `paied` tinyint(1) NOT NULL,
  `trimester` int NOT NULL,
  `year` int NOT NULL,
  `file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `isAdmin`) VALUES
(1, 'admin', '123', 1),
(2, 'haytham', '123', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_society`
--

DROP TABLE IF EXISTS `user_society`;
CREATE TABLE IF NOT EXISTS `user_society` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_society` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_society` (`id_society`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_society`
--

INSERT INTO `user_society` (`id`, `id_user`, `id_society`) VALUES
(1, 2, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
