-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2022 at 08:44 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fake_beggar`
--

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `id` int(11) NOT NULL,
  `doner_id` int(10) NOT NULL,
  `beggar_cnic` varchar(256) DEFAULT NULL,
  `amount` varchar(256) NOT NULL,
  `doner_name` varchar(256) NOT NULL,
  `phone_no` varchar(256) NOT NULL,
  `gender` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `description` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `beggar_full_name` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`id`, `doner_id`, `beggar_cnic`, `amount`, `doner_name`, `phone_no`, `gender`, `address`, `description`, `name`, `beggar_full_name`) VALUES
(129, 46, '348rq87843r78q78', '234455', 'Inam', '0445-9887654', 'Male', 'Sawabi', 'hadsuf siadfhcioads', 'd.PNG', 'zahid'),
(130, 46, '348rq87843r78q78', '234455', 'Inam', '0445-9887654', 'Male', 'Sawabi', 'hadsuf siadfhcioads', 'de.PNG', 'zahid'),
(131, 46, '348rq87843r78q78', '234455', 'Inam', '0445-9887654', 'Male', 'Sawabi', 'hadsuf siadfhcioads', 'de.PNG', 'zahid'),
(132, 46, '348rq87843r78q78', '234455', 'Inam', '0445-9887654', 'Male', 'Sawabi', 'hadsuf siadfhcioads', 'de.PNG', 'zahid'),
(133, 46, '348rq87843r78q78', '234455', 'Inam', '0445-9887654', 'Male', 'Sawabi', 'hadsuf siadfhcioads', 'create.PNG', 'zahid');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TEST` (`doner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `TEST` FOREIGN KEY (`doner_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
