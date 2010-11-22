
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- table`User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `UserID` int NOT NULL AUTO_INCREMENT,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `User_name` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- table`UserActivation`
--

CREATE TABLE IF NOT EXISTS `UserActivation` (
  `ActivationID` int NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  PRIMARY KEY (`ActivationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


--
-- table `UserResetPassword`
--

CREATE TABLE IF NOT EXISTS `UserResetPassword` (
  `UserID` int NOT NULL AUTO_INCREMENT,
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


--
-- table `ProjectRole`
-- 
CREATE  TABLE IF NOT EXISTS `ProjectRole` (
  `ProjectRoleID` INT NOT NULL AUTO_INCREMENT ,
  `Role` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`ProjectRoleID`) ,
  UNIQUE INDEX `Unique_Project_Role` (`Role` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


-- 
-- table `Project`
-- 
CREATE  TABLE IF NOT EXISTS `Project` (
  `ProjectID` INT NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(100) NOT NULL ,
  `Description` VARCHAR(300) NOT NULL ,
  `Created` TIMESTAMP NULL DEFAULT NOW() ,
  PRIMARY KEY (`ProjectID`) ,
  UNIQUE INDEX `Unique_Project_Title` (`Title` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `ProjectInvitation`
-- 
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
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `ProjectMember`
-- 
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
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



