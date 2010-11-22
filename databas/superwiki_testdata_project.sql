

-- -----------------------------------------------------
-- Data for table `ProjectRole`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `ProjectRole` (`ProjectRoleID`, `Role`) VALUES ('1', 'Admin');
INSERT INTO `ProjectRole` (`ProjectRoleID`, `Role`) VALUES ('2', 'Member');

COMMIT;

-- -----------------------------------------------------
-- Data for table `Project`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `Project` (`ProjectID`, `Title`, `Description`, `Created`) VALUES ('1', 'Test Project', 'Description for Test Project...', '9999-12-31 23:59:59');

COMMIT;

-- -----------------------------------------------------
-- Data for table `ProjectInvitation`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `ProjectInvitation` (`ProjectInvitationID`, `ProjectInvitationCode`, `ProjectID`, `ProjectRoleID`) VALUES ('1', '9e107d9d372bb6826bd81d3542a419d6', '1', '2');

COMMIT;

-- -----------------------------------------------------
-- Data for table `ProjectMember`
-- -----------------------------------------------------
SET AUTOCOMMIT=0;
INSERT INTO `ProjectMember` (`ProjectMemberID`, `UserID`, `ProjectID`, `ProjectRoleID`) VALUES ('1', '1', '1', '1');

COMMIT;