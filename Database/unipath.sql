-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 09:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unipath`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `account_id` int(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`account_id`, `username`, `password`, `usertype`, `datecreated`) VALUES
(1, 'admin', '123', 'Jobseeker', '04/05/2025'),
(28, 'tine', '123', '', '04/11/2025'),
(29, 'cristine', '123', '', '04/11/2025'),
(30, 'meina', '123', '', '04/12/2025'),
(31, 'judd', '123', '', '04/14/2025'),
(32, 'employer1', '123', '', '04/14/2025'),
(33, 'employer2', '123', 'Employer', '04/14/2025'),
(34, 'employer3', '123', '', '04/14/2025'),
(35, 'employer4', '123', 'Employer', '04/14/2025'),
(36, 'user1', '123', 'Jobseeker', '04/14/2025'),
(37, 'user2', '123', '', '04/14/2025'),
(38, 'user3', '123', 'Jobseeker', '04/14/2025'),
(39, 'user4', '123', 'Employer', '04/14/2025'),
(40, 'employer5', '123', 'Employer', '04/14/2025'),
(41, 'employer6', '123', '', '04/14/2025'),
(42, 'employer7', '123', 'Employer', '04/14/2025'),
(43, 'employer8', '123', '', '04/14/2025'),
(44, 'Ricafort', '123', 'Employer', '04/14/2025'),
(45, 'employer9', '123', '', '04/14/2025'),
(46, 'employer10', '123', 'Employer', '04/14/2025'),
(47, 'jr', '123', '', '04/15/2025'),
(48, 'jayR', '123', 'Jobseeker', '04/15/2025'),
(49, 'user10', '123', 'Jobseeker', '04/16/2025'),
(50, 'Rato', '123', 'Jobseeker', '04/16/2025'),
(51, 'Luna', '123', 'Jobseeker', '04/17/2025'),
(61, 'admin1', '123', 'Admin', '3/28/2025'),
(62, 'admin2', '123', 'Admin', '04/05/2025'),
(63, 'employer11', '123', 'Jobseeker', '04/19/2025'),
(64, 'employer12', '123', 'Employer', '04/19/2025'),
(65, 'employer20', '123', 'Employer', '04/19/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblapplications`
--

