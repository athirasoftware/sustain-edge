-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sustain_edge
CREATE DATABASE IF NOT EXISTS `sustain_edge` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `sustain_edge`;

-- Dumping structure for table sustain_edge.company
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_of_org` varchar(255) DEFAULT NULL,
  `business_email` varchar(255) NOT NULL,
  `size_of_org` int(11) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `sub_industry` varchar(255) DEFAULT NULL,
  `head_quarters` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `org_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.company: ~3 rows (approximately)
INSERT INTO `company` (`id`, `name_of_org`, `business_email`, `size_of_org`, `industry`, `sub_industry`, `head_quarters`, `country`, `img_path`, `org_url`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Test Company', 'testrbn@gmail.com', 25, 'Banking', 'Information Technology', 'Hyderabad', 'India', 'uploads/1702747509_Screenshot (7).png', 'testass.com', '2023-12-16 11:55:09', '2023-12-16 11:55:09', NULL),
	(2, 'abcd.co', 'abcd@gmail.com', 3, 'Insurance', 'Infrastructure', 'hyd', 'India', 'uploads/1703518163_Screenshot (6).png', 'abcd.co.in', '2023-12-25 09:59:23', '2023-12-25 09:59:23', NULL),
	(3, 'sustain.co', 'sustain@gmail.com', 20, 'Information Technology', 'Banking', 'Hyderabad', 'India', 'uploads/1703523283_Screenshot (8).png', 'stainedge.com', '2023-12-25 11:24:43', '2023-12-25 11:24:43', NULL),
	(4, 'sdfasd', 'sdfasa@mail.com', 33, 'Information Technology', 'Information Technology', 'Hyderabad', 'India', NULL, 'india.com', '2023-12-26 10:38:05', '2023-12-26 10:38:05', NULL);

-- Dumping structure for table sustain_edge.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.roles: ~2 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Administrator', 'Administrator', '2023-12-16 17:17:32', '2023-12-16 17:17:32', NULL),
	(2, 'Team Lead', 'Team Leader', '2023-12-16 17:17:32', '2023-12-16 17:17:32', NULL),
	(3, 'Employee', 'Employee', '2023-12-16 17:17:32', '2023-12-16 17:17:32', NULL);

-- Dumping structure for table sustain_edge.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `role` int(10) unsigned DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL,
  `status_id` int(11) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `user_fk1` (`role`),
  KEY `user_fk2` (`company_id`),
  CONSTRAINT `user_fk1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_fk2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.users: ~24 rows (approximately)
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `department`, `role`, `company_id`, `email_verified_at`, `img_path`, `status_id`, `created_by`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Bhoomanandam RB', 'testrbn@gmail.com', '$2y$12$hkRRpvCsOIYQLYeY5niki.MG.UIoAln1vDJmVhedYSgf/fI6gMvWC', 'IT', 1, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 11:55:09', '2023-12-17 05:07:34', NULL),
	(2, 'sadfsd', 'fasdas@mail.com', '$2y$12$kvPTkPW9TXlj8CC3C.BKre51qTMIAU0HDKwb.7dGqtTH2IGo9PgwW', 'sfasdasd', 1, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 12:26:30', '2023-12-16 13:32:11', '2023-12-16 13:32:11'),
	(3, 'sdfasdf', 'testuser@mail.com', '$2y$12$W5NZLS9hUC6VJkaTl.9cCeNzw4nXj399PKzTKsH7iMWRUJoGK17yG', 'sdasasasd', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 12:32:37', '2023-12-16 13:43:42', '2023-12-16 13:43:42'),
	(4, 'sadfasd', 'asdas@mail.com', '$2y$12$ji5B6u53sEHcGj11hJcD5O3V2LV43e3iHW.FKh2uvSRRGItEk/IsG', 'assdsasd', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 12:34:53', '2023-12-16 13:39:16', '2023-12-16 13:39:16'),
	(5, 'asdfsd', 'afasdsa@mail.com', '$2y$12$bAy9IFv9y3LRGtn/ovyMjeBY.vIEzUOkrTw.EzO0z3nPLLTq8KOnq', 'sdfasd', 3, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 12:37:03', '2023-12-16 13:26:50', '2023-12-16 13:26:50'),
	(6, 'Testrbn', 'asdassaW@mail.com', '$2y$12$cxazYOpg6EqgaSOYnfarhO6yccdZ7XiQjTyqskYS/LchWP2XYWFV2', 'asfasd', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 12:38:41', '2023-12-16 13:29:53', '2023-12-16 13:29:53'),
	(7, 'asdfaas', 'asda@mail.com', '$2y$12$anWNzIpxq0FA/0nM5.8kfenFn0OZ.jfHA/9t.JlLoJ1niZViAHg/i', 'asasdfa', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 13:44:25', '2023-12-16 13:44:25', NULL),
	(8, 'asfdasd', 'asfasa@mail.com', '$2y$12$Xt35/3XQKK0phq/NPqDBEOURluv/GqN/5NAN5FYjtsWwLnB88A0Gu', 'sdassdfa', 1, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 13:52:20', '2023-12-16 13:54:31', '2023-12-16 13:54:31'),
	(9, 'asdfasd@mail', 'sdfasd@mail.com', '$2y$12$pjvR1nHvTZcNC2hD7/Yok.JTPd8QH2j0ST9gt5P9DxbrD8H9LvE9S', 'asdasafas', 3, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 13:53:10', '2023-12-16 13:53:10', NULL),
	(10, 'sdfasda', 'sadfasdfsad@mail.com', '$2y$12$Xrnv24V8L5OiHsScvTyVMOp8ffqtXX5BO6Fi4pRXx7dmnCbcZKKzu', 'asdfasf', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 13:54:00', '2023-12-16 13:54:00', NULL),
	(11, 'fadsfas update2asd', 'asasdd@mila.com', '$2y$12$Yd9UlwqSM5yVIpvaZMnPfuHGOWw2NpttdRZCKPHSdaSgIrX5oYH86', 'sdasdff update1', 3, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 14:11:44', '2023-12-17 03:41:10', NULL),
	(12, 'testsasd', 'asdasd@mail.com', '$2y$12$DVlGudbCHZ2..GMeKt3hQ.wOntUiBPMtBol61F25gOQdLFt66YJjm', 'asdfasa', 3, 1, NULL, NULL, 1, 1, NULL, '2023-12-16 15:13:55', '2023-12-17 02:55:14', NULL),
	(13, 'TestUsersad', 'sfa@mail.com', '$2y$12$/JcB36aJymtPwagA52fzU.LCNo6j0OqNI1VFcvYfZRoREI7BrHW62', 'afasddss', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-17 03:24:41', '2023-12-17 03:24:41', NULL),
	(14, 'Testfasdd', 'sfas@mail.com', '$2y$12$nfdPVEsfCcbSGYiV5W4ByebwIIfnWNEGt2uVP7V5/WB0sSGZCVKIG', 'fasdaasdf', 3, 1, NULL, 'uploads/1702803672_Screenshot (7).png', 1, 1, NULL, '2023-12-17 03:31:12', '2023-12-17 04:20:21', '2023-12-17 04:20:21'),
	(15, 'Tsada', 'sfasd@mail.com', '$2y$12$0zeKAzN3I44lLgoRUOR1Yuyc8YT15WdMb4snmgd0CyniEPjZMwr7q', 'asdfasda', 3, 1, NULL, 'uploads/1702803740_Screenshot (8).png', 1, 1, NULL, '2023-12-17 03:32:20', '2023-12-17 03:32:20', NULL),
	(16, 'Employee up', 'employee@gmail.com', '$2y$12$.KcEtZjT59RlN6GZ2ySS3.gx1TYEVVNKoRxowjDodalluxZo1n956', 'EMP', 3, 1, NULL, NULL, 1, 1, NULL, '2023-12-17 04:21:26', '2023-12-17 06:31:28', NULL),
	(17, 'Team Head', 'teamlead@gmail.com', '$2y$12$tV7d4WzWOuSax77b5L2tNeasx2XgDB2S7ZVBHKmPcgZm7mh64zELC', 'IT', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-17 04:24:12', '2023-12-17 04:24:12', NULL),
	(18, 'sdasd update', 'employee1@gmail.com', '$2y$12$CY6OpVUyHrTnWDxxr3q9He9o3xZOREGn9yGWZ1G5twdGWB5df97Fi', 'adsdf', 2, 1, NULL, NULL, 1, 1, NULL, '2023-12-17 04:30:55', '2023-12-17 07:17:13', NULL),
	(19, 'abcd', 'abcd@gmail.com', '$2y$12$RexpIbKfPIPwQ9qK/sh84.Fe5hIBkDoByjXliwTh4Vs7OLdsoR4YC', 'IT', 1, 2, NULL, 'uploads/1703518163_Screenshot (6).png', 1, NULL, NULL, '2023-12-25 09:59:23', '2023-12-25 09:59:23', NULL),
	(20, 'testuser1', 'testsadfsad@mail.com', '$2y$12$YIVaNBvQlpByhdMF1X8CNehPyQol5Hl8QwdgW.Ql7ZRJxs2FUZ1HS', 'It', 3, 2, NULL, NULL, 1, 19, NULL, '2023-12-25 10:00:17', '2023-12-25 10:01:22', '2023-12-25 10:01:22'),
	(21, 'fadsd', 'ASDSAD@MAIL.COM', '$2y$12$DdIFSf3Uwbt7xbABsxHGsO0zbENRG87UuJR8Th/F2EdwS4P1x0sKy', 'IT', 3, 2, NULL, NULL, 1, 19, NULL, '2023-12-25 10:01:14', '2023-12-25 10:01:14', NULL),
	(22, 'sustainEdge', 'sustain@gmail.com', '$2y$12$YTCBzesGhz.EJgaS3RicHOhGxkrEv6kmb3G1BYi7pDq8t9oi5aHQS', 'It', 1, 3, NULL, 'uploads/1703523283_Screenshot (8).png', 1, NULL, NULL, '2023-12-25 11:24:43', '2023-12-25 11:24:43', NULL),
	(23, 'sustainedgeUserOne', 'sustainedgeUserOne@gmail.com', '$2y$12$oTyCGjFgbBUuEj9Yfw6R5OGdcLWfnVTWAyqRDLJvlbua/XlEEG6s6', 'IT', 2, 3, NULL, NULL, 1, 22, NULL, '2023-12-25 11:31:10', '2023-12-25 11:31:10', NULL),
	(24, 'sustainedgeUserTwo', 'sustainedgeTwoW@gmail.com', '$2y$12$5wx0xrkhshVkuafgzXpeo.cCVY2/9JGM/LiFVwcEDf7yaUUJOK06q', 'IT', 3, 3, NULL, NULL, 1, 22, NULL, '2023-12-25 11:32:12', '2023-12-25 11:35:20', '2023-12-25 11:35:20'),
	(25, 'xafads', 'sdfasa@mail.com', '$2y$12$prDdw4qI30au0ZzuabeRtuBFQ3yScppLyuXqeZd9K/IN34Y2rUCp6', 'It', 1, 4, NULL, NULL, 1, NULL, NULL, '2023-12-26 10:38:05', '2023-12-26 10:54:33', NULL),
	(26, 'sdfgsq update', 'asdasdaasa@mail.com', '$2y$12$Erqr2fRX35anbeBr.YZ4Z.ERlc7VW.Lopt8PiPkhwEd11Cgr.GGaC', 'IT', 1, 4, NULL, NULL, 1, 25, NULL, '2023-12-26 10:55:28', '2023-12-26 10:56:00', NULL);

-- Dumping structure for table sustain_edge.role_user
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user1` (`role_id`),
  KEY `role_user2` (`user_id`),
  CONSTRAINT `role_user1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.role_user: ~24 rows (approximately)
INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, '2023-12-16 11:55:09', '2023-12-17 05:07:34', NULL),
	(2, 1, 2, '2023-12-16 12:26:30', '2023-12-16 13:32:11', '2023-12-16 13:32:11'),
	(3, 2, 3, '2023-12-16 12:32:37', '2023-12-16 13:43:42', '2023-12-16 13:43:42'),
	(4, 2, 4, '2023-12-16 12:34:53', '2023-12-16 13:39:16', '2023-12-16 13:39:16'),
	(5, 3, 5, '2023-12-16 12:37:03', '2023-12-16 13:26:50', '2023-12-16 13:26:50'),
	(6, 2, 6, '2023-12-16 12:38:41', '2023-12-16 13:29:53', '2023-12-16 13:29:53'),
	(7, 2, 7, '2023-12-16 13:44:25', '2023-12-16 13:44:25', NULL),
	(8, 1, 8, '2023-12-16 13:52:20', '2023-12-16 13:54:31', '2023-12-16 13:54:31'),
	(9, 3, 9, '2023-12-16 13:53:10', '2023-12-16 13:53:10', NULL),
	(10, 2, 10, '2023-12-16 13:54:00', '2023-12-16 13:54:00', NULL),
	(11, 2, 11, '2023-12-16 14:11:44', '2023-12-16 14:11:44', NULL),
	(12, 3, 12, '2023-12-16 15:13:55', '2023-12-16 15:13:55', NULL),
	(13, 2, 13, '2023-12-17 03:24:41', '2023-12-17 03:24:41', NULL),
	(14, 3, 14, '2023-12-17 03:31:12', '2023-12-17 04:20:21', '2023-12-17 04:20:21'),
	(15, 3, 15, '2023-12-17 03:32:20', '2023-12-17 03:32:20', NULL),
	(16, 3, 16, '2023-12-17 04:21:26', '2023-12-17 04:21:26', NULL),
	(17, 2, 17, '2023-12-17 04:24:12', '2023-12-17 04:24:12', NULL),
	(18, 2, 18, '2023-12-17 04:30:55', '2023-12-17 07:17:13', NULL),
	(19, 1, 19, '2023-12-25 09:59:23', '2023-12-25 09:59:23', NULL),
	(20, 3, 20, '2023-12-25 10:00:17', '2023-12-25 10:01:22', '2023-12-25 10:01:22'),
	(21, 3, 21, '2023-12-25 10:01:14', '2023-12-25 10:01:14', NULL),
	(22, 1, 22, '2023-12-25 11:24:43', '2023-12-25 11:24:43', NULL),
	(23, 2, 23, '2023-12-25 11:31:10', '2023-12-25 11:31:10', NULL),
	(24, 3, 24, '2023-12-25 11:32:12', '2023-12-25 11:35:20', '2023-12-25 11:35:20'),
	(25, 1, 25, '2023-12-26 10:38:05', '2023-12-26 10:38:05', NULL),
	(26, 1, 26, '2023-12-26 10:55:28', '2023-12-26 10:55:28', NULL);

-- Dumping structure for table sustain_edge.stationary_combustion_standards
CREATE TABLE IF NOT EXISTS `stationary_combustion_standards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sno` varchar(255) NOT NULL,
  `fuel_type` varchar(255) NOT NULL,
  `particulars` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `actual_quantity` float(10,2) NOT NULL,
  `converted_uom` varchar(255) DEFAULT NULL,
  `conversion_factor_to_s_uom` float(10,2) NOT NULL,
  `converted_actual_quantity` float(10,2) NOT NULL,
  `ncv_kj_kg` int(10) unsigned NOT NULL,
  `density` float(10,2) NOT NULL DEFAULT 0.00,
  `uom_factor_to_kj` varchar(255) DEFAULT NULL,
  `conversion_factor_to_kj` float(20,2) NOT NULL,
  `converted_qnty` float(20,2) NOT NULL,
  `emission_factor_kj_tj` int(25) unsigned NOT NULL,
  `converted_emission_factor_ton_kj` float(10,10) NOT NULL DEFAULT 0.0000000000,
  `total_emission` float(10,3) NOT NULL,
  `standard_uom` varchar(255) NOT NULL DEFAULT 'tCO2',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.stationary_combustion_standards: ~51 rows (approximately)
INSERT INTO `stationary_combustion_standards` (`id`, `sno`, `fuel_type`, `particulars`, `region`, `uom`, `actual_quantity`, `converted_uom`, `conversion_factor_to_s_uom`, `converted_actual_quantity`, `ncv_kj_kg`, `density`, `uom_factor_to_kj`, `conversion_factor_to_kj`, `converted_qnty`, `emission_factor_kj_tj`, `converted_emission_factor_ton_kj`, `total_emission`, `standard_uom`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'S11001', 'Solid', 'Anthracite', '', '', 123.45, ' ', 1.00, 123.45, 26700, 785.00, 'kg/m3', 20959500.00, 2587450368.00, 98300, 0.0000000983, 254.346, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(2, 'S11002', 'Liquid', 'Aviation gasoline', '', '', 123.45, ' ', 1.00, 123.45, 44300, 0.00, '', 0.00, 0.00, 70000, 0.0000000700, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(3, 'S11003', 'Biomass', 'Biodiesels', '', '', 123.45, ' ', 1.00, 123.45, 27000, 880.00, 'kg/m3', 23760000.00, 2933171968.00, 70800, 0.0000000708, 207.669, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(4, 'S11004', 'Biomass', 'Biogasoline', '', '', 123.45, ' ', 1.00, 123.45, 27000, 1.20, 'kg/m3', 32400.00, 3999780.00, 70800, 0.0000000708, 0.283, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(5, 'S11005', 'Solid', 'Bitumen', '', '', 123.45, ' ', 1.00, 123.45, 40200, 1000.00, 'kg/m3', 40200000.00, 4962690048.00, 80700, 0.0000000807, 400.489, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(6, 'S11006', 'Gaseous', 'Blast furnace gas', '', '', 123.45, ' ', 1.00, 123.45, 2470, 0.00, '', 0.00, 0.00, 260000, 0.0000002600, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(7, 'S11007', 'Solid', 'Brown coal briquettes', '', '', 123.45, ' ', 1.00, 123.45, 20700, 0.00, '', 0.00, 0.00, 97500, 0.0000000975, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(8, 'S11008', 'Biomass', 'Charcoal', '', '', 123.45, ' ', 1.00, 123.45, 29500, 149.00, 'kg/m3', 4395500.00, 542624448.00, 112000, 0.0000001120, 60.774, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(9, 'S11009', 'Solid', 'Coal tar', '', '', 123.45, ' ', 1.00, 123.45, 28000, 0.00, '', 0.00, 0.00, 80700, 0.0000000807, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(10, 'S11010', 'Solid', 'Coke oven coke and lignite coke', '', '', 123.45, ' ', 1.00, 123.45, 28200, 0.00, '', 0.00, 0.00, 107000, 0.0000001070, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(11, 'S11011', 'Gaseous', 'Coke oven gas', '', '', 123.45, ' ', 1.00, 123.45, 38700, 0.00, '', 0.00, 0.00, 44400, 0.0000000444, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(12, 'S11012', 'Solid', 'Coking coal', '', '', 123.45, ' ', 1.00, 123.45, 28200, 900.00, 'kg/m3', 25380000.00, 3133160960.00, 94600, 0.0000000946, 296.397, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(13, 'S11013', 'Liquid', 'Crude oil', 'India', 'Litre', 123.45, ' Litre', 1.00, 123.45, 42300, 900.00, 'kg/m3', 38070000.00, 4699741696.00, 73300, 0.0000000733, 344.491, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(14, 'S11014', 'Gaseous', 'Ethane', '', '', 123.45, ' ', 1.00, 123.45, 46400, 1.28, 'kg/m3', 59484.80, 7343398.50, 61600, 0.0000000616, 0.452, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(15, 'S11015', 'Solid', 'Gas coke', '', '', 123.45, ' ', 1.00, 123.45, 28200, 0.00, '', 0.00, 0.00, 107000, 0.0000001070, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(16, 'S11016', 'Gaseous', 'Gas works gas', '', '', 123.45, ' ', 1.00, 123.45, 38700, 0.00, '', 0.00, 0.00, 44400, 0.0000000444, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(17, 'S11017', 'Liquid', 'Gas/Diesel oil', '', '', 123.45, ' ', 1.00, 123.45, 43000, 800.00, '', 34400000.00, 4246680064.00, 74100, 0.0000000741, 314.679, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(18, 'S11018', 'Liquid', 'Jet fuel', '', '', 123.45, ' ', 1.00, 123.45, 44300, 798.72, '', 35383296.00, 4368068096.00, 70000, 0.0000000700, 305.765, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(19, 'S11019', 'Biomass', 'Landfill gas', '', '', 123.45, ' ', 1.00, 123.45, 50400, 0.00, '', 0.00, 0.00, 54600, 0.0000000546, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(20, 'S11020', 'Solid', 'Lignite', '', '', 123.45, ' ', 1.00, 123.45, 11900, 0.00, '', 0.00, 0.00, 101000, 0.0000001010, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(21, 'S11021', 'Gaseous', 'Liquified Petroleum Gases', '', '', 123.45, ' ', 1.00, 123.45, 47300, 1.90, 'kg/m3', 89775.40, 11082773.00, 63100, 0.0000000631, 0.699, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(22, 'S11022', 'Liquid', 'Lubricants', '', '', 123.45, ' ', 1.00, 123.45, 40200, 825.00, 'kg/m3', 33165000.00, 4094219264.00, 73300, 0.0000000733, 300.106, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(23, 'S11023', 'Liquid', 'Motor gasoline', '', '', 123.45, ' ', 1.00, 123.45, 44300, 750.00, 'kg/m3', 33225000.00, 4101626368.00, 69300, 0.0000000693, 284.243, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(24, 'S11024', 'Solid', 'Municipal waste (Non biomass fraction)', '', '', 123.45, ' ', 1.00, 123.45, 10000, 0.00, '', 0.00, 0.00, 91700, 0.0000000917, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(25, 'S11025', 'Biomass', 'Municipal wastes (Biomass fraction)', '', '', 123.45, ' ', 1.00, 123.45, 11600, 0.00, '', 0.00, 0.00, 100000, 0.0000001000, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(26, 'S11026', 'Liquid', 'Naphtha', '', '', 123.45, ' ', 1.00, 123.45, 44500, 767.50, 'kg/m3', 34153752.00, 4216280320.00, 73300, 0.0000000733, 309.053, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(27, 'S11027', 'Gaseous', 'Natural gas', '', '', 123.45, ' ', 1.00, 123.45, 48000, 0.72, 'kg/m3', 34416.00, 4248655.00, 56100, 0.0000000561, 0.238, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(28, 'S11028', 'Gaseous', 'Natural Gas Liquids', 'Europe', '', 123.45, ' ', 1.00, 123.45, 44200, 0.80, 'kg/m3', 35360.00, 4365192.00, 64200, 0.0000000642, 0.280, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(29, 'S11029', 'Liquid', 'Oil shale and tar sands', '', '', 123.45, ' ', 1.00, 123.45, 8900, 0.00, '', 0.00, 0.00, 107000, 0.0000001070, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(30, 'S11030', 'Liquid', 'Orimulsion', 'US', 'Gallon', 123.45, ' Litre', 3.79, 467.31, 27500, 0.00, '', 0.00, 0.00, 77000, 0.0000000770, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(31, 'S11031', 'Gaseous', 'Oxygen steel furnace gas', '', '', 123.45, ' ', 1.00, 123.45, 7060, 0.00, '', 0.00, 0.00, 182000, 0.0000001820, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(32, 'S11032', 'Solid', 'Paraffin waxes', '', '', 123.45, ' ', 1.00, 123.45, 40200, 900.00, 'kg/m3', 36180000.00, 4466421248.00, 73300, 0.0000000733, 327.389, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(33, 'S11033', 'Solid', 'Patent fuel', '', '', 123.45, ' ', 1.00, 123.45, 20700, 0.00, '', 0.00, 0.00, 97500, 0.0000000975, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(34, 'S11034', 'Biomass', 'Peat', '', '', 123.45, ' ', 1.00, 123.45, 9760, 355.00, 'kg/m3', 3464800.00, 427729568.00, 106000, 0.0000001060, 45.339, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(35, 'S11035', 'Solid', 'Petroleum coke', '', '', 123.45, ' ', 1.00, 123.45, 32500, 437.50, 'kg/m3', 14218750.00, 1755304704.00, 97500, 0.0000000975, 171.142, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(36, 'S11036', 'Liquid', 'Refinery feedstocks', '', '', 123.45, ' ', 1.00, 123.45, 43000, 0.00, '', 0.00, 0.00, 73300, 0.0000000733, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(37, 'S11037', 'Gaseous', 'Refinery gas', '', '', 123.45, ' ', 1.00, 123.45, 49500, 0.00, '', 0.00, 0.00, 57600, 0.0000000576, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(38, 'S11038', 'Liquid', 'Residual fuel oil', '', '', 123.45, ' ', 1.00, 123.45, 40400, 991.00, '', 40036400.00, 4942493696.00, 74100, 0.0000000741, 366.239, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(39, 'S11039', 'Liquid', 'Shale oil', '', '', 123.45, ' ', 1.00, 123.45, 38100, 0.00, '', 0.00, 0.00, 73300, 0.0000000733, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(40, 'S11040', 'Biomass', 'Sludge gas', '', '', 123.45, ' ', 1.00, 123.45, 50400, 0.00, '', 0.00, 0.00, 54600, 0.0000000546, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(41, 'S11041', 'Solid', 'Sub bituminous coal', '', '', 123.45, ' ', 1.00, 123.45, 18900, 0.00, '', 0.00, 0.00, 96100, 0.0000000961, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(42, 'S11042', 'Biomass', 'Sulphite lyes (Black liqour)', '', '', 123.45, ' ', 1.00, 123.45, 11800, 0.00, '', 0.00, 0.00, 95300, 0.0000000953, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(43, 'S11043', 'Liquid', 'Waste oils', '', '', 123.45, ' ', 1.00, 123.45, 40200, 845.00, '', 33969000.00, 4193473024.00, 73300, 0.0000000733, 307.382, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(44, 'S11044', 'Liquid', 'White Spirit/SBP', '', '', 123.45, ' ', 1.00, 123.45, 40200, 0.78, 'kg/L', 31356.00, 3870898.25, 73300, 0.0000000733, 0.284, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(45, 'S11045', 'Biomass', 'Wood or Wood waste', '', '', 123.45, ' ', 1.00, 123.45, 15600, 372.50, 'kg/m3', 5811000.00, 717367936.00, 112000, 0.0000001120, 80.345, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(46, 'S11046', 'Biomass', 'Other biogas', '', '', 123.45, ' ', 1.00, 123.45, 50400, 0.00, '', 0.00, 0.00, 54600, 0.0000000546, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(47, 'S11047', 'Solid', 'Other bituminous coal', '', '', 123.45, ' ', 1.00, 123.45, 25800, 745.00, 'kg/m3', 19221000.00, 2372832512.00, 94600, 0.0000000946, 224.470, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(48, 'S11048', 'Liquid', 'Other kerosene', '', '', 123.45, ' ', 1.00, 123.45, 43800, 800.00, 'kg/m3', 35040000.00, 4325687808.00, 71900, 0.0000000719, 311.017, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(49, 'S11049', 'Biomass', 'Other liquid biofuels', '', '', 123.45, ' ', 1.00, 123.45, 27400, 0.00, '', 0.00, 0.00, 79600, 0.0000000796, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(50, 'S11050', 'Solid', 'Other petroleum products', '', '', 123.45, ' ', 1.00, 123.45, 40200, 366.30, '', 14725260.00, 1817833344.00, 73300, 0.0000000733, 133.247, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL),
	(51, 'S11051', 'Biomass', 'Other primary solid biomass fuels', '', '', 123.45, ' ', 1.00, 123.45, 11600, 0.00, '', 0.00, 0.00, 100000, 0.0000001000, 0.000, 'tCO2', '2023-12-19 17:57:11', '2023-12-19 17:57:11', NULL);

-- Dumping structure for table sustain_edge.scope_one_combustion
CREATE TABLE IF NOT EXISTS `scope_one_combustion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fuel_particular_id` int(10) unsigned NOT NULL,
  `scope_type` tinyint(4) NOT NULL,
  `fuel_particular` varchar(255) NOT NULL,
  `fuel_type` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `input_uom` varchar(255) NOT NULL,
  `actual_quantity` float(10,2) NOT NULL,
  `converted_actual_quantity` float(10,2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `created_by_user_id` int(10) unsigned NOT NULL,
  `converted_uom` varchar(255) DEFAULT NULL,
  `conversion_unit` float(10,2) NOT NULL,
  `converted_value1` float(10,2) NOT NULL,
  `ncv` int(10) NOT NULL,
  `density` float(10,2) NOT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `conversion_factor_kj` varchar(255) NOT NULL,
  `converted_factor_kj` varchar(255) NOT NULL,
  `emission` int(10) NOT NULL,
  `converted_emission` varchar(255) NOT NULL,
  `total_emission` varchar(255) NOT NULL,
  `standard` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scop1_combusion_key1` (`fuel_particular_id`),
  KEY `scop1_combusion_key2` (`company_id`),
  KEY `scop1_combusion_key3` (`created_by_user_id`),
  CONSTRAINT `scop1_combusion_key1` FOREIGN KEY (`fuel_particular_id`) REFERENCES `stationary_combustion_standards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scop1_combusion_key2` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `scop1_combusion_key3` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.scope_one_combustion: ~2 rows (approximately)
INSERT INTO `scope_one_combustion` (`id`, `fuel_particular_id`, `scope_type`, `fuel_particular`, `fuel_type`, `region`, `input_uom`, `actual_quantity`, `converted_actual_quantity`, `start_date`, `end_date`, `company_id`, `created_by_user_id`, `converted_uom`, `conversion_unit`, `converted_value1`, `ncv`, `density`, `uom`, `conversion_factor_kj`, `converted_factor_kj`, `emission`, `converted_emission`, `total_emission`, `standard`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 2, 1, 'Aviation gasoline', 'Length', 'India', '5', 12.00, 120.00, '2023-11-26', '2024-01-06', 1, 1, 'DecaM', 1.00, 120.00, 44300, 0.00, '5', '0', '0', 70000, '0.0000000700', '0.000', 'tCO2', '2023-12-25 09:50:32', '2023-12-25 11:47:50', '2023-12-25 11:47:50'),
	(2, 13, 1, 'Crude oil', 'Length', 'India', '4', 13.00, 13.00, '2023-12-03', '2024-01-06', 3, 23, 'Litre', 1.00, 13.00, 42300, 900.00, 'kg/m3', '38070000', '494910000', 73300, '0.0000000733', '36.277', 'tCO2', '2023-12-25 12:01:33', '2023-12-25 12:01:47', '2023-12-25 12:01:47');

-- Dumping structure for table sustain_edge.units_of_measurements
CREATE TABLE IF NOT EXISTS `units_of_measurements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quantity_type` varchar(255) NOT NULL,
  `units` int(10) unsigned NOT NULL DEFAULT 1,
  `conversion_type` varchar(255) NOT NULL,
  `unit_of_measurement` varchar(255) DEFAULT NULL,
  `uom_description` varchar(255) DEFAULT NULL,
  `converted_value` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table sustain_edge.units_of_measurements: ~39 rows (approximately)
INSERT INTO `units_of_measurements` (`id`, `quantity_type`, `units`, `conversion_type`, `unit_of_measurement`, `uom_description`, `converted_value`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Length', 1, 'meter', 'MM', 'millimetre', '0.001', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(2, 'Length', 1, 'meter', 'CM', 'centimetre', '0.01', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(3, 'Length', 1, 'meter', 'DM', 'decimetre', '0.1', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(4, 'Length', 1, 'meter', 'Meter', 'meter', '1', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(5, 'Length', 1, 'meter', 'DecaM', 'decametre', '10', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(6, 'Length', 1, 'meter', 'HM', 'hectometre', '100', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(7, 'Length', 1, 'meter', 'KM', 'kilometre', '1000', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(8, 'Length', 1, 'meter', 'Inch', 'inch', '0.0254', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(9, 'Length', 1, 'meter', 'Foot', 'foot', '0.3048', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(10, 'Length', 1, 'meter', 'Angstrom', 'angstrom', '0.0000000001', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(11, 'Length', 1, 'meter', 'Fermi', 'fermi', '0.000000000000001', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(12, 'Length', 1, 'meter', 'Light Year', 'light year', '961.136', '2023-12-25 10:04:43', '2023-12-25 10:04:43', NULL),
	(13, 'Length', 1, 'meter', 'Mile', 'mile', '1609', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(14, 'Volume', 1, 'liter', 'ML', 'milliliter', '0.001', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(15, 'Volume', 1, 'liter', 'CL', 'centilitre', '0.01', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(16, 'Volume', 1, 'liter', 'DL', 'decilitre', '0.1', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(17, 'Volume', 1, 'liter', 'Liter', 'liter', '1', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(18, 'Volume', 1, 'liter', 'DecaL', 'decalitre', '10', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(19, 'Volume', 1, 'liter', 'HL', 'hectolitre', '100', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(20, 'Volume', 1, 'liter', 'KL', 'kilolitre', '1000', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(21, 'Volume', 1, 'liter', 'Cubic Inch', 'cubic inch', '0.01639', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(22, 'Volume', 1, 'liter', 'Gallon', 'gallon', '3.785', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(23, 'Volume', 1, 'liter', 'Cubic Foot', 'cubic foot', '28.316', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(24, 'Mass', 1, 'gram', 'MG', 'milligram', '0.001', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(25, 'Mass', 1, 'gram', 'CG', 'centigram', '0.01', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(26, 'Mass', 1, 'gram', 'DG', 'decigram', '0.1', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(27, 'Mass', 1, 'gram', 'Gram', 'gram', '1', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(28, 'Mass', 1, 'gram', 'DecaG', 'decagram', '10', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(29, 'Mass', 1, 'gram', 'HG', 'hectogram', '100', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(30, 'Mass', 1, 'gram', 'KG', 'kilogram', '1000', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(31, 'Mass', 1, 'gram', 'Stone', 'stone', '6350.29', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(32, 'Mass', 1, 'gram', 'Pound', 'pound', '453.592', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(33, 'Mass', 1, 'gram', 'Ounce', 'ounce', '28.3495', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(34, 'Time', 1, 'second', 'Minute', 'minute', '60', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(35, 'Time', 1, 'second', 'Second', 'second', '1', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(36, 'Time', 1, 'second', 'Hour', 'hour', '3600', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(37, 'Time', 1, 'second', 'Day', 'day', '86400', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(38, 'Time', 1, 'second', 'Week', 'week', '604800', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL),
	(39, 'Time', 1, 'second', 'year', 'year', '31536000', '2023-12-25 10:04:44', '2023-12-25 10:04:44', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
