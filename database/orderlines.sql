-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 22 dec 2023 om 10:12
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
-- Tabelstructuur voor tabel `orderlines`
--

CREATE TABLE `orderlines` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_per_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `orderlines`
--

INSERT INTO `orderlines` (`id`, `order_id`, `product_id`, `quantity_per_product`) VALUES
(1, 51, 6, 1),
(2, 51, 1, 1),
(3, 52, 1, 1),
(4, 52, 3, 1),
(5, 53, 1, 3),
(6, 54, 1, 1),
(7, 55, 1, 1),
(8, 56, 1, 1),
(9, 57, 1, 1),
(10, 58, 1, 1),
(11, 59, 1, 1),
(12, 60, 1, 1),
(13, 61, 2, 2),
(14, 62, 3, 1),
(15, 63, 5, 1),
(16, 64, 12, 3),
(17, 65, 3, 1),
(18, 66, 6, 1),
(19, 67, 7, 1),
(20, 68, 9, 1),
(21, 69, 3, 1),
(22, 70, 12, 1),
(23, 71, 1, 1),
(24, 72, 2, 1),
(25, 73, 5, 1),
(26, 74, 5, 1),
(27, 75, 5, 1),
(28, 76, 5, 1),
(29, 77, 1, 1),
(30, 77, 3, 1),
(31, 77, 2, 1),
(32, 78, 2, 4),
(33, 79, 2, 1),
(34, 80, 2, 1),
(35, 81, 3, 1),
(36, 82, 5, 1),
(37, 83, 1, 1),
(38, 84, 1, 3),
(39, 84, 5, 2),
(40, 85, 1, 2),
(41, 86, 1, 1),
(42, 87, 1, 1),
(43, 88, 3, 1),
(44, 89, 5, 1),
(45, 90, 5, 1),
(46, 91, 5, 3),
(47, 92, 1, 2);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `orderlines`
--
ALTER TABLE `orderlines`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `orderlines`
--
ALTER TABLE `orderlines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
