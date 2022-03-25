-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server Version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server Betriebssystem:        Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für webshop
CREATE DATABASE IF NOT EXISTS `webshop` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `webshop`;

-- Exportiere Struktur von Tabelle webshop.bankauszug_kreditkarte_infos
CREATE TABLE IF NOT EXISTS `bankauszug_kreditkarte_infos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_user` int(11) NOT NULL,
  `Kontonummer` int(11) NOT NULL,
  `BLZ` int(11) NOT NULL,
  `Kreditkartennummer` int(11) NOT NULL,
  `Gueltigkeit` int(11) NOT NULL,
  `Pruefnummer` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.bankauszug_kreditkarte_infos: ~0 rows (ungefähr)
DELETE FROM `bankauszug_kreditkarte_infos`;
/*!40000 ALTER TABLE `bankauszug_kreditkarte_infos` DISABLE KEYS */;
INSERT INTO `bankauszug_kreditkarte_infos` (`ID`, `ID_user`, `Kontonummer`, `BLZ`, `Kreditkartennummer`, `Gueltigkeit`, `Pruefnummer`) VALUES
	(19, 59, 12345678, 12345678, 12345678, 1012000, 123);
/*!40000 ALTER TABLE `bankauszug_kreditkarte_infos` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.bestellungen
CREATE TABLE IF NOT EXISTS `bestellungen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `bezahlt` tinyint(1) NOT NULL,
  `preis` double NOT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.bestellungen: ~2 rows (ungefähr)
DELETE FROM `bestellungen`;
/*!40000 ALTER TABLE `bestellungen` DISABLE KEYS */;
INSERT INTO `bestellungen` (`ID`, `user_ID`, `bezahlt`, `preis`, `stempel`) VALUES
	(70, 59, 1, 223, '2022-03-24 15:24:23'),
	(71, 59, 1, 500, '2022-03-24 15:25:11');
/*!40000 ALTER TABLE `bestellungen` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.bestellungen_produkte
CREATE TABLE IF NOT EXISTS `bestellungen_produkte` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_bestellung` int(11) NOT NULL,
  `ID_produkt` int(11) NOT NULL,
  `anzahl` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.bestellungen_produkte: ~5 rows (ungefähr)
DELETE FROM `bestellungen_produkte`;
/*!40000 ALTER TABLE `bestellungen_produkte` DISABLE KEYS */;
INSERT INTO `bestellungen_produkte` (`ID`, `ID_bestellung`, `ID_produkt`, `anzahl`) VALUES
	(50, 70, 104, 1),
	(51, 70, 111, 1),
	(52, 71, 116, 1),
	(53, 71, 131, 1);
/*!40000 ALTER TABLE `bestellungen_produkte` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.bewertungen
CREATE TABLE IF NOT EXISTS `bewertungen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `art` varchar(200) NOT NULL,
  `fische` int(11) NOT NULL,
  `kommentar` text NOT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.bewertungen: ~2 rows (ungefähr)
DELETE FROM `bewertungen`;
/*!40000 ALTER TABLE `bewertungen` DISABLE KEYS */;
INSERT INTO `bewertungen` (`ID`, `user_ID`, `art`, `fische`, `kommentar`, `stempel`) VALUES
	(88, 59, 'Allgemiene Shop Bewertung', 6, 'Prima Shop. Gefällt mir sehr gut!', '2022-03-24 13:00:10'),
	(89, 59, 'Taucherbrille', 5, 'Super Taucherbrille, macht mir sehr viel Spaß diese zu nutzen!', '2022-03-24 13:00:47');
/*!40000 ALTER TABLE `bewertungen` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.hersteller
CREATE TABLE IF NOT EXISTS `hersteller` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Firmenname` varchar(200) NOT NULL,
  `Webadresse` varchar(200) NOT NULL,
  `EMail` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.hersteller: ~3 rows (ungefähr)
DELETE FROM `hersteller`;
/*!40000 ALTER TABLE `hersteller` DISABLE KEYS */;
INSERT INTO `hersteller` (`ID`, `Firmenname`, `Webadresse`, `EMail`) VALUES
	(13, 'DieBestenProdukte', 'www.dieBestenProdukte.de', 'diebestenProdukte@gmail.com'),
	(14, 'tacuherfirma', 'www.tacherfirma.de', 'tacuerfirma@gmail.de'),
	(15, 'Firma Wasserdicht', 'www.wasserdicht.de', 'wasserdicht@gmail.com');
