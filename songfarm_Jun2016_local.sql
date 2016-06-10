-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2016 at 08:08 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `songfarm-jul2015`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `songcircle_create`
--

INSERT INTO `songcircle_create` (`id`, `songcircle_id`, `created_by_id`, `songcircle_name`, `date_of_songcircle`, `songcircle_permission`, `duration`, `max_participants`, `songcircle_status`) VALUES
(35, '574790373f980', 0, 'Test Songcircle', '2016-06-11 20:00:00', 0, '03:00:00', 2, 1),
(36, '575b140f54674', 0, 'Songfarm Open Songcircle', '2016-06-17 19:30:00', 0, '03:00:00', 2, 0);

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
  `verification_key` varchar(40) DEFAULT NULL,
  `reg_time` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `songcircle_register`
--

INSERT INTO `songcircle_register` (`id`, `songcircle_id`, `user_id`, `confirm_status`, `confirmation_key`, `verification_key`, `reg_time`) VALUES
(14, '574790373f980', 28, 1, '', '123456', '2016-06-10 19:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `songcircle_wait_register`
--

CREATE TABLE IF NOT EXISTS `songcircle_wait_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `songcircle_id` varchar(13) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_status` int(1) NOT NULL DEFAULT '0' COMMENT '0=false, 1=confirmed',
  `confirmation_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_photo`
--

INSERT INTO `user_photo` (`id`, `user_id`, `filename`, `type`, `size`) VALUES
(1, 0, '5616f7616bd7b7.23097310', '.png', 86720),
(2, 1, '5660a4e78bf226.21719669', '.jpeg', 154496),
(3, 2, '5616f921322159.30997850', '.jpeg', 87555),
(4, 28, '56f029efcbac13.01896461', '.jpeg', 7572);

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
  `permission` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=false, 1=has_permission',
  `reg_date` date NOT NULL,
  `unsubscribe_key` varchar(12) NOT NULL COMMENT 'a random key for identifying users with $_GET parameters',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `artist_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Songfarm Artist Sign Up test' AUTO_INCREMENT=43 ;

--
-- Dumping data for table `user_register`
--

INSERT INTO `user_register` (`user_id`, `user_type`, `user_name`, `user_email`, `user_password`, `permission`, `reg_date`, `unsubscribe_key`) VALUES
(0, 1, 'Songfarm', 'david@songfarm.ca', '$2y$10$MC6rznH95/4wEVxPxWRILe74iCMzGOqiLneMgnEWJ3a3QP/EMDZ0S', 1, '2015-09-23', '0'),
(28, 1, 'David Burke Gaskin', 'davidburkegaskin@gmail.com', '$2y$10$TE5UPBNoZndVYzZGmf/1eumj9hjo5Ta5/lM/lDjC12L7W7JK0Onsi', 0, '2016-03-20', 'X2DhLVM3fJNY');

-- --------------------------------------------------------

--
-- Table structure for table `user_songs`
--

CREATE TABLE IF NOT EXISTS `user_songs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `song_id` varchar(23) NOT NULL,
  `song_name` varchar(250) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0=original,1=cover',
  `permission` tinyint(1) NOT NULL COMMENT '0=private,1=public',
  `lyric` text,
  `cover_artist` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='collects user_song data. A user has many songs. User_id is the foreign key' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores user''s selected timezone' AUTO_INCREMENT=16 ;

--
-- Dumping data for table `user_timezone`
--

INSERT INTO `user_timezone` (`id`, `user_id`, `timezone`, `full_timezone`, `city_name`, `country_name`, `country_code`) VALUES
(14, 28, 'America/Vancouver', '(UTC-07:00) Vancouver (PDT)', NULL, 'Belgium', 'BE');

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
