-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 19 november 2010 kl 11:12
-- Serverversion: 5.1.36
-- PHP-version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Databas: `webbklient`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `user_activation`
--

CREATE TABLE IF NOT EXISTS `user_activation` (
  `ActivationID` int(11) unsigned NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ActivationID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