/*!40000 ALTER TABLE `hersteller` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.katikorien
CREATE TABLE IF NOT EXISTS `katikorien` (
  `ID` int(200) NOT NULL AUTO_INCREMENT,
  `produktname` varchar(200) NOT NULL,
  `kbeschreibung` text NOT NULL,
  `lbeschreibung` text NOT NULL,
  `imgsrc` varchar(200) NOT NULL,
  `hersteller_ID` int(200) NOT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.katikorien: ~5 rows (ungefähr)
DELETE FROM `katikorien`;
/*!40000 ALTER TABLE `katikorien` DISABLE KEYS */;
INSERT INTO `katikorien` (`ID`, `produktname`, `kbeschreibung`, `lbeschreibung`, `imgsrc`, `hersteller_ID`, `stempel`) VALUES
	(34, 'Taucherbrille', 'Prima Taucherbrille für den Urlaub oder auch Heimgewässer.', 'Egal ob Urlaub, Heimgewässer oder Planschbecken, diese Taucherbrille ist für alle Zwecke zu haben! Einfach nur empfehlenswert!', 'Taucherbrille.jpg', 13, '2022-03-24 11:44:17'),
	(35, 'Pressluftflasche', 'Pressluftflasche für jeden Einsatz!', 'Eine hochwertig verarbeitete Pressluftflasche mit Super Bewertungen! Perfekt für Deinen Einsatz!', 'Pressluftflasche.jpg', 14, '2022-03-24 11:46:59'),
	(36, 'Flossen', 'Prima Flossen für Spiel und Spaß!', 'Mit diesen Flossen sind perfekt für den Urlaub. Hochwertige Verarbeitung und Kundenzufriedenheit garantiert!', 'Flossen.jpg', 15, '2022-03-24 11:49:45'),
	(37, 'Neoprenanzug', 'Ein kälteresistenter Anzug.', 'Ein hochwertiger Anzug, der dich gegen Kälte und Verletzungen schützt. Kundenzufriedenheit garantiert.', 'Neoprenanzug.jpg', 13, '2022-03-24 11:59:22'),
	(38, 'Unterwasserkamera', 'Eine Unterwasserkamera, für die besten Momente.', 'Halte Deine Taucherlebnisse mit einer Kamera fest, die hochauflösende Bilder aufnimmt. ', 'Unterwasserkamera.jpg', 14, '2022-03-24 12:02:01');
/*!40000 ALTER TABLE `katikorien` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.kundenanliegen
CREATE TABLE IF NOT EXISTS `kundenanliegen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `text` text NOT NULL,
  `stempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.kundenanliegen: ~1 rows (ungefähr)
DELETE FROM `kundenanliegen`;
/*!40000 ALTER TABLE `kundenanliegen` DISABLE KEYS */;
INSERT INTO `kundenanliegen` (`ID`, `user_ID`, `text`, `stempel`) VALUES
	(6, 59, 'Hallo liebes Entwicklerteam, dies ist ein sehr guter Shop!LG', '2022-03-24 13:01:25');
/*!40000 ALTER TABLE `kundenanliegen` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.liefer_rechnungs_adresse
CREATE TABLE IF NOT EXISTS `liefer_rechnungs_adresse` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_user` int(11) NOT NULL,
  `Land` varchar(200) NOT NULL,
  `liefer_PLZ` int(11) NOT NULL,
  `liefer_Ort` varchar(200) NOT NULL,
  `liefer_Strasse` varchar(200) NOT NULL,
  `liefer_Hausnummer` varchar(200) NOT NULL,
  `rechnungs_PLZ` int(11) NOT NULL,
  `rechnungs_Ort` varchar(200) NOT NULL,
  `rechnungs_Strasse` varchar(200) NOT NULL,
  `rechnungs_Hausnummer` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.liefer_rechnungs_adresse: ~1 rows (ungefähr)
DELETE FROM `liefer_rechnungs_adresse`;
/*!40000 ALTER TABLE `liefer_rechnungs_adresse` DISABLE KEYS */;
INSERT INTO `liefer_rechnungs_adresse` (`ID`, `ID_user`, `Land`, `liefer_PLZ`, `liefer_Ort`, `liefer_Strasse`, `liefer_Hausnummer`, `rechnungs_PLZ`, `rechnungs_Ort`, `rechnungs_Strasse`, `rechnungs_Hausnummer`) VALUES
	(15, 59, 'Deutschland', 1234, 'Fulda', 'Staße', '1', 1234, 'Fulda', 'Staße', '1');