CREATE TABLE `tblapplications` (
  `application_id` int(50) NOT NULL,
  `account_id` int(50) NOT NULL,
  `job_id` int(50) NOT NULL,
  `profile_id` int(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblapplications`
--

INSERT INTO `tblapplications` (`application_id`, `account_id`, `job_id`, `profile_id`, `datecreated`) VALUES
(1, 1, 1, 3, '3/28/2025'),
(2, 38, 1, 9, '04/14/2025'),
(3, 36, 0, 14, '2025-04-16 00:00:55'),
(4, 36, 0, 14, '2025-04-16 00:04:15'),
(5, 36, 1, 14, '2025-04-16 00:07:36'),
(6, 36, 3, 14, '2025-04-16 00:07:53'),
(7, 36, 2, 14, '04/16/2025'),
(8, 49, 2, 19, '04/16/2025'),
(9, 36, 3, 14, '04/16/2025'),
(10, 49, 0, 19, '04/16/2025'),
(11, 49, 0, 19, '04/16/2025'),
(12, 49, 0, 19, '04/16/2025'),
(13, 49, 0, 19, '04/16/2025'),
(14, 49, 0, 19, '04/16/2025'),
(15, 49, 0, 19, '04/16/2025'),
(16, 49, 0, 19, '04/16/2025'),
(17, 49, 1, 19, '04/16/2025'),
(18, 49, 1, 19, '04/16/2025'),
(19, 49, 1, 19, '04/16/2025'),
(20, 49, 2, 19, '04/16/2025'),
(21, 49, 2, 19, '04/16/2025'),
(22, 49, 2, 19, '04/16/2025'),
(23, 49, 3, 19, '04/16/2025'),
(24, 49, 1, 19, '04/16/2025'),
(25, 50, 1, 20, '04/16/2025'),
(26, 50, 2, 20, '04/16/2025'),
(27, 51, 1, 21, '04/17/2025'),
(28, 51, 2, 21, '04/17/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblcompany`
--

CREATE TABLE `tblcompany` (
  `company_id` int(50) NOT NULL,
  `account_id` int(50) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_logo` varchar(100) NOT NULL,
  `company_about` varchar(100) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcompany`
--

INSERT INTO `tblcompany` (`company_id`, `account_id`, `company_name`, `company_logo`, `company_about`, `datecreated`) VALUES
(8, 33, 'Mcdonalds', 'download.jfif', '123', ''),
(9, 34, 'Jollibee', '', '', ''),
(10, 35, 'Bahay', '', '', ''),
(11, 40, 'Mcdo', '', '', ''),
(12, 41, 'Concentrix', '', '', ''),
(14, 46, 'Ricafort Co.', '', '', ''),
(15, 63, 'Jollibee', '', '', ''),
(16, 64, 'Jollibee', '', '', ''),
(17, 65, 'Watsons', '', '', '04/19/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tbljobs`
--

CREATE TABLE `tbljobs` (
  `job_id` int(50) NOT NULL,
  `company_id` int(50) NOT NULL,
  `job_name` varchar(100) NOT NULL,
  `job_location` varchar(250) NOT NULL,
  `max_salary` varchar(20) NOT NULL,
  `min_salary` varchar(20) NOT NULL,
  `job_type` varchar(50) NOT NULL,
  `job_about` varchar(500) NOT NULL,
  `status` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbljobs`
--

INSERT INTO `tbljobs` (`job_id`, `company_id`, `job_name`, `job_location`, `max_salary`, `min_salary`, `job_type`, `job_about`, `status`, `datecreated`) VALUES
(4, 8, 'TAGALUTO', 'manila', '1', '2', 'Part-time', 'asd', 'approved', '3/28/2025'),
(5, 8, 'Manager', 'Quezon', '1', '2', 'Full-Time', 'Handling the crew ', 'Approved', '04/14/2025'),
(6, 8, 'Manager', 'Manila', '1', '2', 'Part-time', 'Handling the crew', 'Declined', '3/28/2025'),
(7, 8, '123', '123', '123', '123', 'Full-Time', '123', 'Pending', '2025-04-19 00:34:17'),
(8, 8, 'Assistant Manager', 'Manila', '200', '123', 'Full-Time', 'Handling Schedule ', 'Approved', '2025-04-19 00:34:54'),
(10, 8, 'Branch Manager', 'Quezon City', '200', '100', 'Full-Time', 'asd', 'Pending', '2025-04-19 00:46:31'),
(11, 8, 'Assistant Manager', 'Manila', '200', '123', 'Freelance', 'Handling Schedule ', 'Pending', '2025-04-19 00:54:46'),
(12, 16, 'Area Manager', 'Metro Manila', '2000', '1000', 'Full-Time', 'Handling all the branch', 'Approved', '2025-04-19 02:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `tblprofiles`
--

CREATE TABLE `tblprofiles` (
  `profile_id` int(50) NOT NULL,
  `account_id` int(50) NOT NULL,
  `profile_pic` varchar(100) NOT NULL,
  `resume` varchar(100) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `birthdate` varchar(50) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblprofiles`
--

INSERT INTO `tblprofiles` (`profile_id`, `account_id`, `profile_pic`, `resume`, `lastname`, `firstname`, `birthdate`, `phone_number`, `email`, `street`, `city`, `datecreated`) VALUES
(3, 1, 'bravo.png', 'test.pdf', 'Rato', 'Ria Marion', '2004-08-09', '1123-456-7890', 'Ria.OO@gmail.com', 'Anywhere St.', 'Any City', ''),
(10, 28, '', '', 'V', 'Cristine', '', '123', '', '', '', ''),
(11, 29, '476951575_1991327934712519_211526852942050342_n.jpg', '', 'V', 'Cristine Mae', '', '123', '', '', '', ''),
(12, 30, 'bravo.png', '', 'R', 'Meina', '', '123', '', '', '', ''),
(13, 31, '', '', 'Ricafort', 'Judd Robert', '', '0912345678', '', '', '', ''),
(14, 36, '', 'proglan lecture.pdf', 'User', 'User1', '', '123', '', '', '', ''),
(15, 38, '', '', 'User', 'user3', '', '123', '', '', '', ''),
(16, 39, '', '', 'User', 'User4', '', '123', '', '', '', ''),
(17, 42, '', '', '123', 'SIsig', '', '123', '', '', '', ''),
(18, 48, '', '', 'Torres', 'JayR', '', '0912345678', '', '', '', ''),
(19, 49, 'download (2).jfif', 'test (2).pdf', 'Tabal', 'Jan', '2025-04-16', '0912-3456-78', 'joshricafort30@gmail.com', 'Anywhere St.', 'Any City', ''),
(20, 50, 'download (3).jfif', 'test (2) (1).pdf', 'Ria', 'Rato', '2025-04-16', '123', 'rj.ricafort21@gmail.com', 'Anywhere St.', 'Any City', ''),
(21, 51, 'download (3).jfif', 'test (2) (1) (2).pdf', 'Viaje', 'Luna', '2025-04-17', '123-456-789', 'viajecristinejoy@gmail.com', 'Anywhere St.', 'Any City', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `tblapplications`
--
ALTER TABLE `tblapplications`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `tblcompany`
--
ALTER TABLE `tblcompany`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `tbljobs`
--
ALTER TABLE `tbljobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `tblprofiles`
--
ALTER TABLE `tblprofiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  MODIFY `account_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tblapplications`
--
ALTER TABLE `tblapplications`
  MODIFY `application_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tblcompany`
--
ALTER TABLE `tblcompany`
  MODIFY `company_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tbljobs`
--
ALTER TABLE `tbljobs`
  MODIFY `job_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblprofiles`
--
ALTER TABLE `tblprofiles`
  MODIFY `profile_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcompany`
--
ALTER TABLE `tblcompany`
  ADD CONSTRAINT `accountid` FOREIGN KEY (`account_id`) REFERENCES `tblaccounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblprofiles`
--
ALTER TABLE `tblprofiles`
  ADD CONSTRAINT `account_id` FOREIGN KEY (`account_id`) REFERENCES `tblaccounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
