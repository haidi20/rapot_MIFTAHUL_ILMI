-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table raport.absen
CREATE TABLE IF NOT EXISTS `absen` (
  `id` varchar(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `quiz_student_id` varchar(50) NOT NULL,
  `absen_type_id` varchar(50) NOT NULL,
  `date_absen_id` varchar(50) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.absen_type
CREATE TABLE IF NOT EXISTS `absen_type` (
  `id` varchar(50) DEFAULT NULL,
  `name_absen_type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.class_room
CREATE TABLE IF NOT EXISTS `class_room` (
  `id` varchar(50) DEFAULT NULL,
  `name_class_room` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `name_speaker` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.quiz
CREATE TABLE IF NOT EXISTS `quiz` (
  `id` varchar(50) DEFAULT NULL,
  `name_quiz` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.quiz_date
CREATE TABLE IF NOT EXISTS `quiz_date` (
  `id` varchar(50) DEFAULT NULL,
  `quiz_id` varchar(50) DEFAULT NULL,
  `class_room_id` varchar(50) DEFAULT NULL,
  `date` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.quiz_student
CREATE TABLE IF NOT EXISTS `quiz_student` (
  `id` varchar(50) DEFAULT NULL,
  `class_room_id` varchar(50) DEFAULT NULL,
  `quiz_id` varchar(50) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `grade` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table raport.student
CREATE TABLE IF NOT EXISTS `student` (
  `id` varchar(50) DEFAULT NULL,
  `name_student` varchar(50) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