/*!40000 ALTER TABLE `liefer_rechnungs_adresse` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.produkte
CREATE TABLE IF NOT EXISTS `produkte` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `katikorien_ID` int(11) NOT NULL,
  `groesse` varchar(200) NOT NULL,
  `anzahl` int(11) NOT NULL,
  `preis` double NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.produkte: ~26 rows (ungefähr)
DELETE FROM `produkte`;
/*!40000 ALTER TABLE `produkte` DISABLE KEYS */;
INSERT INTO `produkte` (`ID`, `katikorien_ID`, `groesse`, `anzahl`, `preis`) VALUES
	(104, 34, 'xs', 23, 23),
	(105, 34, 's', 24, 23),
	(106, 34, 'm', 18, 24),
	(107, 34, 'l', 21, 25),
	(108, 34, 'l', 27, 25),
	(109, 34, 'xl', 32, 25),
	(110, 34, 'xxl', 18, 25),
	(111, 35, '7 Liter', 22, 200),
	(114, 35, '12 Liter', 42, 250),
	(115, 35, '15 Liter', 32, 300),
	(116, 36, 'xs -s', 5, 50),
	(117, 36, 's - m', 24, 50),
	(119, 36, 'm - l', 54, 50),
	(120, 36, 'l - xl', 23, 52),
	(121, 36, 'xl - xxl', 32, 52),
	(122, 37, 'xs', 15, 200),
	(123, 37, 's', 21, 210),
	(126, 37, 'm', 25, 220),
	(128, 37, 'l', 32, 230),
	(129, 37, 'xl', 23, 240),
	(130, 37, 'xxl', 16, 240),
	(131, 38, '16 GB Speicher', 13, 450),
	(132, 38, '32 GB Speicher', 10, 500),
	(133, 38, '64  GB Speicher', 9, 550),
	(134, 38, '128 GB Speicher', 4, 600),
	(135, 38, '265 GB Speicher', 3, 650);
/*!40000 ALTER TABLE `produkte` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.rollen
CREATE TABLE IF NOT EXISTS `rollen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_ID` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `admin_anfrage` tinyint(1) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.rollen: ~2 rows (ungefähr)
DELETE FROM `rollen`;
/*!40000 ALTER TABLE `rollen` DISABLE KEYS */;
INSERT INTO `rollen` (`ID`, `user_ID`, `level`, `admin_anfrage`, `admin`) VALUES
	(36, 58, 1, 0, 1),
	(37, 59, 1, 0, 0);
/*!40000 ALTER TABLE `rollen` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.user
CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(200) NOT NULL AUTO_INCREMENT,
  `Benutzername` varchar(200) NOT NULL,
  `Vorname` varchar(200) NOT NULL,
  `Nachname` varchar(200) NOT NULL,
  `Geburtsdatum` date NOT NULL,
  `Email` varchar(200) NOT NULL,
  `Passwort` varchar(200) NOT NULL,
  `Zeitstempel` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.user: ~2 rows (ungefähr)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`ID`, `Benutzername`, `Vorname`, `Nachname`, `Geburtsdatum`, `Email`, `Passwort`, `Zeitstempel`) VALUES
	(58, 'admin', 'Admin', 'Nutzer', '2000-01-01', 'admin@admin.de', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '2022-03-24 12:58:10'),
	(59, 'nutzer', 'Nutzer', 'Nutzer', '2000-01-01', 'nutzer@nutzer.de', '3097525b27a147bf5f95c8f37861c1aa77d5518b3c892aa8446e8784bee54d1f', '2022-03-24 12:59:17');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Exportiere Struktur von Tabelle webshop.warenkorb
CREATE TABLE IF NOT EXISTS `warenkorb` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_user` int(11) NOT NULL,
  `ID_produkt` int(11) NOT NULL,
  `wAnzahl` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=172 DEFAULT CHARSET=utf8mb4;

-- Exportiere Daten aus Tabelle webshop.warenkorb: ~1 rows (ungefähr)
DELETE FROM `warenkorb`;
/*!40000 ALTER TABLE `warenkorb` DISABLE KEYS */;
/*!40000 ALTER TABLE `warenkorb` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
