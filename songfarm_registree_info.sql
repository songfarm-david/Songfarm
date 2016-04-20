-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 20, 2016 at 02:35 PM
-- Server version: 5.5.48-MariaDB
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `songfarm_registree_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `songcircle_create`
--

CREATE TABLE IF NOT EXISTS `songcircle_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `songcircle_id` varchar(13) NOT NULL,
  `created_by_id` int(11) NOT NULL DEFAULT '0' COMMENT '0 = Songfarm Global User',
  `songcircle_name` varchar(75) NOT NULL,
  `date_of_songcircle` datetime NOT NULL COMMENT 'All times are UTC',
  `songcircle_permission` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=public, 1=private',
  `duration` time NOT NULL,
  `max_participants` tinyint(2) NOT NULL COMMENT 'current maximum = 12 participants simultaneously',
  `songcircle_status` tinyint(1) DEFAULT '0' COMMENT '0 Not started	1 Started	5 completed',
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`),
  KEY `user_id` (`created_by_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `songcircle_register`
--

CREATE TABLE IF NOT EXISTS `songcircle_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `songcircle_id` varchar(13) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=false, 1=true',
  `confirmation_key` varchar(40) NOT NULL COMMENT 'a 40-length random character string',
  `verification_key` varchar(40) DEFAULT NULL COMMENT 'for verifying start_call.php',
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

-- --------------------------------------------------------

--
-- Table structure for table `songcircle_wait_register`
--

CREATE TABLE IF NOT EXISTS `songcircle_wait_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `songcircle_id` varchar(13) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_status` int(1) NOT NULL DEFAULT '0',
  `confirmation_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_photo`
--

CREATE TABLE IF NOT EXISTS `user_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Foreign Key to Registree_info',
  `filename` varchar(23) NOT NULL,
  `type` varchar(20) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename_2` (`filename`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_register`
--

CREATE TABLE IF NOT EXISTS `user_register` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` tinyint(1) DEFAULT NULL COMMENT '1=artist,2=industry,3=fan',
  `user_name` varchar(60) NOT NULL,
  `user_email` varchar(80) NOT NULL,
  `user_password` varchar(60) DEFAULT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT '0',
  `reg_date` date NOT NULL,
  `user_key` varchar(12) NOT NULL COMMENT 'a random key for identifying users with $_GET parameters',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Contains Registree Info for users who register at Songfarm.ca' AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_timezone`
--

CREATE TABLE IF NOT EXISTS `user_timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `full_timezone` varchar(100) NOT NULL COMMENT 'fully formatted timezone',
  `city_name` varchar(100) DEFAULT NULL,
  `country_name` varchar(100) NOT NULL,
  `country_code` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores user''s selected timezone' AUTO_INCREMENT=51 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `songcircle_register`
--
ALTER TABLE `songcircle_register`
  ADD CONSTRAINT `songcircle_register_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_register` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `songcircle_wait_register`
--
ALTER TABLE `songcircle_wait_register`
  ADD CONSTRAINT `songcircle_wait_register_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_register` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_timezone`
--
ALTER TABLE `user_timezone`
  ADD CONSTRAINT `user_timezone_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_register` (`user_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
