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
  `player_seqno` smallint DEFAULT NULL COMMENT 'Σειρά στη βεντάλια του παίκτη',
  PRIMARY KEY (`id`),
  KEY `FK_card_player` (`player_id`),
  CONSTRAINT `FK_card_player` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Dumping data for table adise21_185383.card: ~41 rows (approximately)
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` (`id`, `figure`, `symbol`, `player_id`, `player_seqno`) VALUES
	(1, 'A', 'Κούπα', 1, 37),
	(2, 'A', 'Σπαθί', 1, 5),
	(3, 'A', 'Καρό', 2, 20),
	(4, 'A', 'Μπαστούνι', 2, 28),
	(5, '2', 'Κούπα', 2, 0),
	(6, '2', 'Σπαθί', 1, 19),
	(7, '2', 'Καρό', 1, 9),
	(8, '2', 'Μπαστούνι', 1, 23),
	(9, '3', 'Κούπα', 2, 32),
	(10, '3', 'Σπαθί', 2, 34),
	(11, '3', 'Καρό', 2, 10),
	(12, '3', 'Μπαστούνι', 2, 8),
	(13, '4', 'Κούπα', 2, 30),
	(14, '4', 'Σπαθί', 2, 26),
	(15, '4', 'Καρό', 1, 7),
	(16, '4', 'Μπαστούνι', 2, 18),
	(17, '5', 'Κούπα', 2, 14),
	(18, '5', 'Σπαθί', 1, 11),
	(19, '5', 'Καρό', 2, 36),
	(20, '5', 'Μπαστούνι', 2, 2),
	(21, '6', 'Κούπα', 1, 29),
	(22, '6', 'Σπαθί', 2, 16),
	(23, '6', 'Καρό', 1, 15),
	(24, '6', 'Μπαστούνι', 1, 1),
	(25, '7', 'Κούπα', 2, 40),
	(26, '7', 'Σπαθί', 1, 21),
	(27, '7', 'Καρό', 1, 39),
	(28, '7', 'Μπαστούνι', 2, 12),
	(29, '8', 'Κούπα', 1, 17),
	(30, '8', 'Σπαθί', 2, 22),
	(31, '8', 'Καρό', 1, 13),
	(32, '8', 'Μπαστούνι', 1, 33),
	(33, '9', 'Κούπα', 1, 3),
	(34, '9', 'Σπαθί', 2, 6),
	(35, '9', 'Καρό', 1, 27),
	(36, '9', 'Μπαστούνι', 1, 31),
	(37, '10', 'Κούπα', 1, 35),
	(38, '10', 'Σπαθί', 2, 24),
	(39, '10', 'Καρό', 1, 25),
	(40, '10', 'Μπαστούνι', 2, 38),
	(41, 'K', 'Μπαστούνι', 2, 4);
/*!40000 ALTER TABLE `card` ENABLE KEYS */;

-- Dumping structure for πίνακας adise21_185383.player
CREATE TABLE IF NOT EXISTS `player` (
  `id` int NOT NULL,
  `playing_id` int NOT NULL,
  `playing_iscurrent` smallint NOT NULL DEFAULT '0',
  `state` smallint NOT NULL DEFAULT '1' COMMENT 'Κατάσταση (1.Ένταξη, 2. Aπόρριψη διπλών, 3. Επιλογή χαρτιού)',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `FK_player_playing` (`playing_id`),
  CONSTRAINT `FK_player_playing` FOREIGN KEY (`playing_id`) REFERENCES `playing` (`id`),
  CONSTRAINT `FK_player_user` FOREIGN KEY (`id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- Dumping data for table adise21_185383.player: ~2 rows (approximately)
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` (`id`, `playing_id`, `playing_iscurrent`, `state`) VALUES
	(1, 9, 1, 1),
	(2, 9, 0, 1);
/*!40000 ALTER TABLE `player` ENABLE KEYS */;

-- Dumping structure for πίνακας adise21_185383.playing
CREATE TABLE IF NOT EXISTS `playing` (
  `id` int NOT NULL AUTO_INCREMENT,
  `active` smallint NOT NULL DEFAULT '0' COMMENT 'Ενεργό (0.Όχι, 1.Ναι)',
  `phase` smallint NOT NULL DEFAULT '0' COMMENT 'Φάση (0.Αρχική, 1.Ένταξη παικτών, 2.Αρχική απόρριψη διπλών, 3.Παιχνίδι, 4.Τερματισμός)',
  `player_cnt` smallint NOT NULL DEFAULT '0' COMMENT 'Πλήθος παικτών',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- Dumping data for table adise21_185383.playing: ~1 rows (approximately)
/*!40000 ALTER TABLE `playing` DISABLE KEYS */;
INSERT INTO `playing` (`id`, `active`, `phase`, `player_cnt`) VALUES
	(9, 1, 2, 2);
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
