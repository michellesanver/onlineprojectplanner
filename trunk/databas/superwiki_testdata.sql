--
-- Dumping data for table `User`
--

INSERT INTO `User` VALUES(4, 'Ronaaald', 'Mcccdonald', 'ronald@gmail.com', 'bf199c2cd79a9d9080baed710e4763', 'ronald', 'brandonstreet', 90210, 'new york');


-- -----------------------------------------------------
-- Data for table `ProjectRole`
-- -----------------------------------------------------

INSERT INTO `ProjectRole` (`ProjectRoleID`, `Role`) VALUES ('1', 'Admin');
INSERT INTO `ProjectRole` (`ProjectRoleID`, `Role`) VALUES ('2', 'Member');

-- -----------------------------------------------------
-- Data for table `Project`
-- -----------------------------------------------------

INSERT INTO `Project` (`ProjectID`, `Title`, `Description`, `Created`) VALUES ('1', 'Test Project', 'Description for Test Project...', '9999-12-31 23:59:59');

-- -----------------------------------------------------
-- Data for table `ProjectInvitation`
-- -----------------------------------------------------

INSERT INTO `ProjectInvitation` (`ProjectInvitationID`, `ProjectInvitationCode`, `ProjectID`, `ProjectRoleID`) VALUES ('1', '9e107d9d372bb6826bd81d3542a419d6', '1', '2');

-- -----------------------------------------------------
-- Data for table `ProjectMember`
-- -----------------------------------------------------

INSERT INTO `ProjectMember` (`ProjectMemberID`, `UserID`, `ProjectID`, `ProjectRoleID`) VALUES ('1', '1', '1', '1');
