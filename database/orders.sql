-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 22 dec 2023 om 10:13
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amr_webshop`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_nr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `order_nr`) VALUES
(51, 1, '2023-12-19 14:15:22', 1),
(52, 1, '2023-12-19 14:16:51', 2),
(53, 1, '2023-12-19 14:17:48', 3),
(54, 1, '2023-12-19 14:23:14', 4),
(55, 1, '2023-12-19 14:25:08', 5),
(56, 1, '2023-12-19 14:25:23', 6),
(57, 1, '2023-12-19 14:33:46', 7),
(58, 1, '2023-12-19 14:34:37', 8),
(59, 1, '2023-12-19 14:43:27', 9),
(60, 1, '2023-12-19 14:44:56', 10),
(61, 1, '2023-12-19 14:45:37', 11),
(62, 1, '2023-12-19 14:46:27', 12),
(63, 1, '2023-12-19 14:46:40', 13),
(64, 1, '2023-12-19 15:45:26', 14),
(65, 1, '2023-12-19 15:45:38', 15),
(66, 1, '2023-12-19 15:45:45', 16),
(67, 1, '2023-12-19 15:45:49', 17),
(68, 1, '2023-12-19 15:45:53', 18),
(69, 1, '2023-12-19 15:45:55', 19),
(70, 2, '2023-12-19 15:46:12', 20),
(71, 2, '2023-12-19 15:46:14', 21),
(72, 2, '2023-12-19 15:46:16', 22),
(73, 2, '2023-12-19 15:46:20', 23),
(74, 2, '2023-12-19 15:46:23', 24),
(75, 2, '2023-12-19 15:46:25', 25),
(76, 2, '2023-12-19 15:46:28', 26),
(77, 2, '2023-12-19 15:46:52', 27),
(78, 2, '2023-12-19 15:48:37', 28),
(79, 2, '2023-12-19 15:48:40', 29),
(80, 2, '2023-12-19 15:48:44', 30),
(81, 2, '2023-12-19 15:48:47', 31),
(82, 2, '2023-12-19 15:48:49', 32),
(83, 2, '2023-12-19 15:48:53', 33),
(84, 2, '2023-12-19 16:12:46', 34),
(85, 2, '2023-12-19 16:14:06', 35),
(86, 2, '2023-12-19 23:44:53', 36),
(87, 2, '2023-12-19 23:47:30', 37),
(88, 1, '2023-12-20 09:56:17', 38),
(89, 1, '2023-12-20 09:56:34', 39),
(90, 1, '2023-12-20 09:56:54', 40),
(91, 1, '2023-12-20 09:57:07', 41),
(92, 6, '2023-12-21 22:23:09', 42);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
