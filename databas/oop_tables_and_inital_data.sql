
--
-- manual mysql dump 2011-03-12
-- Fredrik Johansson <tazzie76@gmail.com>
-- https://code.google.com/p/onlineprojectplanner/
--

CREATE TABLE `Project_Role` (
  `Project_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `Role` varchar(25) NOT NULL,
  `Project_role_id_u` int(11) NOT NULL,
  PRIMARY KEY (`Project_role_id`),
  UNIQUE KEY `Unique_Project_Role` (`Role`),
  KEY `FK_ProjectInvitation_ProjectRole` (`Project_role_id`),
  KEY `FK_ProjectRole_ProjectRole` (`Project_role_id_u`),
  CONSTRAINT `FK_ProjectRole_ProjectRole` FOREIGN KEY (`Project_role_id_u`) REFERENCES `Project_Role` (`Project_role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE `Widgets` (
  `Widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `Widget_name` varchar(50) NOT NULL,
  `In_development` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'set to 1 if widget is in development; will not be deleted when syncing',
  `Is_core` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Minimum_role` varchar(25) DEFAULT NULL,
  `Have_DB` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `DB_installed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Widget_id`),
  KEY `Minimum_rolename` (`Minimum_role`),
  CONSTRAINT `Minimum_rolename` FOREIGN KEY (`Minimum_role`) REFERENCES `Project_Role` (`Role`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Default_Widgets` (
  `Default_widgets_id` int(11) NOT NULL AUTO_INCREMENT,
  `Widgets_id` int(11) DEFAULT '0',
  `Is_core` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`Default_widgets_id`),
  KEY `Widgets_id_key` (`Widgets_id`),
  CONSTRAINT `Widgets_id_key` FOREIGN KEY (`Widgets_id`) REFERENCES `Widgets` (`Widget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `Project_Widgets` (
  `Project_widgets_id` int(11) NOT NULL AUTO_INCREMENT,
  `Project_id` int(11) NOT NULL,
  `Widget_id` int(11) NOT NULL,
  `Widget_instance_name` varchar(50) NOT NULL,
  `Is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Project_widgets_id`),
  KEY `Project_id` (`Project_id`),
  KEY `Widget_id` (`Widget_id`),
  CONSTRAINT `Project_Widgets_ibfk_1` FOREIGN KEY (`Project_id`) REFERENCES `Project` (`Project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Project_Widgets_ibfk_2` FOREIGN KEY (`Widget_id`) REFERENCES `Widgets` (`Widget_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Error_Log` (
  `Error_id` int(11) NOT NULL AUTO_INCREMENT,
  `Ip_adress` varchar(15) NOT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Function` varchar(56) NOT NULL,
  `Calling` varchar(56) NOT NULL,
  `Variables` varchar(300) NOT NULL,
  `Message` varchar(300) NOT NULL,
  PRIMARY KEY (`Error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `User` (
  `User_id` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`User_id`),
  UNIQUE KEY `Unique_Email` (`Email`),
  UNIQUE KEY `Unique_Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Widget_Positions` (
  `Widget_postions_id` int(11) NOT NULL AUTO_INCREMENT,
  `Project_widgets_id` int(11) NOT NULL COMMENT 'also used with name instance_id',
  `Project_id` int(11) NOT NULL COMMENT 'used in select-query',
  `User_id` int(11) NOT NULL,
  `Last_x_position` int(11) NOT NULL DEFAULT '0',
  `Last_y_position` int(11) NOT NULL DEFAULT '0',
  `Width` int(11) NOT NULL DEFAULT '0',
  `Height` int(11) NOT NULL DEFAULT '0',
  `Is_maximized` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `Is_open` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`Widget_postions_id`),
  KEY `widget_positions_ibfk_1` (`Project_widgets_id`),
  KEY `widget_positions_ibfk_2` (`User_id`),
  KEY `widget_positions_ibfk_3` (`Project_id`),
  CONSTRAINT `widget_positions_ibfk_1` FOREIGN KEY (`Project_widgets_id`) REFERENCES `Project_Widgets` (`Project_widgets_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `widget_positions_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `User` (`User_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `widget_positions_ibfk_3` FOREIGN KEY (`Project_id`) REFERENCES `Project` (`Project_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Project` (
  `Project_id` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(100) NOT NULL,
  `Description` varchar(300) NOT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Project_id`),
  UNIQUE KEY `Unique_Project_Title` (`Title`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `User_Activation` (
  `Activation_id` int(11) NOT NULL,
  `Code` varchar(32) NOT NULL,
  `Created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Activation_id`),
  CONSTRAINT `user_activation_ibfk_1` FOREIGN KEY (`Activation_id`) REFERENCES `User` (`User_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Widget_Settings` (
  `Settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `Widget_id` int(11) NOT NULL,
  `Type_id` int(11) NOT NULL,
  `Internal_id` int(11) NOT NULL COMMENT 'The widgethandler must have a standard id for comunication with its settings individually.',
  `Name` varchar(128) DEFAULT NULL,
  `Order` int(11) NOT NULL,
  PRIMARY KEY (`Settings_id`),
  KEY `Widget_id` (`Widget_id`),
  KEY `Type_id` (`Type_id`),
  CONSTRAINT `Widget_Settings_ibfk_1` FOREIGN KEY (`Widget_id`) REFERENCES `Widgets` (`Widget_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `Project_Invitation` (
  `Project_invitation_id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` char(32) NOT NULL,
  `Project_id` int(11) NOT NULL,
  `Project_role_id` int(11) NOT NULL,
  PRIMARY KEY (`Project_invitation_id`),
  UNIQUE KEY `Unique_ProjectInvitation_Code` (`Code`),
  KEY `FK_ProjectInvitation_Project` (`Project_id`),
  KEY `FK_ProjectInvitation_ProjectRole` (`Project_role_id`),
  CONSTRAINT `FK_ProjectInvitation_Project` FOREIGN KEY (`Project_id`) REFERENCES `Project` (`Project_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_ProjectInvitation_ProjectRole` FOREIGN KEY (`Project_role_id`) REFERENCES `Project_Role` (`Project_role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `User_ResetPassword` (
  `User_id` int(11) NOT NULL AUTO_INCREMENT,
  `Firstname` varchar(100) NOT NULL,
  `Lastname` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Resetcode` varchar(10) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Streetadress` varchar(100) NOT NULL DEFAULT '',
  `Postalcode` int(5) NOT NULL DEFAULT '0',
  `Hometown` varchar(130) NOT NULL DEFAULT '',
  PRIMARY KEY (`User_id`),
  CONSTRAINT `user_reset_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `User` (`User_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Widget_Settings_Type` (
  `Type_id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `CI_rule` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`Type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

CREATE TABLE `Project_Member` (
  `Project_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `User_id` int(11) NOT NULL,
  `Project_id` int(11) NOT NULL,
  `Project_role_id` int(11) NOT NULL,
  PRIMARY KEY (`Project_member_id`),
  KEY `FK_ProjectMember_User` (`User_id`),
  KEY `FK_ProjectMember_Project` (`Project_id`),
  KEY `FK_ProjectMember_ProjectRole` (`Project_role_id`),
  CONSTRAINT `Project_Member_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `User` (`User_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Project_Member_ibfk_2` FOREIGN KEY (`Project_id`) REFERENCES `Project` (`Project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Project_Member_ibfk_3` FOREIGN KEY (`Project_role_id`) REFERENCES `Project_Role` (`Project_role_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `Widget_Settings_Value` (
  `Widget_settings_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `Project_widgets_id` int(11) DEFAULT '0',
  `Settings_id` int(11) DEFAULT '0',
  `Value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`Widget_settings_value_id`),
  KEY `Project_widgets_id` (`Project_widgets_id`),
  KEY `Settings_id` (`Settings_id`),
  CONSTRAINT `Widget_Settings_Value_ibfk_1` FOREIGN KEY (`Project_widgets_id`) REFERENCES `Project_Widgets` (`Project_widgets_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Widget_Settings_Value_ibfk_2` FOREIGN KEY (`Settings_id`) REFERENCES `Widget_Settings` (`Settings_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- initial data
--

INSERT INTO `Widgets` (`Widget_id`, `Widget_name`, `Is_core`, `Minimum_role`) VALUES (1, 'organizer', 1, 'Admin');
INSERT INTO `Default_Widgets` (`Default_widgets_id`, `Widgets_id`, `Is_core`) VALUES (1, 1, 1);

INSERT INTO `Project_Role` (Project_role_id,Role,Project_role_id_u) VALUES (1,'Admin',3);
INSERT INTO `Project_Role` (Project_role_id,Role,Project_role_id_u) VALUES (2,'Member',1);
INSERT INTO `Project_Role` (Project_role_id,Role,Project_role_id_u) VALUES (3,'General',3);

INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (1,'Int','required number');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (2,'Alphabetical characters','required');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (3,'Boolean','');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (4,'Float','required number');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (5,'Date','required date');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (6,'Mixed','');
INSERT INTO `Widget_Settings_Type` (Type_id,Name,CI_rule) VALUES (7,'Ip-adress','');