-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2023 at 06:57 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nlrc`
--

-- --------------------------------------------------------

--
-- Table structure for table `dtr`
--

CREATE TABLE `dtr` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time_in` varchar(255) NOT NULL,
  `time_out` varchar(255) DEFAULT NULL,
  `work_base` varchar(255) NOT NULL DEFAULT 'Office' COMMENT 'WFH | Office | WFH/Office',
  `shift_reason` varchar(5000) DEFAULT NULL,
  `per_hour` int(255) NOT NULL COMMENT 'fetch current salary grade of employee, foreign key not required',
  `paid` varchar(255) NOT NULL DEFAULT 'no' COMMENT 'yes | no',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dtr`
--

INSERT INTO `dtr` (`id`, `user_id`, `date`, `time_in`, `time_out`, `work_base`, `shift_reason`, `per_hour`, `paid`, `date_created`, `date_updated`) VALUES
(17, 2, '02/01/2023', '08:00', '12:00', 'WFH', NULL, 77, 'no', '2023-02-08 09:03:30', '2023-02-09 09:03:00'),
(18, 2, '02/01/2023', '13:00', '15:00', 'WFH', 'Do nothing', 77, 'no', '2023-02-08 09:04:08', '2023-02-10 06:16:57'),
(19, 27, '02/08/2023', '08:00', '16:00', 'Office', NULL, 77, 'no', '2023-02-08 09:15:21', '2023-02-10 06:13:17'),
(20, 2, '02/10/2023', '05:00', '15:00', 'WFH', NULL, 77, 'no', '2023-02-10 07:33:59', '2023-02-10 07:34:14'),
(21, 2, '02/10/2023', '16:00', '17:00', 'Office', NULL, 77, 'no', '2023-02-10 07:38:20', '2023-02-10 07:39:14'),
(30, 2, '02/14/2023', '08:00', '12:00', 'Office', NULL, 77, 'no', '2023-02-14 03:00:08', '2023-02-14 03:01:29'),
(31, 2, '12/14/2023', '13:00', NULL, 'Office', NULL, 77, 'no', '2023-02-14 03:01:44', '2023-02-14 03:01:44'),
(50, 27, '02/08/2023', '17:00', '18:00', 'Office', 'Personal Errand', 85, 'no', '2023-02-15 03:19:59', '2023-02-15 03:21:25'),
(51, 27, '02/15/2023', '08:00', '13:00', 'Office', NULL, 85, 'no', '2023-02-15 05:56:21', '2023-02-16 02:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `request_ot`
--

CREATE TABLE `request_ot` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `task` varchar(5000) NOT NULL,
  `date` varchar(255) NOT NULL COMMENT 'mm/dd/yyyy',
  `time` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'pending' COMMENT 'pending/approved/denied',
  `reason_denied` varchar(5000) DEFAULT NULL COMMENT 'required only when status is  denied',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_ot`
--

