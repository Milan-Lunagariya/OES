-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2024 at 10:02 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parentid` int(11) NOT NULL,
  `createdat` varchar(50) NOT NULL,
  `updatedat` varchar(50) NOT NULL,
  `images` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryid`, `name`, `parentid`, `createdat`, `updatedat`, `images`) VALUES
(32, 'Mobile', 0, '18-August-2024 , 13:12:06', '', '[\"1723966926_mobiles.jpeg\"]'),
(33, 'iPhone 12', 32, '18-August-2024 , 13:20:20', '', '[\"1723967420_iphone_12.jpeg\"]'),
(34, 'iPhone 13', 32, '18-August-2024 , 13:20:52', '', '[\"1723967452_iphone_13.jpeg\"]'),
(35, 'IPhone 14', 32, '18-August-2024 , 13:21:28', '', '[\"1723967488_iphone_14.jpeg\"]'),
(36, 'Laptops', 0, '18-August-2024 , 13:23:26', '', '[\"1723967606_laptops.jpeg\"]'),
(37, 'HP', 36, '18-August-2024 , 13:25:04', '', '[\"1723967704_hp.jpeg\"]'),
(38, 'Lenovo', 36, '18-August-2024 , 13:25:21', '', '[\"1723967721_lenovo.jpeg\"]'),
(39, 'Mackbook', 36, '18-August-2024 , 13:25:42', '', '[\"1723967742_macbook.jpeg\"]'),
(40, 'Accessories', 0, '18-August-2024 , 13:26:38', '', '[\"1723967798_powerbanks.jpeg\"]'),
(41, 'Cable All in One', 40, '18-August-2024 , 13:29:53', '', '[\"1723967993_multi_cable.jpeg\"]'),
(42, 'Smart Speaker', 40, '18-August-2024 , 13:30:09', '', '[\"1723968009_smart_speaker.jpeg\"]'),
(43, 'USB Cover', 40, '18-August-2024 , 13:30:35', '', '[\"1723968035_usb_cover.jpeg\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
