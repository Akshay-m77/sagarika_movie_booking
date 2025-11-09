-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2025 at 04:54 AM
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
-- Database: `sagarika`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `unit` varchar(60) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `Auto` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `NAME`, `unit`, `PASSWORD`, `Auto`) VALUES
(28, 'admin', 'admin', '$2y$10$ZGSbLiYdalntMzhBqDfh6esx8X8OXQ.OoGUNLTz2NKrIUKnAMaQaq', ''),
(61, 'Venduruthy', 'Venduruthy', '$2y$10$6/CzB3vmBSORgbTfG43r7.30WOx1y4.mmwvLpHkG9z9YQetOBAIw2', ''),
(62, 'unit', 'unit', '$2y$10$7cCIHU2ruCOZowCdKePSzujE4QtwgQOlXcu5mlMF9fl5o9gA6nJv2', '');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `B_ID` int(11) NOT NULL,
  `U_ID` int(11) NOT NULL,
  `BOOKING_DATE` datetime NOT NULL DEFAULT current_timestamp(),
  `SEAT_NO` varchar(20) NOT NULL,
  `BOOKING_ID` varchar(30) NOT NULL,
  `S_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `F_ID` int(11) NOT NULL,
  `U_ID` int(11) NOT NULL,
  `SERVICES` varchar(50) NOT NULL,
  `RATING` int(11) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `DATE` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `M_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `IMAGE` varchar(255) NOT NULL,
  `STARRING` varchar(50) NOT NULL,
  `RATING` varchar(20) NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `TRAILER` varchar(255) DEFAULT NULL,
  `STATUS` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `S_ID` int(11) NOT NULL,
  `M_ID` int(11) NOT NULL,
  `SHOW_TIME` time NOT NULL,
  `DATE` date NOT NULL,
  `STATUS` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `SERVICES` varchar(50) NOT NULL,
  `S_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`SERVICES`, `S_email`) VALUES
('Galley/ Dining Hall', NULL),
('INCS', NULL),
('MES - Common Areas', NULL),
('MI room', NULL),
('MT pool', NULL),
('NORA', NULL),
('Sagarika', NULL),
('Sailors Institute', NULL),
('Shopping Complex - Bakery', NULL),
('Shopping Complex - Fruits and Vegetables', NULL),
('Shopping Complex - Juice Shop', NULL),
('Shopping Complex - Mobile Store', NULL),
('Shopping Complex - Other Shops', NULL),
('Shopping Complex - Provision Store', NULL),
('Shopping Complex - Shoe Mart', NULL),
('Shopping Complex - Tailor Shop', NULL),
('Shopping Complex - Textiles', NULL),
('Shopping Complex - Watch Repair', NULL),
('SMA', NULL),
('SNC (O) Mess', NULL),
('Sports Facilities', NULL),
('SSA', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `U_ID` int(11) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `UNIT` varchar(50) NOT NULL,
  `RANK` varchar(255) NOT NULL,
  `MOB_NO` varchar(20) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `STATUS` int(10) NOT NULL,
  `AUTHKEY` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`U_ID`, `NAME`, `UNIT`, `RANK`, `MOB_NO`, `PASSWORD`, `STATUS`, `AUTHKEY`) VALUES
(127, 'test 2', 'unit', 'Officer & equivalent', '9998887776', '$2y$10$uEl02QerDFzGEaRvV38AfuUxpgf8.uODTh0mH1nYgmcP90uCw.Ts2', 1, 'd5bd45f73d79903a17757982c6edfa3d'),
(128, 'aaa', 'unit', 'Officer & equivalent', '9000000000', '$2y$10$8UhUygMGEEJ3wqveH42.DeC5PwI3aqpJV.nkGlSiqCpyNllGs77P.', 1, NULL),
(129, 'mm', 'unit', 'Senior sailors & equivalent', '9876543210', '$2y$10$SsSTttEZH/1xKLjslf2sGuoqbepDnkn2/n9RBFwxNfcnP8ihxCeZ2', 1, '1c37ce21392395d48c102774f383b768');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `unit` (`unit`),
  ADD UNIQUE KEY `NAME` (`NAME`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`B_ID`),
  ADD UNIQUE KEY `unique_seat_per_schedule` (`S_ID`,`SEAT_NO`),
  ADD KEY `U_ID` (`U_ID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`F_ID`),
  ADD KEY `U_ID` (`U_ID`),
  ADD KEY `SERVICES` (`SERVICES`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`M_ID`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`S_ID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`SERVICES`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`U_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `B_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `F_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `M_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `S_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `U_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`),
  ADD CONSTRAINT `fk_booking_schedules` FOREIGN KEY (`S_ID`) REFERENCES `schedules` (`S_ID`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `user` (`U_ID`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`SERVICES`) REFERENCES `services` (`SERVICES`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
