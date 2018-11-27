-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 27 nov. 2018 à 10:58
-- Version du serveur :  5.7.23
-- Version de PHP :  7.0.32

SET SQL_MODE = "no_auto_value_on_zero";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @`old_character_set_client` = @@`character_set_client` */;
/*!40101 SET @`old_character_set_results` = @@`character_set_results` */;
/*!40101 SET @`old_collation_connection` = @@`collation_connection` */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `contact`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact`
(
  `id`           INT(10) UNSIGNED                       NOT NULL AUTO_INCREMENT,
  `first_name`   VARCHAR(255) COLLATE `utf8_unicode_ci` NOT NULL,
  `last_name`    VARCHAR(255) COLLATE `utf8_unicode_ci` NOT NULL,
  `surname`      VARCHAR(255) COLLATE `utf8_unicode_ci` DEFAULT NULL,
  `email`        VARCHAR(255) COLLATE `utf8_unicode_ci` DEFAULT NULL,
  `address`      VARCHAR(255) COLLATE `utf8_unicode_ci` DEFAULT NULL,
  `phone_number` VARCHAR(255) COLLATE `utf8_unicode_ci` DEFAULT NULL,
  `birthday`     DATE                                   DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`
  COLLATE = `utf8_unicode_ci`;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs`
(
  `id`      INT(10)                                 NOT NULL AUTO_INCREMENT,
  `level`   SMALLINT(5)                             NOT NULL,
  `message` VARCHAR(2000) COLLATE `utf8_unicode_ci` NOT NULL,
  `file`    VARCHAR(255) COLLATE `utf8_unicode_ci`  NOT NULL,
  `line`    VARCHAR(6) COLLATE `utf8_unicode_ci`    NOT NULL,
  `date`    DATETIME                                NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8`
  COLLATE = `utf8_unicode_ci`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @`old_character_set_client` */;
/*!40101 SET CHARACTER_SET_RESULTS = @`old_character_set_results` */;
/*!40101 SET COLLATION_CONNECTION = @`old_collation_connection` */;
