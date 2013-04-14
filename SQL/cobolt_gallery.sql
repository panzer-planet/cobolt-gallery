-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 01, 2012 at 01:52 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cobolt_gallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `icons`
--

CREATE TABLE IF NOT EXISTS `icons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon_set` varchar(25) NOT NULL,
  `icon_filename` varchar(25) NOT NULL,
  `icon_name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `icons`
--

INSERT INTO `icons` (`id`, `icon_set`, `icon_filename`, `icon_name`) VALUES
(1, 'glyph', 'next_arrow.png', 'next_arrow'),
(2, 'glyph', 'previous_arrow.png', 'previous_arrow'),
(3, 'glyph', 'admin_button.png', 'admin_button'),
(4, 'glyph', 'view_thumbnails.png', 'view_thumbnails'),
(5, 'glyph', 'previous_page.png', 'previous_page'),
(6, 'glyph', 'next_page.png', 'next_page'),
(7, 'glyph', 'log_out', 'log_out');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullsize_file_name` varchar(200) NOT NULL,
  `thumbnail_file_name` varchar(200) NOT NULL,
  `pagesize_file_name` varchar(200) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `caption` text,
  `disk_usage` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting`, `value`) VALUES
(1, 'x_thumbs', '3'),
(2, 'y_thumbs', '3'),
(3, 'max_file_size', '51200000'),
(4, 'thumbs_width', '150'),
(5, 'pagesize_width', '710'),
(6, 'image_storage_location', '/cobolt-gallery/gallery_images/');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
