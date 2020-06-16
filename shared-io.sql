-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2020 at 03:26 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shared-io`
--

-- --------------------------------------------------------

--
-- Table structure for table `documentdetails`
--

CREATE TABLE `documentdetails` (
  `doc_id` varchar(255) NOT NULL,
  `doc_name` varchar(255) NOT NULL,
  `owner` varchar(100) NOT NULL,
  `mode` varchar(10) NOT NULL DEFAULT 'allow',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `masterlogin`
--

CREATE TABLE `masterlogin` (
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sharedetails`
--

CREATE TABLE `sharedetails` (
  `share_id` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `doc_id` varchar(255) NOT NULL,
  `last_opened` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documentdetails`
--
ALTER TABLE `documentdetails`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `owner` (`owner`);

--
-- Indexes for table `masterlogin`
--
ALTER TABLE `masterlogin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sharedetails`
--
ALTER TABLE `sharedetails`
  ADD PRIMARY KEY (`share_id`),
  ADD KEY `email` (`email`),
  ADD KEY `sharedetails_ibfk_1` (`doc_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documentdetails`
--
ALTER TABLE `documentdetails`
  ADD CONSTRAINT `documentdetails_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `masterlogin` (`email`);

--
-- Constraints for table `sharedetails`
--
ALTER TABLE `sharedetails`
  ADD CONSTRAINT `sharedetails_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `documentdetails` (`doc_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
