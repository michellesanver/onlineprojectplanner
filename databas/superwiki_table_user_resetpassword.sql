
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";  

--
-- Struktur f?r tabell `User_resetpassword`
--

CREATE TABLE IF NOT EXISTS `User_resetpassword` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Reset_code` varchar(10) NOT NULL,
  `User_name` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

