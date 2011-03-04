
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- table`User`
-- updated to our standards!
-- 

CREATE TABLE `User` (
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

CREATE TABLE `User_Activation` (
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

CREATE TABLE `User_ResetPassword` (
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
CREATE TABLE `Project_Role` (
  `Project_role_id` INT NOT NULL AUTO_INCREMENT ,
  `Role` VARCHAR(25) NOT NULL ,
  `Project_role_id_u` INT NOT NULL ,
  PRIMARY KEY (`Project_role_id`) ,
  UNIQUE INDEX `Unique_Project_Role` (`Role` ASC) ,
  INDEX `FK_ProjectInvitation_ProjectRole` (`Project_role_id` ASC) ,
  CONSTRAINT `FK_ProjectRole_ProjectRole`
    FOREIGN KEY (`Project_role_id_u` )
    REFERENCES `Project_Role` (`Project_role_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


-- 
-- table `Project`
-- updated to our standards!
-- 
CREATE TABLE `Project` (
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
CREATE  TABLE `Project_Invitation` (
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
CREATE  TABLE `Project_Member` (
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
-- table `Widgets`
-- 
 CREATE TABLE `Widgets` (
    `Widget_id` INT NOT NULL AUTO_INCREMENT,
    `Widget_name` VARCHAR( 50 ) NOT NULL ,
    `In_development` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    `Is_core` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    `Minimum_role` VARCHAR(25) NULL,
    PRIMARY KEY ( `Widget_id` )
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- 
-- table `Project_Widgets`
-- 
 CREATE TABLE `Project_Widgets` (
	`Project_widgets_id` INT NOT NULL AUTO_INCREMENT,
	`Project_id` INT NOT NULL ,
    `Widget_id` INT NOT NULL,
    `Widget_instance_name` VARCHAR( 30 ) NOT NULL ,
	`Is_active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    `Order` SMALLINT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY ( `Project_widgets_id` ),
  CONSTRAINT `project_widget_ibfk_1` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `project_widget_ibfk_2` 
    FOREIGN KEY (`Widget_id`)
    REFERENCES `Widgets` (`Widget_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


-- 
-- table `Widget_Positions`
-- 
CREATE TABLE `Widget_Positions` (
    `Widget_postions_id` INT NOT NULL AUTO_INCREMENT,
    `Project_widgets_id` INT NOT NULL COMMENT 'also used with name instance_id',
    `Project_id` INT NOT NULL COMMENT 'used in select-query',
    `User_id` INT NOT NULL ,
    `Last_x_position` INT NOT NULL DEFAULT '0',
    `Last_y_position` INT NOT NULL DEFAULT '0',
    `Width` INT NOT NULL DEFAULT '0',
    `Height` INT NOT NULL DEFAULT '0',
    `Is_maximized` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
    `Is_open` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0', 
    PRIMARY KEY ( `Widget_postions_id` ),
  CONSTRAINT `widget_positions_ibfk_1` 
    FOREIGN KEY (`Project_widgets_id`)
    REFERENCES `Project_Widgets` (`Project_widgets_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `widget_positions_ibfk_2`
    FOREIGN KEY (`User_id` )
    REFERENCES `User` (`User_id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `widget_positions_ibfk_3` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;  

-- 
-- table `Default_Widgets`
-- 
CREATE TABLE `Default_Widgets` (
  `Default_widgets_id` int NOT NULL auto_increment,
  `Widgets_id` int default '0',
  `Is_core` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'if widget is core-widget (another folder)',
  PRIMARY KEY  (`Default_widgets_id`),
  KEY `Widgets_id_key` (`Widgets_id`),
  CONSTRAINT `Widgets_id_key` FOREIGN KEY (`Widgets_id`) REFERENCES `Widgets` (`Widget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- 
-- table `Widget_Settings`
--
CREATE TABLE `Widget_Settings` (
  `Settings_id` int NOT NULL AUTO_INCREMENT,
  `Widget_id` int NOT NULL,
  `Type_id` int NOT NULL,
  `Internal_id` int NOT NULL COMMENT 'The widgethandler must have a standard id for comunication with its settings individually.',
  `Name` varchar(128) DEFAULT NULL,
  `Order` int NOT NULL,
  PRIMARY KEY (`Settings_id`),
  KEY `Widget_id` (`Widget_id`),
  KEY `Type_id` (`Type_id`),
  CONSTRAINT `Widget_Settings_ibfk_1` FOREIGN KEY (`Widget_id`) REFERENCES `Widgets` (`Widget_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- 
-- table `Widget_Settings_Type`
--
CREATE TABLE `Widget_Settings_Type` (
  `Type_id` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `CI_rule` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`Type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- 
-- table `Widget_Settings_Value`
--
CREATE TABLE `Widget_Settings_Value` (
  `Widget_settings_value_id` int NOT NULL AUTO_INCREMENT,
  `Project_widgets_id` int DEFAULT '0',
  `Settings_id` int DEFAULT '0',
  `Value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Widget_settings_value_id`),
  KEY `Project_widgets_id` (`Project_widgets_id`),
  KEY `Settings_id` (`Settings_id`),
  CONSTRAINT `Widget_Settings_Value_ibfk_1` FOREIGN KEY (`Project_widgets_id`) REFERENCES `Project_Widgets` (`Project_widgets_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Widget_Settings_Value_ibfk_2` FOREIGN KEY (`Settings_id`) REFERENCES `Widget_Settings` (`Settings_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- 
-- table `Error_Log`
--
CREATE TABLE `Error_Log` (
  `Error_id` int NOT NULL AUTO_INCREMENT,
  `Ip_adress` varchar(15) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Function` varchar(56) NOT NULL,
  `Calling` varchar(56) NOT NULL,
  `Variables` varchar(300) NOT NULL,
  `Message` varchar(300) NOT NULL,
  PRIMARY KEY (`Error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;



-- 
-- insert data into `Project_Role`
-- 
INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (1, 'General', 1);
INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (2, 'Admin', 1);
INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (3, 'Member', 2);

-- 
-- insert data into `Default_Widgets` and `Widgets` (for Organizer)
-- 
INSERT INTO `Widgets` (`Widget_id`, `Widget_name`, `Is_core`, `Minimum_role`) VALUES (1, 'organizer', 1, 'Admin');
INSERT INTO `Default_Widgets` (`Default_widgets_id`, `Widgets_id`, `Is_core`) VALUES (1, 1, 1);


