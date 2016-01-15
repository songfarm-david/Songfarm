-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2016 at 11:28 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
  `max_participants` tinyint(2) NOT NULL COMMENT 'current maximum = 12 participants simultaneously',
  `songcircle_status` tinyint(1) DEFAULT '0' COMMENT '0 Not started	1 Started	5 completed',
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`),
  KEY `user_id` (`created_by_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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
  PRIMARY KEY (`id`),
  KEY `songcircle_id` (`songcircle_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `artist_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Songfarm Artist Sign Up test' AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Stores user''s selected timezone' AUTO_INCREMENT=12 ;
