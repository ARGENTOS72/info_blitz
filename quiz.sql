-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 12, 2025 alle 19:45
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
-- Struttura della tabella `classe`
--

CREATE TABLE `classe` (
  `classe` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `classe`
--

INSERT INTO `classe` (`classe`) VALUES
('1A');

-- --------------------------------------------------------

--
-- Struttura della tabella `correzione`
--

CREATE TABLE `correzione` (
  `id` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `id_test` int(11) NOT NULL,
  `id_studente` int(11) NOT NULL,
  `correzione` text NOT NULL,
  `punteggio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `correzione`
--

INSERT INTO `correzione` (`id`, `id_domanda`, `id_test`, `id_studente`, `correzione`, `punteggio`) VALUES
(15, 1, 1, 34, 'No', 4),
(16, 2, 1, 34, 'Perché si', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda`
--

CREATE TABLE `domanda` (
  `id` int(11) NOT NULL,
  `id_test` int(11) NOT NULL,
  `tipo` enum('multipla','aperta') NOT NULL,
  `testo_domanda` text NOT NULL,
  `punteggio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `domanda`
--

INSERT INTO `domanda` (`id`, `id_test`, `tipo`, `testo_domanda`, `punteggio`) VALUES
(1, 1, 'aperta', 'Does skibidi toilet lives under your house?', 10),
(2, 1, 'multipla', 'Chi è il più sigma tra questi?', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `domanda_multipla`
--

CREATE TABLE `domanda_multipla` (
  `id` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `testo_opzione` varchar(255) NOT NULL,
  `corretta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `domanda_multipla`
--

INSERT INTO `domanda_multipla` (`id`, `id_domanda`, `testo_opzione`, `corretta`) VALUES
(1, 2, 'Gabriele', 0),
(2, 2, 'Giacomini', 1),
(3, 2, 'Cameramen', 0),
(4, 2, 'Fortnite', 1),
(5, 2, 'Festivale', 0),
(6, 2, 'Arongi', 0),
(7, 2, 'Yarararararagi', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `risposta`
--

CREATE TABLE `risposta` (
  `id` int(11) NOT NULL,
  `id_domanda` int(11) NOT NULL,
  `id_studente` int(11) NOT NULL,
  `id_sessione_test` int(11) NOT NULL,
  `risposta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `risposta`
--

INSERT INTO `risposta` (`id`, `id_domanda`, `id_studente`, `id_sessione_test`, `risposta`) VALUES
(48, 1, 34, 1, 'asdvf'),
(49, 2, 34, 1, '1,3');

-- --------------------------------------------------------

--
-- Struttura della tabella `sessione_test`
--

CREATE TABLE `sessione_test` (
  `id` int(11) NOT NULL,
  `id_test` int(11) NOT NULL,
  `classe` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sessione_test`
--

INSERT INTO `sessione_test` (`id`, `id_test`, `classe`) VALUES
(1, 1, '1A'),
(2, 2, '1A');

-- --------------------------------------------------------

--
-- Struttura della tabella `studente`
--

CREATE TABLE `studente` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `classe` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `studente`
--

INSERT INTO `studente` (`id`, `id_utente`, `classe`) VALUES
(3, 34, '1A'),
(4, 36, '1A');

-- --------------------------------------------------------

--
-- Struttura della tabella `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `titolo` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `test`
--

INSERT INTO `test` (`id`, `id_docente`, `titolo`, `descrizione`) VALUES
(1, 35, 'Test inglese', NULL),
(2, 35, 'Test italiano', 'Fortnite festivale è bellissimo provatelo por favor');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL DEFAULT 'password',
  `ruolo` enum('studente','docente','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `nome`, `cognome`, `login`, `password`, `ruolo`) VALUES
(1, 'skibidi', 'toilet', 'fornei', '$2y$10$NWNGWmTS3fd5kz74VXEdEerGU7qAhh9GEJlzy4dlSFVwzwxzecQ9W', 'admin'),
(34, 'Studente', 'Studente', 'Studente', '$2y$10$iDHmC0BFh.GRVE.xBhUvCuAz6w22SdylfdZjmIx..nj4U5.lA136a', 'studente'),
(35, 'Docente', 'Docente', 'Docente', '$2y$10$giDv/btYyMTR.wHfvcJKduk33E9T6u7HNi3rzSAVMEENsoTZkYWOC', 'docente'),
(36, 'Test', 'Test', 'Test', '$2y$10$F4mQ.n7GmcDAx6CnzAFBueO8hWiHhvd8Zo991eDu1ceJhuhny07YG', 'studente');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`classe`);

--
-- Indici per le tabelle `correzione`
--
ALTER TABLE `correzione`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `risposta`
--
ALTER TABLE `risposta`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `sessione_test`
--
ALTER TABLE `sessione_test`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `studente`
--
ALTER TABLE `studente`
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
-- AUTO_INCREMENT per la tabella `correzione`
--
ALTER TABLE `correzione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT per la tabella `domanda`
--
ALTER TABLE `domanda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `domanda_multipla`
--
ALTER TABLE `domanda_multipla`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `risposta`
--
ALTER TABLE `risposta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT per la tabella `sessione_test`
--
ALTER TABLE `sessione_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `studente`
--
ALTER TABLE `studente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
