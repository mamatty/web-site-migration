-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Set 07, 2018 alle 15:31
-- Versione del server: 10.1.34-MariaDB
-- Versione PHP: 7.0.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartgym`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `account`
--

INSERT INTO `account` (`id`, `first_name`, `last_name`, `email`, `password`, `hash`) VALUES
(4, 'Matteo', 'Novembre', 'matteo.novembre94@gmail.com', '$2y$10$wutP4.t5jaSf1xWySUIXsuY1PRjwhnpxMA0AVlzS/al0lGBGvsEVK', '7ef605fc8dba5425d6965fbd4c8fbe1f'),
(16, 'marco', 'angius', 'marco.angius@gmail.it', '$2y$10$tYzqwDb/VHRTqZGkHdmrgu1lmSFTyAiQcQyhQ8npNqsJp9RZBjJ22', '303ed4c69846ab36c2904d3ba8573050'),
(17, 'sergio', 'micalizzi', 'sergio.micalizzi@gmail.com', '$2y$10$eroxuw2oHCffRa8l73grhuyl/HNMIlktzZIH/tz/OhVPUFOoPGJ/a', '46922a0880a8f11f8f69cbb52b1396be'),
(18, 'Francesco', 'Ziparo', 'zipareddu@gmail.com', '$2y$10$A8esQpH/rRPfnNOpN3CMpO10Ozgu1DtReNgFfO2sawwtBM07bc8kq', 'b137fdd1f79d56c7edf3365fea7520f2'),
(19, 'Matteo', 'ciao', 'ciao@gmail.com', '$2y$10$r3xC/37J8E.GkNm1rXG/6ewMSk7Oe9h00JwNCS7tfKxjpkbbEqgg2', '8cb22bdd0b7ba1ab13d742e22eed8da2'),
(20, 'alessandro', 'prova', 'prova@prova.it', '$2y$10$q30l2I26RRhToZNXBK4Bte6fhbEu5ECoBm1m9f85PhdCRgRTYtBde', '0c74b7f78409a4022a2c4c5a5ca3ee19');

-- --------------------------------------------------------

--
-- Struttura della tabella `exercise`
--

CREATE TABLE `exercise` (
  `id_exercise` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `muscular_zone` text NOT NULL,
  `url` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `exercise`
--

INSERT INTO `exercise` (`id_exercise`, `name`, `description`, `muscular_zone`, `url`) VALUES
(1, 'distensioni con bilancere su panca', 'qualcosa', 'petto', 'prova'),
(2, 'distensioni con manubri su panca inclinata', 'qualcosa1', 'petto', ''),
(3, 'butterfly', 'qualcosa2', 'petto', 'prova'),
(4, 'french press', 'qualcosa3', 'petto', ''),
(5, 'tricipes machine', 'qualcosa4', 'tricipiti', ''),
(6, 'lat machine', 'qualcosa5', 'schiena', ''),
(7, 'curl bilancere', 'qualcosa6', 'bicipiti', ''),
(8, 'leg extension', 'qualcosa7', 'gambe', ''),
(9, 'panca hyperextension', 'qualcosa8', 'spalle', ''),
(10, 'shoulder press', 'qualcosa9', 'spalle', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `exercise_schedules`
--

CREATE TABLE `exercise_schedules` (
  `id_list` int(11) NOT NULL,
  `id_schedule` int(11) NOT NULL,
  `id_exercise` int(11) NOT NULL,
  `day` int(11) NOT NULL DEFAULT '1',
  `repetitions` int(11) NOT NULL,
  `weight` float NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `exercise_schedules`
--

INSERT INTO `exercise_schedules` (`id_list`, `id_schedule`, `id_exercise`, `day`, `repetitions`, `weight`, `details`) VALUES
(2, 2, 1, 3, 2, 15, 'qualcosa'),
(3, 6, 1, 1, 5, 18, 'frf'),
(4, 6, 2, 1, 11, 14, 'ffff'),
(5, 5, 5, 3, 11, 18, 'ddd'),
(8, 7, 10, 2, 2, 20, 'frf'),
(10, 7, 1, 2, 5, 14, 'frf'),
(11, 7, 1, 1, 11, 18, 'ddd'),
(12, 7, 4, 2, 5, 18, 'ge');

-- --------------------------------------------------------

--
-- Struttura della tabella `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) NOT NULL,
  `title` text NOT NULL,
  `body` text NOT NULL,
  `send_date` date NOT NULL,
  `destination` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `messages`
--

