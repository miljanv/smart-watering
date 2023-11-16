-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2023 at 03:35 PM
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
-- Database: `smart_watering`
--

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `name`) VALUES
(1, 'Device 1'),
(2, 'Device 2');

-- --------------------------------------------------------

--
-- Table structure for table `sensor`
--

CREATE TABLE `sensor` (
  `id` int(11) NOT NULL,
  `device_id` int(10) UNSIGNED NOT NULL,
  `type` enum('humidity','ph') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor`
--

INSERT INTO `sensor` (`id`, `device_id`, `type`, `value`, `timestamp`) VALUES
(53, 1, 'humidity', 50.50, '2023-01-15 07:30:00'),
(54, 1, 'ph', 7.20, '2023-02-22 08:15:00'),
(55, 2, 'humidity', 45.80, '2023-03-12 09:00:00'),
(56, 2, 'ph', 6.80, '2023-04-18 08:45:00'),
(57, 1, 'humidity', 48.30, '2023-05-07 09:30:00'),
(58, 1, 'ph', 7.10, '2023-06-11 10:15:00'),
(59, 2, 'humidity', 44.50, '2023-07-26 11:00:00'),
(60, 2, 'ph', 6.90, '2023-08-09 11:45:00'),
(61, 1, 'humidity', 51.20, '2023-09-14 12:30:00'),
(62, 1, 'ph', 7.00, '2023-10-20 13:15:00'),
(63, 2, 'humidity', 46.70, '2023-11-05 15:00:00'),
(64, 2, 'ph', 6.70, '2023-12-01 15:45:00'),
(65, 1, 'humidity', 49.80, '2023-01-25 16:30:00'),
(66, 1, 'ph', 7.30, '2023-02-03 17:15:00'),
(67, 2, 'humidity', 47.10, '2023-03-17 18:00:00'),
(68, 2, 'ph', 7.40, '2023-04-22 17:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `timestamp`) VALUES
(6, 'admin', '$2y$10$a6J.OyJ5z2InSK7NTx8GC.vhRUV1zDnu0KZ3ncB0LpOL0yDyPFrYi', '2023-11-15 19:16:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sensor`
--
ALTER TABLE `sensor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sensor`
--
ALTER TABLE `sensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sensor`
--
ALTER TABLE `sensor`
  ADD CONSTRAINT `sensor_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `device` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
