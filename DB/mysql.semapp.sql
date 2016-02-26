-- SQL Dump
-- Erstellungszeit: 09. Feb 2016 um 18:29
-- Server Version: 5.6.20
-- PHP-Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `semapp`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `collection`
--

CREATE TABLE IF NOT EXISTS `collection` (
  `id` varchar(50) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `state_id` bigint(20) DEFAULT NULL,
  `title` text,
  `title_short` varchar(100) NOT NULL,
  `location_id` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_state_change` datetime DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes_to_studies` text,
  `bib_id` int(10) unsigned DEFAULT '1',
  `sortorder` varchar(1000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `document`
--

CREATE TABLE IF NOT EXISTS `document` (
`id` bigint(20) unsigned NOT NULL,
  `doc_type_id` bigint(20) DEFAULT NULL,
  `physicaldesc` varchar(100) NOT NULL,
  `collection_id` varchar(20) DEFAULT NULL,
  `state_id` bigint(20) DEFAULT NULL,
  `location_id` int(10) unsigned NOT NULL DEFAULT '1',
  `title` text,
  `author` text,
  `edition` text,
  `year` text,
  `journal` text,
  `volume` text,
  `pages` text,
  `publisher` text,
  `signature` varchar(50) DEFAULT NULL,
  `ppn` text,
  `url` text,
  `url_type_id` bigint(20) DEFAULT NULL,
  `relevance` int(11) DEFAULT NULL,
  `notes_to_studies` text,
  `notes_to_staff` text,
  `protected` tinyint(1) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_state_change` datetime DEFAULT NULL,
  `shelf_remain` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `doc_type`
--

CREATE TABLE IF NOT EXISTS `doc_type` (
`id` bigint(20) unsigned NOT NULL,
  `item` text,
  `description` text,
  `for_loan` tinyint(1) DEFAULT NULL,
  `doc_type_id` bigint(20) unsigned NOT NULL,
  `doc_type` varchar(50) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `doc_type`
--

INSERT INTO `doc_type` (`id`, `item`, `description`, `for_loan`, `doc_type_id`, `doc_type`) VALUES
(1, 'book', 'Buch SA', 1, 1, 'print'),
(4, 'ebook', 'E-Book', 3, 4, 'electronic'),
(3, 'book', 'CD-ROM', 3, 3, 'electronic'),
(2, 'lh_book', 'Buch LH', 1, 0, 'print');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

CREATE TABLE IF NOT EXISTS `role` (
`id` bigint(20) unsigned NOT NULL,
  `name` text,
  `description` text,
  `setuid` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `setuid`) VALUES
(1, 'admin', 'Administrator', 1),
(2, 'staff', 'Bibliothekar', 0),
(3, 'edit', 'Dozent', 0),
(4, 'guest', 'Gast', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `state`
--

CREATE TABLE IF NOT EXISTS `state` (
`id` bigint(20) unsigned NOT NULL,
  `name` text,
  `description` text,
  `color` text
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `state`
--

INSERT INTO `state` (`id`, `name`, `description`, `color`) VALUES
(1, 'new', 'neu bestellt', 'purple'),
(2, 'open', 'wird<br/> bearbeitet', 'blue'),
(3, 'active', 'ist<br>aktiv', 'green'),
(4, 'obsolete', 'wird<br>entfernt', 'red'),
(5, 'inactive', 'ist <br>inaktiv', 'gray'),
(6, 'delete', 'IST<br/>GEL&Ouml;SCHT!', 'black'),
(9, 'suggest', 'Vor<br/>schlag', 'black');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `surname` text NOT NULL,
  `forename` text NOT NULL,
  `sex` enum('m','f') NOT NULL,
  `email` text NOT NULL,
  `state_id` bigint(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `last_state_change` datetime DEFAULT NULL,
  `bib_id` int(10) unsigned DEFAULT '1',
  `department` int(10) unsigned NOT NULL,
  `hawaccount` varchar(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD UNIQUE KEY `id_2` (`id`), ADD KEY `title_short` (`title_short`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_type`
--
ALTER TABLE `doc_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`hawaccount`), ADD UNIQUE KEY `id_5` (`id`), ADD KEY `id` (`id`), ADD KEY `id_2` (`id`), ADD KEY `id_3` (`id`), ADD KEY `id_4` (`id`), ADD FULLTEXT KEY `surname` (`surname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `doc_type`
--
ALTER TABLE `doc_type`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

