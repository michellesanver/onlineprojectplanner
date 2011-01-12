CREATE TABLE IF NOT EXISTS `WI_Chat_Room` (
  `Chat_room_id` INT NOT NULL,
  `Key` CHAR(32) NOT NULL,
  `Project_id` INT NOT NULL,
  PRIMARY KEY (`Chat_room_id`),
  UNIQUE INDEX `chat_widget_unique_1` (`Key` ASC),
  CONSTRAINT `chat_widget_ibfk_1` 
    FOREIGN KEY (`Project_id`)
    REFERENCES `Project` (`Project_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;