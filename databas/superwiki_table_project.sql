-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2010 at 05:16 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `superwiki`
--
-- --------------------------------------------------------




-- -----------------------------------------------------
-- Table `ProjectRole`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ProjectRole` ;

CREATE  TABLE IF NOT EXISTS `ProjectRole` (
  `ProjectRoleID` INT NOT NULL AUTO_INCREMENT ,
  `Role` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`ProjectRoleID`) ,
  UNIQUE INDEX `Unique_Project_Role` (`Role` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `Project`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Project` ;

CREATE  TABLE IF NOT EXISTS `Project` (
  `ProjectID` INT NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(100) NOT NULL ,
  `Description` VARCHAR(300) NOT NULL ,
  `Created` TIMESTAMP NULL DEFAULT NOW() ,
  PRIMARY KEY (`ProjectID`) ,
  UNIQUE INDEX `Unique_Project_Title` (`Title` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ProjectInvitation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ProjectInvitation` ;

CREATE  TABLE IF NOT EXISTS `ProjectInvitation` (
  `ProjectInvitationID` INT NOT NULL AUTO_INCREMENT ,
  `ProjectInvitationCode` CHAR(32) NOT NULL ,
  `ProjectID` INT NOT NULL ,
  `ProjectRoleID` INT NOT NULL ,
  PRIMARY KEY (`ProjectInvitationID`) ,
  UNIQUE INDEX `Unique_ProjectInvitation_ProjectInvitationCode` (`ProjectInvitationCode` ASC) ,
  INDEX `FK_ProjectInvitation_Project` (`ProjectID` ASC) ,
  INDEX `FK_ProjectInvitation_ProjectRole` (`ProjectRoleID` ASC) ,
  CONSTRAINT `FK_ProjectInvitation_Project`
    FOREIGN KEY (`ProjectID` )
    REFERENCES `Project` (`ProjectID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectInvitation_ProjectRole`
    FOREIGN KEY (`ProjectRoleID` )
    REFERENCES `ProjectRole` (`ProjectRoleID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ProjectMember`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ProjectMember` ;

CREATE  TABLE IF NOT EXISTS `ProjectMember` (
  `ProjectMemberID` INT NOT NULL AUTO_INCREMENT ,
  `UserID` INT NOT NULL ,
  `ProjectID` INT NOT NULL ,
  `ProjectRoleID` INT NOT NULL ,
  PRIMARY KEY (`ProjectMemberID`) ,
  INDEX `FK_ProjectMember_User` (`UserID` ASC) ,
  INDEX `FK_ProjectMember_Project` (`ProjectID` ASC) ,
  INDEX `FK_ProjectMember_ProjectRole` (`ProjectRoleID` ASC) ,
  CONSTRAINT `FK_ProjectMember_User`
    FOREIGN KEY (`UserID` )
    REFERENCES `User` (`UserID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectMember_Project`
    FOREIGN KEY (`ProjectID` )
    REFERENCES `Project` (`ProjectID` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectMember_ProjectRole`
    FOREIGN KEY (`ProjectRoleID` )
    REFERENCES `ProjectRole` (`ProjectRoleID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;