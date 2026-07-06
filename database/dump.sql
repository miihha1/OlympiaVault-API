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

INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (113, 104, 41, 38, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (114, 105, 42, 39, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (115, 106, 43, 40, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (116, 107, 44, 41, 51, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (117, 108, 45, 42, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (118, 109, 48, 43, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (119, 110, 48, 43, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (120, 111, 64, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (121, 112, 64, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (122, 113, 64, 45, 50, 11, 22);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (123, 114, 65, 45, 50, 12, 8);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (124, 115, 66, 45, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (125, 116, 50, 46, 51, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (126, 117, 51, 47, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (127, 118, 52, 43, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (128, 119, 52, 43, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (129, 120, 52, 48, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (130, 121, 69, 44, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (131, 122, 69, 44, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (132, 123, 69, 44, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (133, 124, 69, 44, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (134, 125, 54, 49, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (135, 126, 54, 50, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (136, 127, 54, 51, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (137, 128, 56, 52, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (138, 129, 56, 53, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (139, 130, 56, 54, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (140, 131, 56, 55, 50, 13, 19);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (141, 132, 57, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (142, 133, 57, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (143, 134, 57, 52, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (144, 135, 57, 57, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (145, 136, 57, 58, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (146, 137, 57, 52, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (147, 138, 57, 55, 50, 14, 4);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (148, 132, 58, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (149, 133, 58, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (150, 139, 58, 55, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (151, 140, 58, 59, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (152, 141, 58, 52, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (153, 142, 58, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (154, 143, 58, 61, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (155, 144, 58, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (156, 145, 58, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (157, 146, 58, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (158, 147, 75, 62, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (159, 132, 59, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (160, 133, 59, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (161, 148, 59, 55, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (162, 149, 59, 52, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (163, 150, 59, 60, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (164, 144, 59, 60, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (165, 151, 59, 63, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (166, 152, 59, 60, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (167, 153, 59, 60, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (168, 154, 59, 64, 48, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (169, 155, 76, 65, 39, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (170, 156, 76, 66, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (171, 157, 76, 67, 39, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (172, 156, 76, 68, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (173, 158, 60, 63, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (174, 159, 60, 69, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (175, 132, 60, 56, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (176, 133, 60, 56, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (177, 160, 60, 52, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (178, 161, 77, 65, 39, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (179, 162, 61, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (180, 163, 61, 56, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (181, 164, 61, 70, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (182, 165, 61, 52, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (183, 166, 61, 71, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (184, 167, 61, 71, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (185, 168, 61, 71, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (186, 169, 61, 71, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (187, 170, 78, 68, 39, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (188, 171, 78, 67, 39, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (189, 172, 78, 72, 39, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (190, 173, 62, 63, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (191, 174, 62, 55, 50, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (192, 175, 62, 73, 53, 10, 2);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (193, 176, 62, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (194, 177, 62, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (195, 178, 62, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (196, 179, 62, 60, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (197, 180, 79, 74, 50, 9, 1);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (198, 181, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (199, 182, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (200, 183, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (201, 184, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (202, 185, 79, 44, 45, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (203, 186, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (204, 187, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (205, 188, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (206, 189, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (207, 190, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (208, 191, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (209, 192, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (210, 193, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (211, 194, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (212, 195, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (213, 196, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (214, 197, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (215, 198, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (216, 199, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (217, 200, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (218, 201, 79, 44, 54, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (219, 202, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (220, 203, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (221, 204, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (222, 205, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (223, 206, 79, 44, 50, 8, 3);
INSERT INTO `athlete_medals` (`id`, `athlete_id`, `olympic_games_id`, `discipline_id`, `represented_country_id`, `medal_type_id`, `placing`) VALUES (224, 165, 63, 52, 50, 8, 3);

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

INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (104, 'Alojz', 'Szokol', '1941-06-07', 'Hronec', 50, NULL, 'Bernecebaráti', 51);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (105, 'Zoltán', 'Halmaj', NULL, 'Vysoká pri Morave', 50, NULL, 'Budapešť', 51);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (106, 'Alexander', 'Prokopp', '1957-02-04', 'Košice', 50, '1950-04-11', 'Budapešť', 51);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (107, 'Július', 'Torma', '1922-07-03', 'Budapešť', 51, NULL, 'Praha', 52);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (108, 'Ján', 'Zachara', NULL, 'Kubrá pri Trenčíne', 50, '2025-02-01', 'Nová Dubnica', 50);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (109, 'Anton', 'Švajlen', '1937-03-12', 'Solčany', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (110, 'Anton', 'Urban', NULL, 'Kysucké Nové Mesto', 50, '2021-05-03', 'Bratislava', 50);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (111, 'Vladimír', 'Dzurilla', NULL, 'Bratislava', 50, NULL, 'Düsseldorf', 37);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (112, 'Jozef', 'Golonka', '1938-06-01', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (113, 'Ondrej', 'Nepela', NULL, 'Bratislava', 50, '1989-02-02', 'Mannheim', 37);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (114, 'Ondrej', 'Nepela', NULL, 'Bratislava', 50, '1989-02-02', 'Mannheim', 37);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (115, 'Ondrej', 'Nepela', NULL, 'Bratislava', 50, '1989-02-02', 'Mannheim', 37);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (116, 'Eva', 'Šuranová', NULL, 'Ózd', 51, NULL, 'Bratislava', 50);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (117, 'Anton', 'Tkáč', NULL, 'Lozorno', 50, NULL, 'Bratislava', 50);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (118, 'František', 'Kunzo', NULL, 'Spišský Hrušov', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (119, 'Stanislav', 'Seman', '1952-06-08', 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (120, 'Imrich', 'Bugár', NULL, 'Ohrady', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (121, 'Igor', 'Liba', '1960-04-11', 'Prešov', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (122, 'Vincent', 'Lukáč', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (123, 'Dušan', 'Pašek', '1960-07-09', 'Bratislava', 50, NULL, 'Bratislava', 50);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (124, 'Dárius', 'Rusnák', '1959-02-12', 'Ružomberok', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (125, 'Miloslav', 'Mečíř', NULL, 'Bojnice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (126, 'Jozef', 'Pribilinec', '1960-06-07', 'Kopernica', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (127, 'Miloš', 'Mečíř', NULL, 'Bojnice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (128, 'Michal', 'Martikán', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (129, 'Slavomír', 'Kňazovický', '1967-03-05', 'Piešťany', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (130, 'Jozef', 'Gönci', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (131, 'Elena', 'Kaliská', NULL, 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (132, 'Peter', 'Hochschorner', '1979-07-09', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (133, 'Pavol', 'Hochschorner', '1979-07-09', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (134, 'Michal', 'Martikán', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (135, 'Martina', 'Moravcová', NULL, 'Piešťany', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (136, 'Martina', 'Moravcová', NULL, 'Piešťany', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (137, 'Juraj', 'Minčík', NULL, 'Spišská Nová Ves', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (138, 'Elena', 'Kaliská', NULL, 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (139, 'Elena', 'Kaliská', NULL, 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (140, 'Jozef', 'Krnáč', NULL, 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (141, 'Michal', 'Martikán', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (142, 'Juraj', 'Bača', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (143, 'Jozef', 'Gönci', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (144, 'Michal', 'Riszdorfer', '1977-01-07', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (145, 'Richard', 'Riszdorfer', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (146, 'Erik', 'Vlček', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (147, 'Radoslav', 'Židek', NULL, 'Žilina', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (148, 'Elena', 'Kaliská', NULL, 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (149, 'Michal', 'Martikán', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (150, 'Richard', 'Riszdorfer', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (151, 'Zuzana', 'Štefečeková', NULL, 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (152, 'Juraj', 'Tarr', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (153, 'Erik', 'Vlček', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (154, 'David', 'Musuľbes', '1972-02-07', 'Vladi-kaukaz', 48, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (155, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (156, 'Pavol', 'Hurajt', '1978-04-02', 'Poprad', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (157, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (158, 'Zuzana', 'Štefečeková', NULL, 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (159, 'Danka', 'Barteková', NULL, 'Trenčín', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (160, 'Michal', 'Martikán', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (161, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (162, 'Ladislav', 'Škantár', '1983-11-02', 'Kežmarok', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (163, 'Peter', 'Škantár', NULL, 'Kežmarok', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (164, 'Matej', 'Tóth', '1983-10-02', 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (165, 'Matej', 'Beňuš', '1987-02-11', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (166, 'Tibor', 'Linka', NULL, 'Šamorín', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (167, 'Denis', 'Myšák', NULL, 'Bojnice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (168, 'Juraj', 'Tarr', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (169, 'Erik', 'Vlček', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (170, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (171, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (172, 'Anastasiya', 'Kuzmina', NULL, 'Ťumeň', 39, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (173, 'Zuzana', 'Rehák-Štefečeková', NULL, 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (174, 'Jakub', 'Grigar', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (175, 'Rory', 'Sabbatini', '1976-02-04', 'Durban', 53, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (176, 'Samuel', 'Baláž', NULL, 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (177, 'Adam', 'Botek', '1997-05-03', 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (178, 'Denis', 'Myšák', NULL, 'Bojnice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (179, 'Erik', 'Vlček', NULL, 'Komárno', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (180, 'Petra', 'Vlhová', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (181, 'Peter', 'Cehlárik', '1995-02-08', 'Žilina', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (182, 'Michal', 'Čajkovský', '1992-06-05', 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (183, 'Peter', 'Čerešňák', NULL, 'Trenčín', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (184, 'Marek', 'Ďaloga', '1989-04-04', 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (185, 'Marko', 'Daňo', NULL, 'Eisenstadt', 45, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (186, 'Martin', 'Gernát', '1993-11-04', 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (187, 'Adrián', 'Holešinský', '1996-11-02', 'Čadca', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (188, 'Marek', 'Hrivík', NULL, 'Čadca', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (189, 'Marek', 'Hrivík', NULL, 'Čadca', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (190, 'Libor', 'Hudáček', '1990-07-09', 'Levoča', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (191, 'Tomáš', 'Jurčo', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (192, 'Miloš', 'Kelemen', '1999-06-07', 'Lučenec', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (193, 'Samuel', 'Kňažko', '2002-07-08', 'Trenčín', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (194, 'Branislav', 'Konrád', '1987-10-10', 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (195, 'Michal', 'Krištof', '1993-11-10', 'Nitra', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (196, 'Martin', 'Marinčin', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (197, 'Šimon', 'Nemec', NULL, 'Liptovský Mikuláš', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (198, 'Kristián', 'Pospíšil', NULL, 'Zvolen', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (199, 'Pavol', 'Regenda', '1999-07-12', 'Michalovce', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (200, 'Miloš', 'Roman', NULL, 'Kysucké Nové Mesto', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (201, 'Mislav', 'Rosandič', NULL, 'Záhreb', 54, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (202, 'Patrik', 'Rybár', '1993-09-11', 'Skalica', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (203, 'Juraj', 'Slafkovský', NULL, 'Košice', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (204, 'Samuel', 'Takáč', '1991-03-12', 'Prievidza', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (205, 'Matej', 'Tomek', NULL, 'Bratislava', 50, NULL, '', NULL);
INSERT INTO `athletes` (`id`, `first_name`, `last_name`, `birth_date`, `birth_place`, `birth_country_id`, `death_date`, `death_place`, `death_country_id`) VALUES (206, 'Peter', 'Zuzin', '1990-04-09', 'Zvolen', 50, NULL, '', NULL);

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_countries_name` (`name`),
  KEY `idx_countries_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `countries` (`id`, `name`, `code`) VALUES (28, 'Grécko', 'GRC');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (29, 'Spojené Štáty Americké', 'USA');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (30, 'Švédsko', 'SWE');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (31, 'Spojené Kráľovstvo', 'GBR');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (32, 'Fínsko', 'FIN');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (33, 'Austrália', 'AUS');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (34, 'Taliansko', 'ITA');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (35, 'Japonsko', 'JPN');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (36, 'Mexiko', 'MEX');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (37, 'Nemecko', 'DEU');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (38, 'Kanada', 'CAN');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (39, 'Sovietsky zväz', 'SUN');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (40, 'Južná Kórea', 'KOR');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (41, 'Španielsko', 'ESP');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (42, 'Čína', 'CHN');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (43, 'Brazília', 'BRA');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (44, 'Francúzsko', 'FRA');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (45, 'Rakúsko', 'AUT');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (46, 'Juhoslávia', 'YUG');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (47, 'Nórsko', 'NOR');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (48, 'Rusko', 'RUS');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (49, 'Kórea', 'PRK');
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (50, 'Slovensko', NULL);
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (51, 'Maďarsko', NULL);
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (52, 'Česko', NULL);
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (53, 'Južná Afrika', NULL);
INSERT INTO `countries` (`id`, `name`, `code`) VALUES (54, 'Chorvátsko', NULL);

DROP TABLE IF EXISTS `disciplines`;
CREATE TABLE `disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_disciplines_category_name` (`category`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (38, 'atletika', 'beh na 100 m');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (50, 'atletika', 'chôdza na 20 km');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (70, 'atletika', 'chôdza na 50 km');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (48, 'atletika', 'hod diskom');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (46, 'atletika', 'skok do diaľky');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (68, 'biatlon', 'hromadný štart');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (66, 'biatlon', 'šprint');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (65, 'biatlon', 'šprint na 7.5 km');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (67, 'biatlon', 'stíhacie preteky na 10 km');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (72, 'biatlon', 'vytrvalostné preteky na 15 km');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (42, 'box', 'do 57 kg');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (41, 'box', 'do 67 kg');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (47, 'dráhová cyklistika', 'šprint');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (43, 'futbal', 'futbal');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (73, 'golf', 'golf');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (59, 'judo', 'do 66 kg');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (71, 'kanoistika', 'K4 na 1000m');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (45, 'krasokorčuľovanie', 'krasokorčuľovanie');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (44, 'ľadový', 'hokej');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (57, 'plávanie', '100 m motýlik');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (58, 'plávanie', '200 m v.sp.');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (39, 'plávanie', '50 yd v.sp.');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (53, 'rýchlostná kanoistika', 'C1 500m');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (60, 'rýchlostná kanoistika', 'K4');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (62, 'snowboarding', 'snowboardcross');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (54, 'športová streľba', 'ľubovoľná malokalibrovka 60');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (69, 'športová streľba', 'skeet');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (63, 'športová streľba', 'trap');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (40, 'športová streľba', 'vojenská puška');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (61, 'športová streľba', 'vzduchová puška 10');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (49, 'tenis', 'dvojhra');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (51, 'tenis', 'štvorhra');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (75, 'test sport', 'test discipline demo');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (52, 'vodný slalom', 'C1');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (56, 'vodný slalom', 'C2');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (55, 'vodný slalom', 'K1');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (64, 'zápasenie', 'voľný štýl do 120 kg');
INSERT INTO `disciplines` (`id`, `category`, `name`) VALUES (74, 'zjazdové lyžovanie', 'slalom');

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

INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (1, 1, 'adamenko.misha.zp@gmail.com', 'local', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-09 22:54:09');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (2, 1, 'adamenko.misha.zp@gmail.com', 'local', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-09 23:01:06');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (3, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-03-09 23:07:15');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (4, 1, 'adamenko.misha.zp@gmail.com', 'local', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-11 14:43:17');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (5, 1, 'adamenko.misha.zp@gmail.com', 'local', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-11 14:44:25');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (6, 1, 'adamenko.misha.zp@gmail.com', 'local', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-11 14:46:04');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (7, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 13:43:27');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (8, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:17:29');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (9, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:17:35');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (10, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:18:07');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (11, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:18:19');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (12, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:18:28');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (13, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:19:25');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (14, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:20:17');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (15, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:20:34');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (16, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-12 23:21:06');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (17, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.113.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-20 12:17:14');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (18, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.113.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-20 12:26:59');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (19, 4, 'roman.kois@stuba.sk', 'google', '147.175.123.33', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-20 13:31:47');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (20, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.113.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-20 13:33:44');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (21, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 14:59:04');
INSERT INTO `login_history` (`id`, `user_id`, `identifier`, `method`, `ip`, `user_agent`, `logged_at`) VALUES (22, 1, 'adamenko.misha.zp@gmail.com', 'google', '147.175.178.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-03-22 15:00:17');

DROP TABLE IF EXISTS `medal_types`;
CREATE TABLE `medal_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `placing` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_medal_types_placing` (`placing`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (8, 3, 'Bronze', 'Medaila');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (9, 1, 'Gold', 'Medaila');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (10, 2, 'Silver', 'Medaila');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (11, 22, '22. miesto', 'Umiestnenie bez medaily');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (12, 8, '8. miesto', 'Umiestnenie bez medaily');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (13, 19, '19. miesto', 'Umiestnenie bez medaily');
INSERT INTO `medal_types` (`id`, `placing`, `name`, `description`) VALUES (14, 4, '4. miesto', 'Umiestnenie bez medaily');

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

INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (41, 'LOH', 1896, 1, 'Atény', 28);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (42, 'LOH', 1904, 3, 'St. Louis', 29);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (43, 'LOH', 1912, 5, 'Štokholm', 30);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (44, 'LOH', 1948, 14, 'Londýn', 31);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (45, 'LOH', 1952, 15, 'Helsinki', 32);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (46, 'LOH', 1956, 16, 'Melbourne', 33);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (47, 'LOH', 1960, 17, 'Rím', 34);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (48, 'LOH', 1964, 18, 'Tokio', 35);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (49, 'LOH', 1968, 19, 'Mexiko', 36);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (50, 'LOH', 1972, 20, 'Mníchov', 37);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (51, 'LOH', 1976, 21, 'Montreal', 38);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (52, 'LOH', 1980, 22, 'Moskva', 39);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (53, 'LOH', 1984, 23, 'Los Angeles', 29);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (54, 'LOH', 1988, 24, 'Soul', 40);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (55, 'LOH', 1992, 25, 'Barcelona', 41);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (56, 'LOH', 1996, 26, 'Atlanta', 29);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (57, 'LOH', 2000, 27, 'Sydney', 33);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (58, 'LOH', 2004, 28, 'Atény', 28);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (59, 'LOH', 2008, 29, 'Peking/Hongkong', 42);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (60, 'LOH', 2012, 30, 'Londýn', 31);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (61, 'LOH', 2016, 31, 'Rio de Janeiro', 43);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (62, 'LOH', 2020, 32, 'Tokio', 35);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (63, 'LOH', 2024, 33, 'Pariz', 44);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (64, 'ZOH', 1964, 9, 'Innsbruck', 45);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (65, 'ZOH', 1968, 10, 'Grenoble', 44);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (66, 'ZOH', 1972, 11, 'Sapporo', 35);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (67, 'ZOH', 1976, 12, 'Innsbruck', 45);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (68, 'ZOH', 1980, 13, 'Lake Placid', 29);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (69, 'ZOH', 1984, 14, 'Sarajevo', 46);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (70, 'ZOH', 1988, 15, 'Calgary', 38);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (71, 'ZOH', 1992, 16, 'Albertville', 44);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (72, 'ZOH', 1994, 17, 'Lillehammer', 47);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (73, 'ZOH', 1998, 18, 'Nagano', 35);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (74, 'ZOH', 2002, 19, 'Salt Lake City', 29);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (75, 'ZOH', 2006, 20, 'Turín', 34);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (76, 'ZOH', 2010, 21, 'Vancouver', 38);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (77, 'ZOH', 2014, 22, 'Soči', 48);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (78, 'ZOH', 2018, 23, 'Pjongčang', 49);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (79, 'ZOH', 2022, 24, 'Peking', 42);
INSERT INTO `olympic_games` (`id`, `type`, `year`, `order_no`, `city`, `country_id`) VALUES (80, 'ZOH', 2026, 25, 'Milano/Cortina', 34);

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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `oauth_provider`, `oauth_sub`, `totp_secret`, `totp_enabled`, `created_at`) VALUES (1, 'Mykhailo', 'Adamenko', 'adamenko.misha.zp@gmail.com', '$2y$12$zUr87OX1rZhbUAb0x.kdGOWmvsyJ/kVzZSA.ilarZL1Zpn892Jtdq', 'google', 108902968792522975326, 'BM23QESZ57AQKVSJLUCFBAWTSHIURJUQ', 1, '2026-03-09 22:53:10');
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `oauth_provider`, `oauth_sub`, `totp_secret`, `totp_enabled`, `created_at`) VALUES (2, 'lxbfYeaa', 'lxbfYeaa', 'testing@example.com', '$2y$12$nYVEisv0q1rVWOWgI5JPeO3Ko4m/ccR72aMIF1xmulBgXt7NXAN/i', NULL, NULL, 'VNM4D2EG6AEXSKMQDE5X7XM5J4QG4OJS', 0, '2026-03-12 14:21:01');
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `oauth_provider`, `oauth_sub`, `totp_secret`, `totp_enabled`, `created_at`) VALUES (3, 'lxbfYeaa', 'lxbfYeaa', 'testing@example.comsIZDqqWB', '$2y$12$My8hXZA4Ynv2OHExM4cdg.vz.xE.8rFKoXgzvFT7ObYeZnU15DqKK', NULL, NULL, 'WQR627WR6433H7BAYJA5A7AG4GHMQBJC', 0, '2026-03-12 14:21:06');
INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `oauth_provider`, `oauth_sub`, `totp_secret`, `totp_enabled`, `created_at`) VALUES (4, 'Roman', 'Koiš', 'roman.kois@stuba.sk', NULL, 'google', 114563212698216067253, NULL, 0, '2026-03-20 13:31:47');

SET FOREIGN_KEY_CHECKS=1;
