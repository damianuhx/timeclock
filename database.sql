-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 31. Dez 2019 um 17:13
-- Server-Version: 5.6.36
-- PHP-Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `usr_web233_7`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitgeber`
--

CREATE TABLE `arbeitgeber` (
  `arbeitgeber_id` int(11) NOT NULL,
  `arbeitgeber_name` varchar(100) NOT NULL,
  `samstagszulage` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `arbeitgeber`
--

INSERT INTO `arbeitgeber` (`arbeitgeber_id`, `arbeitgeber_name`, `samstagszulage`) VALUES
(1, 'Treasure Islands Trading AG', 1),
(2, 'CNP Entertainment AG', 0),
(3, 'CNP Entertainment AG', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitstage`
--

CREATE TABLE `arbeitstage` (
  `filialen_id` int(11) NOT NULL,
  `jahr` int(11) NOT NULL,
  `jan` int(11) NOT NULL DEFAULT '0',
  `feb` int(11) NOT NULL DEFAULT '0',
  `mar` int(11) NOT NULL DEFAULT '0',
  `apr` int(11) NOT NULL DEFAULT '0',
  `mai` int(11) NOT NULL DEFAULT '0',
  `jun` int(11) NOT NULL DEFAULT '0',
  `jul` int(11) NOT NULL DEFAULT '0',
  `aug` int(11) NOT NULL DEFAULT '0',
  `sep` int(11) NOT NULL DEFAULT '0',
  `okt` int(11) NOT NULL DEFAULT '0',
  `nov` int(11) NOT NULL DEFAULT '0',
  `dez` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `austritte`
--

CREATE TABLE `austritte` (
  `mitarbeiter_id` int(11) NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `austritte`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `eintritte`
--

CREATE TABLE `eintritte` (
  `mitarbeiter_id` int(11) NOT NULL,
  `datum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `eintritte`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feiertage`
--

CREATE TABLE `feiertage` (
  `feiertage_id` int(11) NOT NULL,
  `feiertage_name` varchar(255) NOT NULL,
  `feiertage_datum` date NOT NULL,
  `national` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `feiertage`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ferien`
--

CREATE TABLE `ferien` (
  `mitarbeiter_id` int(11) NOT NULL,
  `jahr` int(11) NOT NULL,
  `monat` int(11) NOT NULL,
  `ferientage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `ferien`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feriensaldo`
--

CREATE TABLE `feriensaldo` (
  `mitarbeiter_id` int(11) NOT NULL,
  `jahr` int(11) NOT NULL,
  `ferientage` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `feriensaldo`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `filialentage`
--

CREATE TABLE `filialentage` (
  `filialen_id` int(11) NOT NULL,
  `feiertage_id` int(11) NOT NULL,
  `feiertag` tinyint(4) NOT NULL,
  `anzeigen` tinyint(4) NOT NULL,
  `oeffnungsname_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `filialentage`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `korrekturen`
--

CREATE TABLE `korrekturen` (
  `korrekturen_id` int(11) NOT NULL,
  `mitarbeiter_id` int(11) NOT NULL,
  `filialen_id` int(11) NOT NULL,
  `korrektur_monat` int(11) NOT NULL,
  `korrektur_jahr` int(11) NOT NULL,
  `korrektur_datum` date NOT NULL,
  `korrektur_dauer` time NOT NULL,
  `korrektur_kommentar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `korrekturen`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mitarbeiter`
--

CREATE TABLE `mitarbeiter` (
  `mitarbeiter_id` int(11) NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fest` tinyint(1) NOT NULL,
  `filialen_id` int(11) NOT NULL,
  `anstellungsgrad` int(11) NOT NULL,
  `aktiv` tinyint(4) NOT NULL DEFAULT '1',
  `ferienimjahr` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `mitarbeiter`
--

-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `mitarbeiterx`
-- (Siehe unten für die tatsächliche Ansicht)
--
CREATE TABLE `mitarbeiterx` (
`mitarbeiter_id` int(11)
,`vorname` varchar(255)
,`nachname` varchar(255)
,`code` varchar(255)
,`fest` tinyint(1)
,`anstellungsgrad` int(11)
,`aktiv` tinyint(4)
);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `normaltage`
--

CREATE TABLE `normaltage` (
  `filialen_id` int(11) NOT NULL,
  `wochentag` int(11) NOT NULL,
  `oeffnungsname_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `normaltage`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oeffnungsname`
--

CREATE TABLE `oeffnungsname` (
  `oeffnungsname_id` int(11) NOT NULL,
  `oeffnungsname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `oeffnungsname`
--



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oeffnungstypen`
--

CREATE TABLE `oeffnungstypen` (
  `filialen_id` int(11) NOT NULL,
  `oeffnungsname_id` int(11) NOT NULL,
  `oeffnung` time NOT NULL,
  `schliessung` time NOT NULL,
  `morgenpausedauer` int(11) NOT NULL,
  `morgenpausestart` time NOT NULL,
  `morgenpauseende` time NOT NULL,
  `abendpausedauer` int(11) NOT NULL,
  `abendpausestart` time NOT NULL,
  `abendpauseende` time NOT NULL,
  `mittagdauer` int(11) NOT NULL,
  `mittagstart` time NOT NULL,
  `mittagende` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `oeffnungstypen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oeffnungszeiten`
--

CREATE TABLE `oeffnungszeiten` (
  `filialen_id` int(11) NOT NULL,
  `wochentag` varchar(2) NOT NULL,
  `oeffnung` time NOT NULL,
  `schliessung` time NOT NULL,
  `morgenpausedauer` int(11) NOT NULL,
  `morgenpausestart` time NOT NULL,
  `morgenpauseende` time NOT NULL,
  `abendpausedauer` int(11) NOT NULL,
  `abendpausestart` time NOT NULL,
  `abendpauseende` time NOT NULL,
  `mittagdauer` int(11) NOT NULL,
  `mittagstart` time NOT NULL,
  `mittagende` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `oeffnungszeiten`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spezialtage`
--

CREATE TABLE `spezialtage` (
  `filialen_id` int(11) NOT NULL,
  `spezial_datum` date NOT NULL,
  `spezial_name` varchar(255) NOT NULL,
  `oeffnungsname_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `spezialtage`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stundensaldo`
--

CREATE TABLE `stundensaldo` (
  `mitarbeiter_id` int(11) NOT NULL DEFAULT '0',
  `jahr` int(11) NOT NULL DEFAULT '0',
  `monat` int(11) NOT NULL DEFAULT '0',
  `stundensaldo` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `stundensaldo`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `werwo`
--

CREATE TABLE `werwo` (
  `mitarbeiter_id` int(11) NOT NULL,
  `filialen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `werwo`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zeitstempel`
--

CREATE TABLE `zeitstempel` (
  `zeitstempel_id` int(11) NOT NULL,
  `filialen_id` int(11) NOT NULL,
  `mitarbeiter_id` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `angemeldet` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `abgemeldet` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `korrektur` time NOT NULL,
  `kommentar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ip_login` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `ip_logout` varchar(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `zeitstempel`
--

-- --------------------------------------------------------

--
-- Struktur des Views `mitarbeiterx`
--
DROP TABLE IF EXISTS `mitarbeiterx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`web233`@`localhost` SQL SECURITY DEFINER VIEW `mitarbeiterx`  AS  (select `mitarbeiter`.`mitarbeiter_id` AS `mitarbeiter_id`,`mitarbeiter`.`vorname` AS `vorname`,`mitarbeiter`.`nachname` AS `nachname`,`mitarbeiter`.`code` AS `code`,`mitarbeiter`.`fest` AS `fest`,`mitarbeiter`.`anstellungsgrad` AS `anstellungsgrad`,`mitarbeiter`.`aktiv` AS `aktiv` from `mitarbeiter`) ;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `arbeitgeber`
--
ALTER TABLE `arbeitgeber`
  ADD KEY `arbeitgeber_id` (`arbeitgeber_id`);

--
-- Indizes für die Tabelle `arbeitstage`
--
ALTER TABLE `arbeitstage`
  ADD PRIMARY KEY (`filialen_id`,`jahr`);

--
-- Indizes für die Tabelle `austritte`
--
ALTER TABLE `austritte`
  ADD PRIMARY KEY (`mitarbeiter_id`,`datum`);

--
-- Indizes für die Tabelle `eintritte`
--
ALTER TABLE `eintritte`
  ADD PRIMARY KEY (`mitarbeiter_id`,`datum`);

--
-- Indizes für die Tabelle `feiertage`
--
ALTER TABLE `feiertage`
  ADD PRIMARY KEY (`feiertage_id`);

--
-- Indizes für die Tabelle `ferien`
--
ALTER TABLE `ferien`
  ADD PRIMARY KEY (`mitarbeiter_id`,`jahr`,`monat`);

--
-- Indizes für die Tabelle `feriensaldo`
--
ALTER TABLE `feriensaldo`
  ADD PRIMARY KEY (`mitarbeiter_id`,`jahr`);

--
-- Indizes für die Tabelle `filialen`
--
ALTER TABLE `filialen`
  ADD PRIMARY KEY (`filialen_id`);

--
-- Indizes für die Tabelle `filialentage`
--
ALTER TABLE `filialentage`
  ADD PRIMARY KEY (`filialen_id`,`feiertage_id`);

--
-- Indizes für die Tabelle `korrekturen`
--
ALTER TABLE `korrekturen`
  ADD PRIMARY KEY (`korrekturen_id`);

--
-- Indizes für die Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  ADD PRIMARY KEY (`mitarbeiter_id`),
  ADD UNIQUE KEY `mitarbeiter_id` (`mitarbeiter_id`),
  ADD KEY `filialen_id` (`filialen_id`);

--
-- Indizes für die Tabelle `normaltage`
--
ALTER TABLE `normaltage`
  ADD PRIMARY KEY (`filialen_id`,`wochentag`);

--
-- Indizes für die Tabelle `oeffnungsname`
--
ALTER TABLE `oeffnungsname`
  ADD PRIMARY KEY (`oeffnungsname_id`);

--
-- Indizes für die Tabelle `oeffnungstypen`
--
ALTER TABLE `oeffnungstypen`
  ADD PRIMARY KEY (`filialen_id`,`oeffnungsname_id`);

--
-- Indizes für die Tabelle `oeffnungszeiten`
--
ALTER TABLE `oeffnungszeiten`
  ADD PRIMARY KEY (`filialen_id`,`wochentag`);

--
-- Indizes für die Tabelle `spezialtage`
--
ALTER TABLE `spezialtage`
  ADD PRIMARY KEY (`filialen_id`,`spezial_datum`);

--
-- Indizes für die Tabelle `stundensaldo`
--
ALTER TABLE `stundensaldo`
  ADD PRIMARY KEY (`mitarbeiter_id`,`jahr`,`monat`);

--
-- Indizes für die Tabelle `werwo`
--
ALTER TABLE `werwo`
  ADD PRIMARY KEY (`mitarbeiter_id`,`filialen_id`);

--
-- Indizes für die Tabelle `zeitstempel`
--
ALTER TABLE `zeitstempel`
  ADD PRIMARY KEY (`zeitstempel_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `arbeitgeber`
--
ALTER TABLE `arbeitgeber`
  MODIFY `arbeitgeber_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `feiertage`
--
ALTER TABLE `feiertage`
  MODIFY `feiertage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT für Tabelle `filialen`
--
ALTER TABLE `filialen`
  MODIFY `filialen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000000;

--
-- AUTO_INCREMENT für Tabelle `korrekturen`
--
ALTER TABLE `korrekturen`
  MODIFY `korrekturen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=688;

--
-- AUTO_INCREMENT für Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  MODIFY `mitarbeiter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1216;

--
-- AUTO_INCREMENT für Tabelle `oeffnungsname`
--
ALTER TABLE `oeffnungsname`
  MODIFY `oeffnungsname_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `zeitstempel`
--
ALTER TABLE `zeitstempel`
  MODIFY `zeitstempel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230698;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `mitarbeiter`
--
ALTER TABLE `mitarbeiter`
  ADD CONSTRAINT `mitarbeiter_ibfk_1` FOREIGN KEY (`filialen_id`) REFERENCES `filialen` (`filialen_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