INSERT INTO `request_ot` (`id`, `user_id`, `task`, `date`, `time`, `status`, `reason_denied`, `date_created`, `date_updated`) VALUES
(26, 2, 'System Updates\r\nRP Applications and Renewals\r\nOther Stuffs', '02/01/2023', '08:00-12:00 13:00-15:00 ', 'pending', NULL, '2023-02-08 09:03:04', '2023-02-13 06:41:11'),
(31, 27, 'This is a task\r\nAnother task', '02/25/2023', '08:00-12:00 13:00-17:00 ', 'pending', NULL, '2023-02-21 06:50:12', '2023-02-21 06:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `salary_grade`
--

CREATE TABLE `salary_grade` (
  `id` int(255) NOT NULL,
  `grade_number` varchar(255) NOT NULL,
  `hourly_rate` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salary_grade`
--

INSERT INTO `salary_grade` (`id`, `grade_number`, `hourly_rate`, `date_created`, `date_updated`) VALUES
(1, '1', '62.5', '2023-01-20 02:18:55', '2023-01-30 05:17:35'),
(2, '2', '76.875', '2023-01-20 02:18:55', '2023-01-20 02:21:50'),
(3, '3', '85.25', '2023-01-20 02:18:55', '2023-01-20 02:21:54'),
(4, '4', '93.75', '2023-01-20 02:18:55', '2023-01-20 02:22:06'),
(5, '5', '103.5', '2023-01-30 03:47:07', '2023-01-30 03:47:07'),
(6, '6', '110.9', '2023-01-30 05:18:52', '2023-01-30 05:18:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `gender` varchar(6) NOT NULL COMMENT 'male | female',
  `date_of_birth` varchar(255) NOT NULL COMMENT 'mm/dd/yyyy',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(255) NOT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `salary_grade` int(255) DEFAULT NULL COMMENT 'if user is admin then null',
  `archive` int(1) NOT NULL DEFAULT '0' COMMENT '0(active)-1(archived)',
  `added_by` int(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `profile_pic`, `email`, `mobile_number`, `gender`, `date_of_birth`, `username`, `password`, `user_type`, `schedule`, `salary_grade`, `archive`, `added_by`, `date_created`, `date_updated`) VALUES
(1, 'Jushua FF', '1673493569.jpg', 'jushuaff@gmail.com', '09182015245', 'Male', '02/02/1999', 'admin', '$2y$12$ahNQLaxIZMqqjd7MFGuWFuyQg/C61lzCK5GhjQXbcrngywlMUmzLi', 1, NULL, NULL, 0, 1, '2022-11-10 01:45:58', '2023-02-14 03:42:24'),
(2, 'Jane Gaong', '1674116165.jpg', 'jgaong@gmail.com', '', 'Female', '05/09/1991', 'recruitment', '$2y$12$dU/jQP78ajHTNFD.PDLPC.E6aj0XCDnk.la6Nxe1.ePM.gqlRvG1y', 2, 'flexible', 2, 0, 1, '2022-11-10 01:45:58', '2023-02-13 09:12:05'),
(27, 'Jyro', '1676267090.jpg', 'sustento@gmail.com', '90182738278', 'Male', '06/04/2004', 'jyro', '$2y$12$aOuvRwL8GQJiTUcFxhigC.1aitba4KFJ.eiL1RTG.3oG.fnXK57NK', 3, 'fixed-09:00-18:00', 3, 0, 1, '2023-01-05 06:00:36', '2023-02-13 06:20:59'),
(28, 'Vivienne', '1674205177.jpg', 'yanny@email.com', '', 'Female', '12/15/2003', 'yanny', '$2y$12$wYdCONrdadwtRbt3wWHoSuDFSvHhBh/IJn2ViBoXoGQHiBD2NDMXO', 3, 'flexible', 2, 0, 1, '2023-01-06 06:49:18', '2023-02-07 07:41:19'),
(29, 'Jethro', '', 'jethro@gmail.com', '', 'Male', '08/06/2004', 'jethro', '$2y$12$aPOb/fN6.rRXzodhttdHNeakh8Uasz9VI8RzFCi7zAMy4rbTxcmS6', 1, NULL, NULL, 1, 1, '2023-01-16 01:35:50', '2023-02-21 07:13:10'),
(30, 'Miguel Pocyoy', '', 'megs@gmail.com', '', 'Male', '06/04/1998', 'megs', '$2y$12$a3cIsm3wTpRRNj0n4NPoFePTWDUykYepLYNvBwcEVoAUWHAtFXNH.', 1, NULL, NULL, 0, 1, '2023-01-16 01:48:46', '2023-01-25 08:50:46'),
(32, 'John', '', 'john@gmail.com', '09482634545', 'Male', '11/19/2003', 'john', '$2y$12$dKWB/97dnPznCbgX0dCAbe5lxySpRVgMGaW4mUN9Zvhhk3w9aNice', 3, 'fixed-08:00-16:00', 2, 0, 1, '2023-01-25 06:07:35', '2023-01-25 09:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `user_type`, `date_created`, `date_updated`) VALUES
(1, 'admin', '2022-11-10 01:42:36', '2022-11-10 01:42:36'),
(2, 'recruitment', '2022-11-10 01:42:36', '2022-11-10 01:42:36'),
(3, 'monitoring', '2022-12-13 08:56:40', '2022-12-13 08:56:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dtr`
--
ALTER TABLE `dtr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `request_ot`
--
ALTER TABLE `request_ot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_user_request_ot` (`user_id`);

--
-- Indexes for table `salary_grade`
--
ALTER TABLE `salary_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `salary_grade` (`salary_grade`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dtr`
--
ALTER TABLE `dtr`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `request_ot`
--
ALTER TABLE `request_ot`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `salary_grade`
--
ALTER TABLE `salary_grade`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dtr`
--
ALTER TABLE `dtr`
  ADD CONSTRAINT `dtr_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_ot`
--
ALTER TABLE `request_ot`
  ADD CONSTRAINT `FK_user_request_ot` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `salary_grade` FOREIGN KEY (`salary_grade`) REFERENCES `salary_grade` (`id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type`) REFERENCES `user_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
