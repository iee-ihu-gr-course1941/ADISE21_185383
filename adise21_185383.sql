-- --------------------------------------------------------
-- Διακομιστής:                  127.0.0.1
-- Έκδοση διακομιστή:            8.0.27 - MySQL Community Server - GPL
-- Λειτ. σύστημα διακομιστή:     Win64
-- HeidiSQL Έκδοση:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for adise21_185383
CREATE DATABASE IF NOT EXISTS `adise21_185383` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `adise21_185383`;

-- Dumping structure for πίνακας adise21_185383.card
CREATE TABLE IF NOT EXISTS `card` (
  `id` int NOT NULL,
  `figure` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'Φιγούρα (A, 2....10, K, Q, J)',
  `symbol` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Κούπα' COMMENT 'Σύμβολο (Κούπα, Σπαθί, Καρό, Μπαστούνι)',
  `player_id` int DEFAULT NULL,
  `playing_id` int DEFAULT NULL,
  `player_seqno` smallint DEFAULT NULL COMMENT 'Σειρά στη βεντάλια του παίκτη',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Dumping data for table adise21_185383.card: ~41 rows (approximately)
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` (`id`, `figure`, `symbol`, `player_id`, `playing_id`, `player_seqno`) VALUES
	(1, 'A', 'Κούπα', NULL, NULL, NULL),
	(2, 'A', 'Σπαθί', NULL, NULL, NULL),
	(3, 'A', 'Καρό', NULL, NULL, NULL),
	(4, 'A', 'Μπαστούνι', NULL, NULL, NULL),
	(5, '2', 'Κούπα', NULL, NULL, NULL),
	(6, '2', 'Σπαθί', NULL, NULL, NULL),
	(7, '2', 'Καρό', NULL, NULL, NULL),
	(8, '2', 'Μπαστούνι', NULL, NULL, NULL),
	(9, '3', 'Κούπα', NULL, NULL, NULL),
	(10, '3', 'Σπαθί', NULL, NULL, NULL),
	(11, '3', 'Καρό', NULL, NULL, NULL),
	(12, '3', 'Μπαστούνι', NULL, NULL, NULL),
	(13, '4', 'Κούπα', NULL, NULL, NULL),
	(14, '4', 'Σπαθί', NULL, NULL, NULL),
	(15, '4', 'Καρό', NULL, NULL, NULL),
	(16, '4', 'Μπαστούνι', NULL, NULL, NULL),
	(17, '5', 'Κούπα', NULL, NULL, NULL),
	(18, '5', 'Σπαθί', NULL, NULL, NULL),
	(19, '5', 'Καρό', NULL, NULL, NULL),
	(20, '5', 'Μπαστούνι', NULL, NULL, NULL),
	(21, '6', 'Κούπα', NULL, NULL, NULL),
	(22, '6', 'Σπαθί', NULL, NULL, NULL),
	(23, '6', 'Καρό', NULL, NULL, NULL),
	(24, '6', 'Μπαστούνι', NULL, NULL, NULL),
	(25, '7', 'Κούπα', NULL, NULL, NULL),
	(26, '7', 'Σπαθί', NULL, NULL, NULL),
	(27, '7', 'Καρό', NULL, NULL, NULL),
	(28, '7', 'Μπαστούνι', NULL, NULL, NULL),
	(29, '8', 'Κούπα', 2, 19, 42),
	(30, '8', 'Σπαθί', NULL, NULL, NULL),
	(31, '8', 'Καρό', 2, 19, 3),
	(32, '8', 'Μπαστούνι', NULL, NULL, NULL),
	(33, '9', 'Κούπα', NULL, NULL, NULL),
	(34, '9', 'Σπαθί', 2, 19, 43),
	(35, '9', 'Καρό', NULL, NULL, NULL),
	(36, '9', 'Μπαστούνι', NULL, NULL, NULL),
	(37, '10', 'Κούπα', NULL, NULL, NULL),
	(38, '10', 'Σπαθί', NULL, NULL, NULL),
	(39, '10', 'Καρό', NULL, NULL, NULL),
	(40, '10', 'Μπαστούνι', NULL, NULL, NULL),
	(41, 'K', 'Μπαστούνι', 2, 19, 41);
/*!40000 ALTER TABLE `card` ENABLE KEYS */;

-- Dumping structure for πίνακας adise21_185383.player
CREATE TABLE IF NOT EXISTS `player` (
  `playing_id` int NOT NULL,
  `id` int NOT NULL,
  `playing_iscurrent` smallint NOT NULL DEFAULT '0',
  `state` smallint NOT NULL DEFAULT '1' COMMENT 'Κατάσταση (1.Ένταξη, 2. Aπόρριψη διπλών, 3. Επιλογή χαρτιού)',
  `final_card_cnt` smallint DEFAULT '0',
  PRIMARY KEY (`playing_id`,`id`),
  KEY `FK_player_user` (`id`),
  CONSTRAINT `FK_player_playing` FOREIGN KEY (`playing_id`) REFERENCES `playing` (`id`),
  CONSTRAINT `FK_player_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- Dumping data for table adise21_185383.player: ~2 rows (approximately)
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` (`playing_id`, `id`, `playing_iscurrent`, `state`, `final_card_cnt`) VALUES
	(19, 1, 0, 2, 0),
	(19, 2, 1, 2, 4);
/*!40000 ALTER TABLE `player` ENABLE KEYS */;

-- Dumping structure for πίνακας adise21_185383.playing
CREATE TABLE IF NOT EXISTS `playing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `active` smallint NOT NULL DEFAULT '0' COMMENT 'Ενεργό (0.Όχι, 1.Ναι)',
  `phase` smallint NOT NULL DEFAULT '0' COMMENT 'Φάση (0.Αρχική, 1.Ένταξη παικτών, 2.Αρχική απόρριψη διπλών, 3.Παιχνίδι, 4.Τερματισμός)',
  `player_cnt` smallint NOT NULL DEFAULT '0' COMMENT 'Πλήθος παικτών',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Dumping data for table adise21_185383.playing: ~1 rows (approximately)
/*!40000 ALTER TABLE `playing` DISABLE KEYS */;
INSERT INTO `playing` (`id`, `active`, `phase`, `player_cnt`) VALUES
	(19, 1, 4, 2);
/*!40000 ALTER TABLE `playing` ENABLE KEYS */;

-- Dumping structure for πίνακας adise21_185383.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table adise21_185383.user: ~6 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `password`) VALUES
	(1, 'player1', '1'),
	(2, 'player2', '2'),
	(3, 'player3', '3'),
	(4, 'player4', '4'),
	(5, 'player5', '5'),
	(6, 'player6', '6');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
