-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 10, 2019 at 09:36 AM
-- Server version: 5.7.24-0ubuntu0.18.10.1
-- PHP Version: 7.2.10-0ubuntu1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recordsys`
--
CREATE DATABASE IF NOT EXISTS `recordsys` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `recordsys`;

-- --------------------------------------------------------

--
-- Table structure for table `RECORDS`
--

DROP TABLE IF EXISTS `RECORDS`;
CREATE TABLE `RECORDS` (
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `doc_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `visit_date` date NOT NULL,
  `occupation` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `n_people` int(11) NOT NULL,
  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `meal_price` float NOT NULL,
  `meal_quantity` int(11) NOT NULL,
  `personal_room` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `personal_room_quantity` int(11) NOT NULL,
  `group_room` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `group_room_quantity` int(11) NOT NULL,
  `meeting_room` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
CREATE TABLE `USERS` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `auth_hash` text COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `USERS`
--

INSERT INTO `USERS` (`id`, `username`, `auth_hash`, `is_admin`) VALUES
(0, 'backup_user', '$argon2i$v=19$m=1024,t=2,p=2$VkJDYzVwR1hScU5DdlMvMg$sNQ8/896v5us2jrIvj6KwZE2kBGaAhuq+gCG2IlQvFk', 1),
(1, 'JasonJa', '$argon2i$v=19$m=1024,t=2,p=2$eThVRDRJLkhjMXd4TGJMNw$5DWloaeibSxlenFopHtGI2Rb9/Rxyd/k0s93XVXkOXM', 1),
(2, 'Turbo', '$argon2i$v=19$m=1024,t=2,p=2$Si8uYlhIVGdFL2p3eUIvYw$tSHang23WkHTFmhirkHL9xvYayROjTyFNU7y0jcAmbo', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `RECORDS`
--
ALTER TABLE `RECORDS`
  ADD PRIMARY KEY (`record_time`);

--
-- Indexes for table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `USERS`
--
ALTER TABLE `USERS`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
