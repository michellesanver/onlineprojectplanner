--
-- Dumping data for table `User`
--

INSERT INTO `User` VALUES(1, 'Ronaaald', 'Mcccdonald', 'ronald@gmail.com', 'bf199c2cd79a9d9080baed710e4763', 'ronald', 'brandonstreet', 90210, 'new york');


-- -----------------------------------------------------
-- Data for table `Project_Role`
-- -----------------------------------------------------

INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (1, 'General', 1);
INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (2, 'Admin', 1);
INSERT INTO `Project_Role` (`Project_role_id`, `Role`, `Project_role_id_u`) VALUES (3, 'Member', 2);

-- -----------------------------------------------------
-- Data for table `Project`
-- -----------------------------------------------------

INSERT INTO `Project` (`Project_id`, `Title`, `Description`) VALUES (1, 'Test Project', 'Description for Test Project...');

-- -----------------------------------------------------
-- Data for table `Project_Invitation`
-- -----------------------------------------------------

INSERT INTO `Project_Invitation` (`Project_invitation_id`, `Code`, `Project_id`, `Project_role_id`) VALUES (1, '9e107d9d372bb6826bd81d3542a419d6', 1, 2);

-- -----------------------------------------------------
-- Data for table `Project_Member`
-- -----------------------------------------------------

INSERT INTO `Project_Member` (`Project_member_id`, `User_id`, `Project_id`, `Project_role_id`) VALUES (1, 1, 1, 1);
