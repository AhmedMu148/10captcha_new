-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2026 at 10:37 AM
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
-- Table structure for table `custom_images`
--

CREATE TABLE `custom_images` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(512) NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `custom_images`
--

INSERT INTO `custom_images` (`id`, `code`, `name`, `description`, `type`, `status`) VALUES
(1, 'common-1', 'Common v.1', 'Common model (wide base of captcha types)', 1, 1),
(2, 'common-2', 'Common v.2', 'Common model (with additional types of captcha)', 1, 1),
(3, 'common-3', 'Common v.3', 'Common model (wide base of captcha types + case sensitive)', 2, 1),
(4, 'detran.ce.gov.br', 'Detran.ce.gov.br', 'Detran.ce.gov.br captcha (with spec.symbols)', 1, 1),
(5, 'facebook', 'Facebook', 'Facebook captcha', 1, 1),
(6, 'evisaforms', 'evisaforms', 'evisaforms.state.gov captcha', 1, 1),
(7, 'funcaptcha', 'FunCaptcha', 'FunCaptcha (returns number of image)', 3, 1),
(8, 'fssp.gov.ru', 'fssp.gov.ru', 'fssp.gov.ru captcha (4..7 symbols)', 1, 1),
(9, 'free-litecoin', 'Free-Litecoin', 'free-litecoin.com captcha, 6-8 chars', 1, 1),
(10, 'gbdr', 'GBDR', 'GBDR captcha', 1, 1),
(11, 'funcaptcha.compare', 'FunCaptcha.Compare', 'FunCaptcha (pick one from 5-6 images according the key image)', 3, 1),
(12, 'imdbux.com', 'imdbux.com', 'imdbux.com captcha (choose the vertically flipped image, one out', 1, 1),
(13, 'hotmail', 'Hotmail', 'Hotmail (Microsoft) captcha, 4-10 characters', 1, 1),
(14, 'collection-1-opt', 'Collection v.1\nOpt', 'Model for several types of captchas (improved accuracy)', 1, 1),
(15, 'collection-1', 'Collection v.1', 'Model for several types of captchas', 1, 1),
(16, 'collection-3', 'Collection v.3', 'Model for several types of captchas', 1, 1),
(17, 'collection-2', 'Collection v.2 cyrillic', 'Model for several types of captchas', 1, 1),
(18, 'joomla', 'Joomla', 'Joomla captcha (3..6 chars)', 1, 1),
(19, 'collection-5', 'Collection v.5', 'Model for several types of captchas', 1, 1),
(20, 'collection-4', 'Collection v.4', 'Model for several types of captchas', 1, 1),
(21, 'liteking', 'Liteking', 'liteking.io captcha', 1, 1),
(22, 'kad.arbitr.ru', 'kad.arbitr.ru', 'kad.arbitr.ru captcha (cyrillic)', 1, 1),
(23, 'nanogames', 'Nanogames', 'Nanogames.io captcha', 1, 1),
(24, 'service.nalog.ru', 'service.nalog.ru', 'service.nalog.ru captcha', 1, 1),
(25, 'odnoklassniki', 'Odnoklassniki.Ru (ru + en)', 'Odnoklassniki.Ru (ru + en) Captcha', 1, 1),
(26, 'bitcoinfaucet', 'BitcoinFaucet', 'Model for bitcoinfaucet.network captcha', 1, 1),
(27, 'pennyearner', 'PennyEarner', 'PennyEarner.com captcha (7 chars, case-sensitive)', 1, 1),
(28, 'omg_gif3', 'OMG_GIF3', 'OMG or similar animated yellow-black gifs', 1, 1),
(29, 'rambler_cyrillic', 'Rambler cyrillic', 'Rambler captcha, 4-6 characters', 1, 1),
(30, 'publisherv2signin', 'PublisherV2Signin', 'PublisherV2Signin (5 chars, case-sensitive)', 1, 1),
(31, 'playserver', 'Playserver', 'playserver.in.th captcha', 1, 1),
(32, 'solvemedia', 'SolveMedia', 'SolveMedia captcha', 1, 1),
(33, 'seo-sast.ru', 'Seo-Fast.Ru', 'seo-fast.ru captcha', 1, 1),
(34, 'sud_es.pfrf.ru', 'Sud Captcha + es.pfrf.ru', 'SUD captcha + es.pfrf.ru captcha', 1, 1),
(35, 'steam', 'Steam', 'Steam captcha (6 chars)', 1, 1),
(36, 'olx.com', 'olx.com', 'olx.com captcha', 1, 1),
(37, 'farpost', 'Farpost', 'farpost.ru captcha', 1, 1),
(38, 'csgo3.run', 'csgo3.run', 'csgo3.run captcha (4 chars, case-sensitive)', 1, 1),
(39, 'faucets', 'Faucets', 'Cryptowin.io captcha (arythmetic), turbofaucet.com and cryptowin.xyz', 1, 1),
(40, 'bradesco.com.br', 'bradesco.com.br', 'bradesco.com.br captcha', 1, 1),
(41, 'blacksprut', 'Blacksprut', 'Blacksprut captcha', 1, 1),
(42, 'bagi.co.in', 'bagi.co.in', 'bagi.co.in captcha (5-chars, case sensetive)', 1, 1),
(43, 'apple.com', 'Apple.com', 'Apple.com captcha', 1, 1),
(44, 'amazon', 'Amazon', 'Amazon captcha', 1, 1),
(45, '2krn.cc', '2krn.cc', '2krn.cc captcha (eng, cyr)', 1, 1),
(46, 'gos', 'Gos', 'Gos (cyrillic)', 1, 1),
(47, 'yandex', 'Yandex', 'Two words Yandex captcha, eng/ru', 1, 1),
(48, 'yandex-2', 'Yandex 200x60', 'Yandex sizetype 200x60', 1, 1),
(49, 'yandex-3', 'Yandex BIG', 'Large Yandex captcha 600x180', 1, 1),
(50, 'worldoftanks3', 'WorldOfTanks3', 'WOT captcha, 6-12 green chars', 1, 1),
(51, 'vk-un', 'VK Universal (cyr + eng)', 'VKontakte captcha universal (4-7 chars, EN+CYR)', 1, 1),
(52, 'vk-fast', 'VK Fast', 'VK 4-7 chars EN+CYR', 1, 1),
(53, 'trafhub', 'Trafhub', 'Traf-hub.ru captcha', 1, 1),
(54, 'extratodebito', 'Extrato debito', 'Extratodebito.detran.pr.gov.br captcha', 1, 1),
(55, 'gmx', 'gmx & elpts', 'gmx and portal.elpts.ru captcha', 1, 1),
(56, 'bitrix', 'Bitrix', 'Bitrix captcha', 1, 1),
(57, 'discuz', 'Discuz', 'Discuz captcha', 1, 1),
(58, 'ipb', 'IPB', 'IPB captcha', 1, 1),
(59, 'phpbb', 'phpBB', 'phpBB captcha', 1, 1),
(60, 'smf', 'SMF', 'SMF captcha', 1, 1),
(61, 'vbulletin', 'VBulletin', 'VBulletin captcha', 1, 1),
(62, '1gabba', '1gabba', '1gabba.net captcha , case sensitive', 1, 1),
(63, 'nfprompt', 'nfprompt', 'nfprompt.io, esale.ikd.ir captcha (case sensitive)', 1, 1),
(64, 'rambler_se', 'Rambler se', 'Rambler captcha, 4-6 characters', 1, 1),
(65, 'reestr', 'Reestr captcha', 'Reestr captcha', 1, 1),
(66, 'rt', 'B2C_RT', 'B2C.passport.rt.ru captcha (case sensitive)', 1, 1),
(67, 'whatsapp', 'Whatsapp', 'Whatsapp captcha ', 1, 1),
(68, 'xtremeTop100', 'XTremeTop100', 'XTremeTop100 captcha ', 1, 1),
(69, 'yaanimail', 'Yaanimail', 'Yaanimail captcha ', 1, 1),
(70, 'yandex-4', 'Yandex 2024', 'Yandex 2024 captcha ', 1, 1),
(71, 'yandex-5', 'Yandex 2025', 'Yandex 2025 captcha ', 1, 1),
(72, '1cupis', '1Cupis', '1Cupis captcha ', 1, 1),
(73, 'basetools', 'Basetools.sk', 'basetools.sk captcha ', 1, 1),
(74, 'bidencash', 'BidenCash', 'BidenCash captcha ', 1, 1),
(75, 'btk', 'BTK', 'internet2.btk.gov.tr/sitesorgu/ captcha (case-sensitive)', 1, 1),
(76, 'caphub', 'CaptHub', 'capthap.online service captcha', 1, 1),
(77, 'cr', 'CR', 'CR-bot captcha', 1, 1),
(78, 'eais', 'EAIS', 'eais.rkn.gov.ru captcha', 1, 1),
(79, 'gos2', 'GOS-2', 'GOS captcha', 1, 1),
(80, 'rdv', 'RDV', 'rdv-prefecture.interieur.gouv.fr captcha', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `custom_images`
--
ALTER TABLE `custom_images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `custom_images`
--
ALTER TABLE `custom_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
