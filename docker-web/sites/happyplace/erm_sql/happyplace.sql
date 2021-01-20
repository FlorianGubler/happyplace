-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `apprentices`;
CREATE TABLE `apprentices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prename` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `place_id` int unsigned NOT NULL,
  `markers_id` int NOT NULL,
  PRIMARY KEY (`id`,`place_id`,`markers_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_apprentices_place_idx` (`place_id`),
  KEY `fk_apprentices_markers1_idx` (`markers_id`),
  CONSTRAINT `fk_apprentices_markers1` FOREIGN KEY (`markers_id`) REFERENCES `markers` (`id`),
  CONSTRAINT `fk_apprentices_place` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `apprentices` (`id`, `prename`, `lastname`, `place_id`, `markers_id`) VALUES
(1,	'Florian',	'Gubler',	1,	1),
(11,	'Oliver',	'Janka',	2,	2),
(12,	'Oliver',	'Arisona',	3,	3),
(13,	'Jon',	'Bunjaku',	4,	4);

DROP TABLE IF EXISTS `markers`;
CREATE TABLE `markers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `color` varchar(255) DEFAULT '#FFFFFF',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `markers` (`id`, `color`) VALUES
(1,	'#cc2c21'),
(2,	'#ff0040'),
(3,	'#00ffd5'),
(4,	'#00fbff');

DROP TABLE IF EXISTS `places`;
CREATE TABLE `places` (
  `id` int unsigned NOT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `places` (`id`, `latitude`, `longitude`) VALUES
(1,	'47.353820061790515',	'8.600858651936793'),
(2,	'22',	'88'),
(3,	'44',	'66'),
(4,	'77',	'66');

-- 2021-01-06 11:22:33
