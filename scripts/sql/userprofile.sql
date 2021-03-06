/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table user
CREATE TABLE IF NOT EXISTS `user` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `displayName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `username` varchar(45) NOT NULL DEFAULT '',
  `token` varchar(255) DEFAULT NULL,
  `login` int(1) DEFAULT NULL,
  `register` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isAdmin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `isAdmin` (`isAdmin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table user_data
CREATE TABLE IF NOT EXISTS `user_data` (
  `userId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `hobby` varchar(200) DEFAULT NULL,
  `signature` mediumtext,
  `nameDescription` varchar(300) DEFAULT NULL,
  `icq` int(11) DEFAULT NULL,
  `myspace` varchar(300) DEFAULT NULL,
  `facebook` varchar(300) DEFAULT NULL,
  `googleplus` varchar(300) DEFAULT NULL,
  `homepage` varchar(300) DEFAULT NULL,
  `blog` varchar(45) DEFAULT NULL,
  `picture` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userDataId` (`userId`),
  CONSTRAINT `FK_user_data_user` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
