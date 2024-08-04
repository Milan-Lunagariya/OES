-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2024 at 06:42 PM
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
(1, 'Mobile', 0, '28-July-2024 , 20:08:15', '', ''),
(2, 'iPhone', 1, '28-July-2024 , 20:08:30', '', ''),
(3, 'iPhone 11', 2, '28-July-2024 , 20:08:47', '', ''),
(4, 'iPhone 14', 2, '28-July-2024 , 20:10:23', '', ''),
(5, 'SamSung', 1, '29-July-2024 , 04:43:08', '', ''),
(6, 'SamSung G7', 5, '29-July-2024 , 04:43:34', '', ''),
(7, 'Laptop', 0, '29-July-2024 , 04:44:31', '', ''),
(8, 'DEll', 7, '29-July-2024 , 04:44:43', '', ''),
(9, 'Lanovo', 7, '29-July-2024 , 04:44:51', '', ''),
(10, 'Dell Inspirion', 8, '29-July-2024 , 04:45:05', '', ''),
(11, 'jern', 0, '31-July-2024 , 18:08:00', '', ''),
(12, 'Test', 0, '02-August-2024 , 17:52:54', '', ''),
(13, 'Test the', 0, '02-August-2024 , 17:53:14', '', ''),
(14, '02 Aug 2024 2131', 8, '02-August-2024 , 18:01:29', '', ''),
(15, 'New', 0, '02-August-2024 , 18:14:06', '', ''),
(16, 'dwe', 0, '02-August-2024 , 18:14:26', '', ''),
(17, 'Hello', 0, '02-August-2024 , 18:14:54', '', ''),
(18, 'ewbjbjk', 0, '02-August-2024 , 18:15:27', '', ''),
(19, 'cwe we', 1, '02-August-2024 , 18:15:34', '', ''),
(20, 'O', 0, '02-August-2024 , 18:16:52', '', ''),
(21, '0', 0, '02-August-2024 , 18:17:17', '', ''),
(22, 'Tets', 1, '02-August-2024 , 18:20:00', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `images` text NOT NULL,
  `name` varchar(11) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `stock` smallint(11) NOT NULL,
  `createdat` varchar(50) NOT NULL,
  `updateid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` mediumint(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(60) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, 'John_deo', 'john@demo.com', '3585a09c9140e4697e33503111bbf9aa', 'John', 'Deo', '127', '2024-06-28', '2024-06-28'),
(2, '', '', '', '', '', '0', '2024-06-28', '2024-06-28'),
(3, '', '', '', '', '', '127', '2024-06-28', '2024-06-28'),
(4, 'test', 'test', 'test', 'test', 'test', '0', '2024-06-28', '2024-06-28'),
(6, 'John', 'john@deo.com', 'f56a281d40ae181c84658f6c0f550a2a', 'John', 'Deo', '1234567890', '2024-06-28', '2024-06-28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` mediumint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
