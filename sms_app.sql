-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 05:55 PM
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
-- Database: `sms_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `id` int(8) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_sms_tbl`
--

CREATE TABLE `private_sms_tbl` (
  `private_sms_id` int(8) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL,
  `admin_id` int(8) NOT NULL,
  `student_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs_tbl`
--

CREATE TABLE `programs_tbl` (
  `program_id` int(8) NOT NULL,
  `program_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_sms_students_tbl`
--

CREATE TABLE `public_sms_students_tbl` (
  `student_id` int(8) NOT NULL,
  `public_sms_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_sms_tbl`
--

CREATE TABLE `public_sms_tbl` (
  `public_sms_id` int(8) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL,
  `admin_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `student_id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `program_id` int(8) NOT NULL,
  `school` varchar(100) NOT NULL,
  `year_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_sms_tbl`
--
ALTER TABLE `private_sms_tbl`
  ADD PRIMARY KEY (`private_sms_id`),
  ADD KEY `private_sms_tbl_ibfk1` (`admin_id`),
  ADD KEY `private_sms_tbl_ibfk2` (`student_id`);

--
-- Indexes for table `programs_tbl`
--
ALTER TABLE `programs_tbl`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `public_sms_students_tbl`
--
ALTER TABLE `public_sms_students_tbl`
  ADD KEY `public_sms_students_tbl_ibfk1` (`student_id`),
  ADD KEY `public_sms_students_tbl_ibfk2` (`public_sms_id`);

--
-- Indexes for table `public_sms_tbl`
--
ALTER TABLE `public_sms_tbl`
  ADD PRIMARY KEY (`public_sms_id`),
  ADD KEY `public_sms_tbl_ibfk1` (`admin_id`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `student_tb_ibfk1` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_sms_tbl`
--
ALTER TABLE `private_sms_tbl`
  MODIFY `private_sms_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs_tbl`
--
ALTER TABLE `programs_tbl`
  MODIFY `program_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public_sms_tbl`
--
ALTER TABLE `public_sms_tbl`
  MODIFY `public_sms_id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `student_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `private_sms_tbl`
--
ALTER TABLE `private_sms_tbl`
  ADD CONSTRAINT `private_sms_tbl_ibfk1` FOREIGN KEY (`admin_id`) REFERENCES `admin_tbl` (`id`),
  ADD CONSTRAINT `private_sms_tbl_ibfk2` FOREIGN KEY (`student_id`) REFERENCES `student_tbl` (`student_id`);

--
-- Constraints for table `public_sms_students_tbl`
--
ALTER TABLE `public_sms_students_tbl`
  ADD CONSTRAINT `public_sms_students_tbl_ibfk1` FOREIGN KEY (`student_id`) REFERENCES `student_tbl` (`student_id`),
  ADD CONSTRAINT `public_sms_students_tbl_ibfk2` FOREIGN KEY (`public_sms_id`) REFERENCES `public_sms_tbl` (`public_sms_id`);

--
-- Constraints for table `public_sms_tbl`
--
ALTER TABLE `public_sms_tbl`
  ADD CONSTRAINT `public_sms_tbl_ibfk1` FOREIGN KEY (`admin_id`) REFERENCES `admin_tbl` (`id`);

--
-- Constraints for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD CONSTRAINT `student_tb_ibfk1` FOREIGN KEY (`program_id`) REFERENCES `programs_tbl` (`program_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
