-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 11:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cogact`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `contact_number` varchar(32) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_id` varchar(32) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `contact_number`, `region`, `country`, `postal_id`, `address`) VALUES
(1, 'Patrick', 'patrickbrequillo280@gmail.com', '$2y$10$GiEsRsZYhxnSIcycF/NOz.soWQodmb0r4HKQgK/WUYPBvbq53wUc2', 'Patrick', 'Brequillo', '09611550313', 'REGION 4-A (CALABARZON)', 'Philippines', '1870', 'Block 6, Northville, Barangay Bagong Nayon'),
(2, 'Test', 'testi@gmail.com', '$2y$10$/akC2WNKi7.DlEBcrFiFPOy.wNtVVvBmPj41yW3NCyzivIf4vOBFa', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Steve', 'steve@gmail.com', '$2y$10$cHGw21xDnP55lkAuLPzGROb0E3bJcXPjX8IU4hsHx9C00gyryvO8O', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Carlo', 'carlo@gmail.com', '$2y$10$E3gWOO0Gjh05FHkGf6v97eqCmVCDd3u1ahRw58qKtZTqd7uhjYb0q', 'Carlo', 'Gultian', '09694731145', 'REGION 4-A (CALABARZON)', 'Philippines', '1870', 'Block 6, Northville, Barangay Bagong Nayon'),
(5, 'Arlan', '2022-201039@rtu.edu.ph', '$2y$10$Fl8i55JasMUEGU3GyrNntOOUpVJjEodau8Di.SKGQVnerA3uSl1vO', 'Patrick', 'Brequillo', '09611550313', 'Asia', 'Philippines', '1870', 'Block 6, Northville, Barangay Bagong Nayon');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
