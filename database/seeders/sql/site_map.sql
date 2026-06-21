-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2026 at 01:14 PM
-- Server version: 10.5.29-MariaDB-deb11-log
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hbeqsretfu`
--

-- --------------------------------------------------------

--
-- Table structure for table `site_map`
--

CREATE TABLE `site_map` (
  `id` int(11) NOT NULL,
  `url` varchar(512) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `site_map`
--

INSERT INTO `site_map` (`id`, `url`, `status`) VALUES
(1, 'https://10captcha.com/', 1),
(2, 'https://10captcha.com/faq', 1),
(3, 'https://10captcha.com/login', 1),
(4, 'https://10captcha.com/register', 1),
(5, 'https://10captcha.com/tickets', 1),
(6, 'https://10captcha.com/api', 1),
(7, 'https://10captcha.com/dashboard', 1),
(8, 'https://10captcha.com/payments', 1),
(9, 'https://10captcha.com/profile', 1),
(10, 'https://10captcha.com/reports', 1),
(11, 'https://10captcha.com/topup', 1),
(12, 'https://10captcha.com/lp/10cai-compare', 1),
(13, 'https://10captcha.com/lp/10cai-free', 1),
(14, 'https://help.10captcha.com/', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `site_map`
--
ALTER TABLE `site_map`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `site_map`
--
ALTER TABLE `site_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
