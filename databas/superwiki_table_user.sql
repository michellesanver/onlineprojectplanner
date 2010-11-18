-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 18 november 2010 kl 21:42
-- Serverversion: 5.1.36
-- PHP-version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Databas: `webbklient`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `User_name` varchar(100) NOT NULL,
  `Reset_code` varchar(8) DEFAULT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  `Activation_code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
