-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 12:27 PM
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
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `ocr_cap_id` int(11) NOT NULL,
  `price` varchar(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `success` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `ocr_cap_id`, `price`, `img`, `success`, `speed`, `sort`, `status`) VALUES
(1, 'reCAPTCHA v2', 3, '0.2', 'https://10captcha.com/assets/img/re.png', 96, 40, 1, 1),
(2, 'reCAPTCHA v3', 5, '0.25', 'https://10captcha.com/assets/img/re.png', 100, 15, 2, 1),
(3, 'reCAPTCHA Enterprise', 6, '0.4', 'https://10captcha.com/assets/img/re.png', 90, 15, 3, 1),
(4, 'hCAPTCHA', 8, '0.4', 'https://capmonster.cloud/img/landing/hcaptcha.svg', 86, 24, 5, 0),
(5, 'Image Captcha', 1, '0.1', 'https://10captcha.com/assets/img/text.svg', 99, 1, 6, 1),
(6, 'FunCAPTCHA', 7, 'Contact Us', 'https://10captcha.com/assets/img/fun.svg', 0, 0, 7, 1),
(7, 'GeeTest', 0, 'Coming Soon', 'https://10captcha.com/assets/img/geetest.svg', 0, 0, 8, 1),
(8, 'reCAPTCHA Invisible', 4, '0.2', 'https://10captcha.com/assets/img/re.png', 95, 30, 4, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
