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
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` longtext NOT NULL,
  `price` float NOT NULL,
  `file_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `file_name`) VALUES
(1, 'Keychron K2 Wireless Mechanical Keyboard', 'Keychron K2 Wireless mechanical keyboard has included keycaps for both Windows and Mac operating systems.', 79.99, 'keyboard.png'),
(2, 'Keychron M3 Wireless Mouse', 'Keychron M3 Wireless Mouse has the best Pixart 3395 sensor. It supports 2.4 GHz, Bluetooth, and USB wired connection.', 49.99, 'mouse.png'),
(3, 'APPLE MacBook Pro 14 (2023)', 'The Apple M3 Pro chip has a 12-core CPU and an 18-core GPU that uses ray tracing with hardware acceleration. This allows you to do demanding things like edit gigantic panoramic photos or compile millions of lines of code with ease. Go all day long with Apple\'s energy-efficient chip. MacBook Pro is always exceptionally fast, whether it runs on mains or battery power. The super-powerful MacBook Pro gives you up to 18 hours of battery life.', 2549.99, 'macbook.png'),
(4, 'SAMSUNG Smart Monitor', 'Whether you\'re working, gaming or watching your favorite content: the Samsung Smart* Monitor M7 LS32BM700UPXEN Black opens up a world of entertainment for you. With its various smart functions, this 32-inch smart monitor is designed to increase your productivity and relaxation.', 299.99, 'monitor.png'),
(5, 'APPLE iPad Pro 12.9\"', 'The versatility of iPad. The groundbreaking achievements of M2. Discover the iPad Pro 12.9 WiFi Space Gray with 128 GB storage.', 1339.99, 'ipad.png'),
(6, 'APPLE iMac 24-inch blue (M3)', 'iMac. The best all-in-one computer in the world. Now with the super power of the M3 chip. A beautiful 24-inch display. A quirky design. And the best camera, microphones and speakers. WithiMac you have everything you need for work and leisure.', 2079.99, 'imac.png'),
(7, 'APPLE iPhone 15 Pro Max', 'iPhone 15 Pro Max. Forged from titanium and equipped with the groundbreaking A17 Pro chip, a customizable action button and the most powerful iPhone camera system ever.', 1479.99, 'iphone.png'),
(8, 'SONY WH-1000XM4', 'The black Sony WH-100XM4 are advanced noise-canceling headphones with smart functions and a long battery life of 30 hours.', 199.99, 'headphone.png'),
(9, 'APPLE AirPods Max - Sky Blue', 'With the Apple AirPods Max you experience music with high-fidelity audio and comfort. The Apple AirPods Max are completely wireless over-ear headphones. Enjoy your favorite music with surround sound, listening to deep bass and full tones.', 579.99, 'airpods.png'),
(10, 'SAMSUNG Galaxy S23 Ultra', 'With the Samsung Galaxy S23 Ultra (5G) Black, the brand of the same name is pushing new boundaries within the Galaxy S series. Thanks to its premium, durable design, versatile camera system with a focus on Nightography and Astrography, and user-friendly and customizable operating system, you have a device that fully meets the requirements of a high-end smartphone. The integrated S Pen is the finishing touch to make operating your Galaxy S smartphone even easier.', 1349.99, 's23ultra.png'),
(11, 'SONY PlayStation 4 (Slim) 500 GB', 'The Sony PlayStation 4 (Slim), the same quality and options you are used to, but in a smaller version. Not only is this PS4 a lot slimmer and weighs less, but it also consumes less power. Despite its small size, this updated PS4 is still powerful enough to play all your games and even supports HDR for even more vivid images on your television. The PlayStation 4 (Slim) comes with a 500 GB hard drive and the updated Dualshock 4 controller that now features a light bar at the top of the touchpad.', 299.99, 'ps4.png'),
(12, 'SONY PlayStation 5 Console Slim - Disk Edition', 'The PlayStation 5 console Slim unleashes new gaming possibilities you never imagined. Charging is faster than ever thanks to an ultra-fast SSD. A whole new generation of impressive PlayStation games is waiting for you, which you can dive even deeper into thanks to support for haptic feedback, adaptive triggers and 3D audio. With this console, players get powerful gaming technology, integrated into a sleek and compact design. Get your favorite games ready and start playing immediately thanks to the built-in 1TB SSD storage. Enjoy smooth, high framerate gameplay at up to 120 fps for compatible games, with support for 120 Hz on 4K displays. Use immersive adaptive triggers with dynamic resistance levels that simulate the physical impact of in-game activities in select PS5 games.', 549.99, 'ps5.png'),
(23, 'APPLE Watch Ultra 2', 'Apple Watch Ultra 2. New adventure. The most rugged and versatile Apple Watch. Designed for outdoor activities and the most extreme workouts, with a lightweight titanium case, extra-long battery life and our brightest display ever. With the \'Double Tap\' gesture, a magical new way to use your Apple Watch. And with \'Precise Search\', a handy feature to find your iPhone.', 899.99, 'applewatch.png'),
(28, 'SAMSUNG Galaxy Watch5 Pro', 'With the Samsung Galaxy Watch5 Pro (45 mm) Black, you can follow a route to even better sports performance and a healthier lifestyle. This scratch- and water-resistant smartwatch has improved route functions and a battery that lasts even longer in use. In addition, there are familiar ones, such as the 3-in-1 Samsung BioActive Sensor, Wear OS and sleep monitoring.', 299.99, 'galaxywatch.png');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
