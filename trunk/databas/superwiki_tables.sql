
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- table`User`
-- updated to our standards!
-- 

CREATE TABLE IF NOT EXISTS `User` (
  `User_id` int NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`User_id`) ,
  UNIQUE INDEX `Unique_Email` (`Email` ASC) ,
  UNIQUE INDEX `Unique_Username` (`Username` ASC)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- table`User_Activation`
-- updated to our standards!
--

CREATE TABLE IF NOT EXISTS `User_Activation` (
  `Activation_id` int NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`Activation_id`),
  CONSTRAINT `user_activation_ibfk_1` 
    FOREIGN KEY (`Activation_id`)
    REFERENCES `User` (`User_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


--
-- table `User_ResetPassword`
-- updated to our standards!
--

CREATE TABLE IF NOT EXISTS `User_ResetPassword` (
  `User_id` int NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Resetcode` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`User_id`),
  CONSTRAINT `user_reset_ibfk_1` 
    FOREIGN KEY (`User_id`)
    REFERENCES `User` (`User_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


--
-- table `Project_Role`
-- updated to our standards!
-- 
CREATE  TABLE IF NOT EXISTS `Project_Role` (
  `Project_role_id` INT NOT NULL AUTO_INCREMENT ,
  `Role` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`Project_role_id`) ,
  UNIQUE INDEX `Unique_Project_Role` (`Role` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


-- 
-- table `Project`
-- updated to our standards!
-- 
CREATE  TABLE IF NOT EXISTS `Project` (
  `Project_id` INT NOT NULL AUTO_INCREMENT ,
  `Title` VARCHAR(100) NOT NULL ,
  `Description` VARCHAR(300) NOT NULL ,
  `Created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`Project_id`) ,
  UNIQUE INDEX `Unique_Project_Title` (`Title` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `Project_Invitation`
-- updated to our standards!
-- 
CREATE  TABLE IF NOT EXISTS `Project_Invitation` (
  `Project_invitation_id` INT NOT NULL AUTO_INCREMENT ,
  `Code` CHAR(32) NOT NULL ,
  `Project_id` INT NOT NULL ,
  `Project_role_id` INT NOT NULL ,
  PRIMARY KEY (`Project_invitation_id`) ,
  UNIQUE INDEX `Unique_ProjectInvitation_Code` (`Code` ASC) ,
  INDEX `FK_ProjectInvitation_Project` (`Project_id` ASC) ,
  INDEX `FK_ProjectInvitation_ProjectRole` (`Project_role_id` ASC) ,
  CONSTRAINT `FK_ProjectInvitation_Project`
    FOREIGN KEY (`Project_id` )
    REFERENCES `Project` (`Project_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectInvitation_ProjectRole`
    FOREIGN KEY (`Project_role_id` )
    REFERENCES `Project_Role` (`Project_role_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `Project_Member`
-- 
CREATE  TABLE IF NOT EXISTS `Project_Member` (
  `Project_member_id` INT NOT NULL AUTO_INCREMENT ,
  `User_id` INT NOT NULL ,
  `Project_id` INT NOT NULL ,
  `Project_role_id` INT NOT NULL ,
  PRIMARY KEY (`Project_member_id`) ,
  INDEX `FK_ProjectMember_User` (`User_id` ASC) ,
  INDEX `FK_ProjectMember_Project` (`Project_id` ASC) ,
  INDEX `FK_ProjectMember_ProjectRole` (`Project_role_id` ASC) ,
  CONSTRAINT `FK_ProjectMember_User`
    FOREIGN KEY (`User_id` )
    REFERENCES `User` (`User_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectMember_Project`
    FOREIGN KEY (`Project_id` )
    REFERENCES `Project` (`Project_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectMember_ProjectRole`
    FOREIGN KEY (`Project_role_id` )
    REFERENCES `Project_Role` (`Project_role_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `Project_Widgets`
-- 
 CREATE TABLE IF NOT EXISTS `Project_Widgets` (
	`Project_widgets_id` INT NOT NULL AUTO_INCREMENT,
	`Project_id` INT NOT NULL ,
    `Widget_id` INT NOT NULL,
	`Is_active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY ( `Project_widgets_id` ),
  CONSTRAINT `project_widget_ibfk_1` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `Widgets`
-- 
 CREATE TABLE IF NOT EXISTS `Widgets` (
    `Widget_id` INT NOT NULL AUTO_INCREMENT,
    `Widget_name` VARCHAR( 50 ) NOT NULL ,
    PRIMARY KEY ( `Widget_id` )
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;



