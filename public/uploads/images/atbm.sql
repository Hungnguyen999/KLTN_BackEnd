-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2015 at 09:16 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `atbm`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblphone`
--

CREATE TABLE IF NOT EXISTS `tblphone` (
`id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `price` varchar(45) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `images` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblphone`
--

INSERT INTO `tblphone` (`id`, `name`, `price`, `description`, `images`) VALUES
(22, 'X-PERIA Z1', '800$', 'Màn hình:	Full HD, 5.0', 'Z1.png'),
(23, 'IPHONE 6', '399$', 'Màn hình:	Retina HD, 4.7, 1334 x 750', 'IP6.png'),
(24, 'GALAXY S6', '500$', 'Màn hình:	Quad HD, 5.1", 1440 x 2560 pixels Camera sau:	16 MP, Quay phim 4K 2160p@30fps', 'S6.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE IF NOT EXISTS `tbluser` (
`id` int(4) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`id`, `username`, `password`) VALUES
(1, 'giangnd', 'giangnd'),
(2, 'giang21199z', 'giang21199z');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblphone`
--
ALTER TABLE `tblphone`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblphone`
--
ALTER TABLE `tblphone`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