INSERT INTO `messages` (`id_message`, `title`, `body`, `send_date`, `destination`) VALUES
(1, 'r', 'rr', '2018-04-23', 'Multicast'),
(2, 'nol', 'nilnj', '2018-04-28', 'Multicast'),
(3, 'ciaoq', 'come va', '2018-05-10', 'Multicast'),
(4, '', '', '2018-05-10', 'All'),
(5, 'prova', 'cdeoicj', '2018-08-28', 'All'),
(6, 'aiuto', 'vjvjv', '2018-08-28', 'All'),
(7, 'vvvv', 'vvvv', '2018-08-28', 'All'),
(8, 'vai', 'forza', '2018-08-28', 'All'),
(9, 'prova', 'vrev', '2018-08-28', 'All'),
(10, 'prova', 'j,nh', '2018-08-28', 'All'),
(11, 'prova', 'j,nh', '2018-08-28', 'All'),
(12, 'kjb ', 'lkhj', '2018-08-28', 'All'),
(13, 'knhl', 'lkj', '2018-08-28', 'All'),
(14, 'ihouh', 'lkji', '2018-08-28', 'Multicast'),
(15, 'lknj', 'jn', '2018-08-28', 'Array'),
(16, 'aiuto', 'ewfw', '2018-08-28', 'topic'),
(17, 'gg', 'ggg', '2018-08-28', 'all'),
(18, 'vv', 'ff', '2018-08-28', 'topic');

-- --------------------------------------------------------

--
-- Struttura della tabella `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `schedules`
--

CREATE TABLE `schedules` (
  `id_schedule` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `details` varchar(250) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `num_days` int(11) DEFAULT '1',
  `objective` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `schedules`
--

INSERT INTO `schedules` (`id_schedule`, `id_user`, `name`, `details`, `start_date`, `end_date`, `num_days`, `objective`) VALUES
(5, 1, 'vdf', 'efv', '2018-05-05', '2018-05-05', 5, 'efv'),
(6, 5, 'prova', 'ddd', '2020-02-10', '2021-02-10', 3, 'qualcosa'),
(7, 2, 'prova', 'ge', '2020-02-10', '2020-03-10', 3, 'qualcosa');

-- --------------------------------------------------------

--
-- Struttura della tabella `subscription`
--

CREATE TABLE `subscription` (
  `id_subscription` int(11) NOT NULL,
  `name` text NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `subscription`
--

INSERT INTO `subscription` (`id_subscription`, `name`, `price`) VALUES
(1, 'daily', 4.99),
(2, 'weekly', 9.99),
(3, 'monthly', 29.99),
(4, 'quarterly', 79.99),
(5, 'annual', 299.99);

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `name` text NOT NULL,
  `surname` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `birth_date` text NOT NULL,
  `address` text NOT NULL,
  `phone` text,
  `image` text NOT NULL,
  `subscription` text NOT NULL,
  `end_subscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id_user`, `name`, `surname`, `email`, `password`, `birth_date`, `address`, `phone`, `image`, `subscription`, `end_subscription`) VALUES
(1, 'Matteo', 'Novembre', 'matteo.novembre94@gmail.com', '$2y$10$w/VhH2Fbx7fN5LicLLDdDeZ1mK5T5g4xZC3RAlbE/s0oucw6XqPTe', '1994-08-06', 'Via Quarnaro I, n.41', '0', '', '6', '0000-00-00'),
(2, 'Irene', 'Raimondi', 'ireraim@gmail.com', '$2y$10$.ahTbMa.wqeVje48mgYK1uJzwapMBXCSUxcb4qPuGo9NvUYm1fSRa', '1991-07-14', 'Via Monte Grappa 61', '0', '', '2', '0000-00-00'),
(3, 'Sergio', 'Micalizzi', 'sergio.micalizzi@gmail.com', '$2y$10$CTDkeY8TYUwybx5qJqGr9uvEwclgIg6Qd13xRZbu8/gBskgnims5G', '1992-01-01', 'via prova', '0', '', 'giornaliero', '0000-00-00'),
(4, 'Matteo ', 'Novembre', 'matteo.novembre944@gmail.com', '$2y$10$E1.8g0pCR5xxwy5ZTvf25e02HqVV6EbXYMBVRODP/HPiIOYAUePCK', '1994-08-06', 'Via Quarnaro I, n.41', '333333333333', '', 'vuh', '1994-08-06');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`id_exercise`);

--
-- Indici per le tabelle `exercise_schedules`
--
ALTER TABLE `exercise_schedules`
  ADD PRIMARY KEY (`id_list`);

--
-- Indici per le tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`);

--
-- Indici per le tabelle `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`);

--
-- Indici per le tabelle `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id_schedule`);

--
-- Indici per le tabelle `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id_subscription`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT per la tabella `exercise`
--
ALTER TABLE `exercise`
  MODIFY `id_exercise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `exercise_schedules`
--
ALTER TABLE `exercise_schedules`
  MODIFY `id_list` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id_schedule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id_subscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
