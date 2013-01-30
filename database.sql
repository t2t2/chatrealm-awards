-- Adminer 3.6.2 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `award_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `award` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `award_nominations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(32) CHARACTER SET utf8 NOT NULL,
  `title` text CHARACTER SET utf8,
  `url` text CHARACTER SET utf8,
  `ip` varchar(40) CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `award_nominees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categories_id` int(10) unsigned NOT NULL,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_id` (`categories_id`),
  CONSTRAINT `award_nominees_ibfk_1` FOREIGN KEY (`categories_id`) REFERENCES `award_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `award_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nominees_id` int(10) unsigned NOT NULL,
  `ip` varchar(40) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nominees_id` (`nominees_id`),
  CONSTRAINT `award_votes_ibfk_1` FOREIGN KEY (`nominees_id`) REFERENCES `award_nominees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2013-01-30 06:54:29
