-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 11, 2024 alle 12:25
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda`
--

CREATE TABLE `domanda` (
  `id` int(11) NOT NULL,
  `id_test` int(11) NOT NULL,
  `scelta_multipla` int(11) DEFAULT NULL,
  `testo_domanda` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `domanda`
--

INSERT INTO `domanda` (`id`, `id_test`, `scelta_multipla`, `testo_domanda`) VALUES
(1, 1, NULL, 'Does skibidi toilet lives under your house?'),
(2, 1, 2, 'Chi è il più sigma tra questi?');

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda_multipla`
--

CREATE TABLE `domanda_multipla` (
  `id` int(11) NOT NULL,
  `id_opzione_esatta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `domanda_multipla`
--

INSERT INTO `domanda_multipla` (`id`, `id_opzione_esatta`) VALUES
(2, 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `opzioni_domanda_multipla`
--

CREATE TABLE `opzioni_domanda_multipla` (
  `id` int(11) NOT NULL,
  `id_domanda_multipla` int(11) NOT NULL,
  `testo_opzione` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `opzioni_domanda_multipla`
--

INSERT INTO `opzioni_domanda_multipla` (`id`, `id_domanda_multipla`, `testo_opzione`) VALUES
(1, 2, 'Gabriele'),
(2, 2, 'Giacomini'),
(3, 2, 'Cameramen'),
(4, 2, 'Fortnite'),
(5, 2, 'Festivale'),
(6, 2, 'Arongi'),
(7, 2, 'Yarararararagi');

-- --------------------------------------------------------

--
-- Struttura della tabella `risposta`
--

CREATE TABLE `risposta` (
  `id` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `risposta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `test`
--

INSERT INTO `test` (`id`, `titolo`, `descrizione`) VALUES
(1, 'Test inglese', NULL),
(2, 'Test italiano', 'Fortnite festivale è bellissimo provatelo por favor');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL DEFAULT 'password'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `nome`, `cognome`, `login`, `password`) VALUES
(1, 'sebastiano', 'tiveron', 'tiveron.st.sebastiano@maxplanck.edu.it', 'password'),
(2, 'fabio', 'biscaro', 'fabio', 'password'),
(5, 'Tomas Matteo', 'Maceira Maurino', 'ARGENTOS72', 'SkibidiPassword'),
(8, 'dasdasdasda', '', '', ''),
(9, 'asdasda', 'dasdas', 'sadas', 'dasd');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `domanda`
--
ALTER TABLE `domanda`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `domanda_multipla`
--
ALTER TABLE `domanda_multipla`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `opzioni_domanda_multipla`
--
ALTER TABLE `opzioni_domanda_multipla`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `risposta`
--
ALTER TABLE `risposta`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `index_login_utente` (`login`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `domanda`
--
ALTER TABLE `domanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `domanda_multipla`
--
ALTER TABLE `domanda_multipla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `opzioni_domanda_multipla`
--
ALTER TABLE `opzioni_domanda_multipla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `risposta`
--
ALTER TABLE `risposta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
