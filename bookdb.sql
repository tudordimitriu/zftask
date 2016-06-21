-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2016 at 04:32 PM
-- Server version: 5.5.42
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Author`
--

DROP TABLE IF EXISTS `Author`;
CREATE TABLE `Author` (
  `IdAuthor` int(11) NOT NULL,
  `FirstName` varchar(255) NOT NULL,
  `LastName` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Author`
--

INSERT INTO `Author` (`IdAuthor`, `FirstName`, `LastName`) VALUES
(1, 'Adam', 'Smith'),
(2, 'Rudyard', 'Kipling'),
(3, 'Geoffrey', 'Chaucer'),
(4, 'John', 'Doe');

-- --------------------------------------------------------

--
-- Table structure for table `Edition`
--

DROP TABLE IF EXISTS `Edition`;
CREATE TABLE `Edition` (
  `IdEdition` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL COMMENT 'The book name as it shows up on the real volume',
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `ISBN10` varchar(10) DEFAULT NULL COMMENT ' ',
  `ISBN13` varchar(13) DEFAULT NULL COMMENT '	',
  `IdParent` int(11) DEFAULT NULL,
  `IdPublisher` int(11) DEFAULT NULL,
  `IdTitle` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Edition`
--

INSERT INTO `Edition` (`IdEdition`, `Name`, `Date`, `ISBN10`, `ISBN13`, `IdParent`, `IdPublisher`, `IdTitle`) VALUES
(1, 'Avuția Națiunilor', '2011-01-01', NULL, '9789731931784', NULL, 1, 1),
(2, 'Puck of Pook''s Hill', '1994-01-01', '1853261386', NULL, NULL, 2, 2),
(7, 'Rewards and Fairies', '1995-01-01', '1853261599', NULL, NULL, 2, 3),
(8, 'Les Contes de Canterbury et Autres Oeuvres', '2010-03-01', NULL, '9782221109830', NULL, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `Publisher`
--

DROP TABLE IF EXISTS `Publisher`;
CREATE TABLE `Publisher` (
  `IdPublisher` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='List of publishing houses in the system';

--
-- Dumping data for table `Publisher`
--

INSERT INTO `Publisher` (`IdPublisher`, `Name`) VALUES
(1, 'Publica'),
(2, 'Wordsworth'),
(3, 'Bouquins');

-- --------------------------------------------------------

--
-- Table structure for table `Title`
--

DROP TABLE IF EXISTS `Title`;
CREATE TABLE `Title` (
  `IdTitle` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Synopsis` varchar(255) DEFAULT NULL,
  `Rating` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Title`
--

INSERT INTO `Title` (`IdTitle`, `Title`, `Synopsis`, `Rating`) VALUES
(1, 'Avutia Natiunilor', 'Wealth of Nations - Romanian Translation', 4),
(2, 'Puck of Pook''s Hill', NULL, 5),
(3, 'Rewards And Fairies', NULL, 5),
(4, 'Les Contes de Canterbury et Autres Oeuvres', NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `TitleAuthor`
--

DROP TABLE IF EXISTS `TitleAuthor`;
CREATE TABLE `TitleAuthor` (
  `IdTitle` int(11) NOT NULL,
  `IdAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Associates titles and authors';

--
-- Dumping data for table `TitleAuthor`
--

INSERT INTO `TitleAuthor` (`IdTitle`, `IdAuthor`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 3),
(3, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Author`
--
ALTER TABLE `Author`
  ADD PRIMARY KEY (`IdAuthor`,`FirstName`,`LastName`);

--
-- Indexes for table `Edition`
--
ALTER TABLE `Edition`
  ADD PRIMARY KEY (`IdEdition`),
  ADD KEY `IdPuplisher_idx` (`IdPublisher`),
  ADD KEY `IdEdition_idx` (`IdParent`),
  ADD KEY `IdTitle_idx` (`IdTitle`);

--
-- Indexes for table `Publisher`
--
ALTER TABLE `Publisher`
  ADD PRIMARY KEY (`IdPublisher`);

--
-- Indexes for table `Title`
--
ALTER TABLE `Title`
  ADD PRIMARY KEY (`IdTitle`,`Title`,`Rating`);

--
-- Indexes for table `TitleAuthor`
--
ALTER TABLE `TitleAuthor`
  ADD PRIMARY KEY (`IdTitle`,`IdAuthor`),
  ADD KEY `IdAuthor_idx` (`IdAuthor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Author`
--
ALTER TABLE `Author`
  MODIFY `IdAuthor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Edition`
--
ALTER TABLE `Edition`
  MODIFY `IdEdition` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `Publisher`
--
ALTER TABLE `Publisher`
  MODIFY `IdPublisher` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `Title`
--
ALTER TABLE `Title`
  MODIFY `IdTitle` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Edition`
--
ALTER TABLE `Edition`
  ADD CONSTRAINT `IdEdition` FOREIGN KEY (`IdParent`) REFERENCES `Edition` (`IdEdition`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `IdPuplisher` FOREIGN KEY (`IdPublisher`) REFERENCES `Publisher` (`IdPublisher`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `IdTitle` FOREIGN KEY (`IdTitle`) REFERENCES `Title` (`IdTitle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `TitleAuthor`
--
ALTER TABLE `TitleAuthor`
  ADD CONSTRAINT `IdAuthor` FOREIGN KEY (`IdAuthor`) REFERENCES `Author` (`IdAuthor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
