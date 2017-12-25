-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 25. Dez 2017 um 10:44
-- Server-Version: 10.1.28-MariaDB
-- PHP-Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `modernjukeboxhost`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(255) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `roomName` varchar(255) NOT NULL,
  `playlistID` varchar(255) NOT NULL,
  `accessToken` varchar(255) NOT NULL,
  `refreshToken` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `email`, `pw`, `roomName`, `playlistID`, `accessToken`, `refreshToken`) VALUES
(1, 'test@test.de', '$2y$10$DCnlBzLebT17kzkEDm291OcwaEU2RidFYeqvpfz.5g9clKtBD44IG', 'schweinske-dehnhaide', '', 'BQD2QW5GhkSZowCl5E60R7JAuUNNFVUgDa0RMjLflVy82KA7k_r82DCw_luWF0OXKi2Wg0S9dJt28DN2w-GHm8oZiAC2lKYbe-JWMGigG2w7YPXW8i76wm5mslAn1GPfSJGLjqR0Q-sEfS6aAC7PTubQn4UhCZ7-3xLWZXLJjpFMXxnkGVwJ9tT-6W5bzsIiZ1ZhcYPLJtnpB6sx3fAVawdPhuZt6keC2ZYTMi0', 'AQDpgu24xhM2Q82bncfzmkPwoK_IrBtN3HehrRRfpoludOvBXOcsl370fUW8b6q87q8f2_52-QTa_Rv2lRVXuMacId8A41_DlvrKzNGJ9_JSyki862-yJAJqSNX7ICtEWhA'),
(31, 'test@test.de', '$2y$10$DCnlBzLebT17kzkEDm291OcwaEU2RidFYeqvpfz.5g9clKtBD44IG', 'schweinske-st-georg', '', 'BQBktbICrmeuI1Ve8f1uHUYLvm5ZtYcae9ukMXGfT1EfxX7iBCI1iYeW2oUkQvMA-MAOnMVUGTO6bBm5JCZRu96VPJZeQqfpCsYiv4AkoeLmIHoTjUOjncgN4tbDmDcCy7O43HycYF2YAiz74L_5sKgq3KDX-s5oGbMlAyz6UUNtLFaFpQYe-kHrLMqBDnW8CgXpHCwdikdklQ9hjDFEjX-1aEyJPSGsHAgRyBLg8JlZgU27EMGVHaBPJbnfJc_', 'AQCNxQEapHFRtF_f_VaxDTyT-UbpSDEkQlryMqZUnmNHERVRdwkd5aeIzkAB8zqU_7Q3AtmKnCFJnaPcBLrQzY6_q-X6JvrmWp3OiDxnxM930xsB-0_NGe8i88y6KXFSmDg'),
(32, 'test@test.de', '$2y$10$DCnlBzLebT17kzkEDm291OcwaEU2RidFYeqvpfz.5g9clKtBD44IG', 'schweinske-hbf', '', 'BQBktbICrmeuI1Ve8f1uHUYLvm5ZtYcae9ukMXGfT1EfxX7iBCI1iYeW2oUkQvMA-MAOnMVUGTO6bBm5JCZRu96VPJZeQqfpCsYiv4AkoeLmIHoTjUOjncgN4tbDmDcCy7O43HycYF2YAiz74L_5sKgq3KDX-s5oGbMlAyz6UUNtLFaFpQYe-kHrLMqBDnW8CgXpHCwdikdklQ9hjDFEjX-1aEyJPSGsHAgRyBLg8JlZgU27EMGVHaBPJbnfJc_', 'AQCNxQEapHFRtF_f_VaxDTyT-UbpSDEkQlryMqZUnmNHERVRdwkd5aeIzkAB8zqU_7Q3AtmKnCFJnaPcBLrQzY6_q-X6JvrmWp3OiDxnxM930xsB-0_NGe8i88y6KXFSmDg');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
