-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `award_categorys`;
CREATE TABLE `award_categorys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `season_id` int(10) unsigned NOT NULL,
  `award` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  CONSTRAINT `award_categorys_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `award_seasons` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `award_category_nominations`;
CREATE TABLE `award_category_nominations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `season_id` int(10) unsigned NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `nominees` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  CONSTRAINT `award_category_nominations_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `award_seasons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `award_nominations`;
CREATE TABLE `award_nominations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `title` text CHARACTER SET utf8,
  `url` text CHARACTER SET utf8,
  `ip` varchar(40) CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category_id`),
  CONSTRAINT `award_nominations_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `award_categorys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `award_nominees`;
CREATE TABLE `award_nominees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `name` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_id` (`category_id`),
  CONSTRAINT `award_nominees_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `award_categorys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `award_seasons`;
CREATE TABLE `award_seasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `promo` text COLLATE utf8_unicode_ci NOT NULL,
  `current` enum('categories','nominations','voting','show') COLLATE utf8_unicode_ci NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `access_secret` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `categories_start` datetime NOT NULL,
  `categories_end` datetime NOT NULL,
  `nominations_start` datetime NOT NULL,
  `nominations_end` datetime NOT NULL,
  `voting_start` datetime NOT NULL,
  `voting_end` datetime NOT NULL,
  `awards_show` datetime NOT NULL,
  `awards_show_youtube` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `award_votes`;
CREATE TABLE `award_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nominee_id` int(10) unsigned NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nominees_id` (`nominee_id`),
  CONSTRAINT `award_votes_ibfk_1` FOREIGN KEY (`nominee_id`) REFERENCES `award_nominees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2014-12-18 19:28:33
