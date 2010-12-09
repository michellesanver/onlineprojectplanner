
 CREATE TABLE IF NOT EXISTS `WI_Wiki_Pages` (
    `Wiki_page_id` INT NOT NULL AUTO_INCREMENT,
    `Project_id` INT NOT NULL ,
    `User_id` INT NOT NULL,
    `Created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), 
    `Updated` TIMESTAMP NULL, 
    `Title` VARCHAR(100) NOT NULL,
    `Text` TEXT NOT NULL,
    `Order` SMALLINT UNSIGNED NOT NULL,
    `Version` SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY ( `Wiki_page_id` ),
  CONSTRAINT `wiki_widget_ibfk_1` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `wiki_widget_ibfk_2` 
    FOREIGN KEY (`User_id`)
    REFERENCES `User` (`User_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


 CREATE TABLE IF NOT EXISTS `WI_Wiki_Pages_History` (
    `Wiki_page_history_id` INT NOT NULL AUTO_INCREMENT,
    `Wiki_page_id` INT NOT NULL,
    `Project_id` INT NOT NULL ,
    `User_id` INT NOT NULL,
    `Created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), 
    `Updated` TIMESTAMP NULL, 
    `Title` VARCHAR(100) NOT NULL,
    `Text` TEXT NOT NULL,
    `Order` SMALLINT UNSIGNED NOT NULL,
    `Version` SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY ( `Wiki_page_history_id` ),
  CONSTRAINT `wiki_history_widget_ibfk_1` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `wiki_history_widget_ibfk_2` 
    FOREIGN KEY (`User_id`)
    REFERENCES `User` (`User_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `wiki_history_widget_ibfk_3` 
    FOREIGN KEY (`Wiki_page_id`)
    REFERENCES `WI_Wiki_Pages` (`Wiki_page_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


 CREATE TABLE IF NOT EXISTS `WI_Wiki_Tags` (
    `Wiki_tags_id` INT NOT NULL AUTO_INCREMENT,
    `Wiki_page_id` INT NOT NULL,
    `Tag` VARCHAR(35) NOT NULL,
    PRIMARY KEY ( `Wiki_tags_id` ),
  CONSTRAINT `wiki_tags_widget_ibfk_1` 
    FOREIGN KEY (`Wiki_page_id`)
    REFERENCES `WI_Wiki_Pages` (`Wiki_page_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
