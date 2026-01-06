-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 12, 2024 at 05:07 PM
-- Server version: 10.5.19-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hambire`
--

-- --------------------------------------------------------

--
-- Table structure for table `state_data`
--

INSERT INTO `state_data` (`id`, `code`, `name`, `country_id`) VALUES
(1, 35, 'ANDAMAN AND NICOBAR ISLANDS', '356'),
(2, 28, 'ANDHRA PRADESH', '356'),
(3, 12, 'ARUNACHAL PRADESH', '356'),
(4, 18, 'ASSAM', '356'),
(5, 10, 'BIHAR', '356'),
(6, 4, 'CHANDIGARH', '356'),
(7, 22, 'CHHATTISGARH', '356'),
(8, 7, 'DELHI', '356'),
(9, 30, 'GOA', '356'),
(10, 24, 'GUJARAT', '356'),
(11, 6, 'HARYANA', '356'),
(12, 2, 'HIMACHAL PRADESH', '356'),
(13, 1, 'JAMMU AND KASHMIR', '356'),
(14, 20, 'JHARKHAND', '356'),
(15, 29, 'KARNATAKA', '356'),
(16, 32, 'KERALA', '356'),
(17, 37, 'LADAKH', '356'),
(18, 31, 'LAKSHADWEEP', '356'),
(19, 23, 'MADHYA PRADESH', '356'),
(20, 27, 'MAHARASHTRA', '356'),
(21, 14, 'MANIPUR', '356'),
(22, 17, 'MEGHALAYA', '356'),
(23, 15, 'MIZORAM', '356'),
(24, 13, 'NAGALAND', '356'),
(25, 21, 'ODISHA', '356'),
(26, 34, 'PUDUCHERRY', '356'),
(27, 3, 'PUNJAB', '356'),
(28, 8, 'RAJASTHAN', '356'),
(29, 11, 'SIKKIM', '356'),
(30, 33, 'TAMIL NADU', '356'),
(31, 36, 'TELANGANA', '356'),
(32, 38, 'THE DADRA AND NAGAR HAVELI AND DAMAN AND DIU', '356'),
(33, 16, 'TRIPURA', '356'),
(34, 5, 'UTTARAKHAND', '356'),
(35, 9, 'UTTAR PRADESH', '356'),
(36, 19, 'WEST BENGAL', '356');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `state_data`
--
ALTER TABLE `state_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `state_data`
--
ALTER TABLE `state_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
