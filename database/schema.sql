SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `athlete_medals`;
CREATE TABLE `athlete_medals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `athlete_id` bigint(20) unsigned NOT NULL,
  `olympic_games_id` bigint(20) unsigned NOT NULL,
  `discipline_id` bigint(20) unsigned NOT NULL,
  `represented_country_id` bigint(20) unsigned DEFAULT NULL,
  `medal_type_id` bigint(20) unsigned DEFAULT NULL,
  `placing` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_athlete_medals` (`athlete_id`,`olympic_games_id`,`discipline_id`,`placing`),
  KEY `idx_athlete_medals_games` (`olympic_games_id`),
  KEY `idx_athlete_medals_discipline` (`discipline_id`),
  KEY `idx_athlete_medals_repr_country` (`represented_country_id`),
  KEY `idx_athlete_medals_medal_type` (`medal_type_id`),
  CONSTRAINT `fk_athlete_medals_athlete` FOREIGN KEY (`athlete_id`) REFERENCES `athletes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_athlete_medals_discipline` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_athlete_medals_games` FOREIGN KEY (`olympic_games_id`) REFERENCES `olympic_games` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_athlete_medals_medal_type` FOREIGN KEY (`medal_type_id`) REFERENCES `medal_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_athlete_medals_repr_country` FOREIGN KEY (`represented_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `athletes`;
CREATE TABLE `athletes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(191) DEFAULT NULL,
  `birth_country_id` bigint(20) unsigned DEFAULT NULL,
  `death_date` date DEFAULT NULL,
  `death_place` varchar(191) DEFAULT NULL,
  `death_country_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_athletes_birth_country` (`birth_country_id`),
  KEY `idx_athletes_death_country` (`death_country_id`),
  KEY `idx_athletes_identity` (`first_name`,`last_name`,`birth_date`),
  CONSTRAINT `fk_athletes_birth_country` FOREIGN KEY (`birth_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_athletes_death_country` FOREIGN KEY (`death_country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_countries_name` (`name`),
  KEY `idx_countries_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `disciplines`;
CREATE TABLE `disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_disciplines_category_name` (`category`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `login_history`;
CREATE TABLE `login_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `method` varchar(20) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_login_history_user_id` (`user_id`),
  CONSTRAINT `fk_login_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `medal_types`;
CREATE TABLE `medal_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `placing` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_medal_types_placing` (`placing`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `olympic_games`;
CREATE TABLE `olympic_games` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `order_no` int(11) DEFAULT NULL,
  `city` varchar(191) NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_games_type_year` (`type`,`year`),
  KEY `idx_games_country` (`country_id`),
  CONSTRAINT `fk_games_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `oauth_provider` varchar(30) DEFAULT NULL,
  `oauth_sub` varchar(255) DEFAULT NULL,
  `totp_secret` varchar(64) DEFAULT NULL,
  `totp_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  UNIQUE KEY `uq_users_oauth_sub` (`oauth_provider`,`oauth_sub`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS=1;
