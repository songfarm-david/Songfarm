-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2016 at 07:46 PM
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
-- Table structure for table `songcircle_register`
--

CREATE TABLE IF NOT EXISTS `songcircle_register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `songcircle_id` varchar(13) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirm_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=false, 1=true',
  `confirmation_key` varchar(40) NOT NULL COMMENT 'a 40-length random character string',
  `verification_key` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
