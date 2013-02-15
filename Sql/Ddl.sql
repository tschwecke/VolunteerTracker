delimiter $$

drop table IF EXISTS `hours`;$$

drop table IF EXISTS `interest`;$$

drop table IF EXISTS `interestarea`;$$

drop table IF EXISTS `availability`;$$

drop table IF EXISTS `profile`;$$

drop table IF EXISTS `volunteer`;$$

drop table IF EXISTS `roletoright`;$$

drop table IF EXISTS `right`;$$

drop table IF EXISTS `role`;$$


CREATE TABLE `right` (
  `Right_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Code` varchar(45) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Right_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8$$


CREATE TABLE `role` (
  `Role_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Role_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `roletoright` (
  `RoleToRight_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Role_PK` int(11) NOT NULL,
  `Right_PK` int(11) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`RoleToRight_PK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8$$


CREATE TABLE `volunteer` (
  `Volunteer_PK` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `EmailAddress` varchar(100) NOT NULL,
  `PasswordHash` varchar(100) NOT NULL,
  `Salt` varchar(36) NOT NULL,
  `Role_PK` int(11) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Volunteer_PK`),
  UNIQUE KEY `EmailAddress_UNIQUE` (`EmailAddress`),
  KEY `FK_volunteer_role_pk` (`Role_PK`),
  CONSTRAINT `FK_volunteer_role_pk` FOREIGN KEY (`Role_PK`) REFERENCES `role` (`Role_PK`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `profile` (
  `Profile_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Volunteer_PK` int(11) NOT NULL,
  `FamilyId` int(11) DEFAULT NULL,
  `StreetAddress` varchar(100) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(2) DEFAULT NULL,
  `ZipCode` varchar(10) DEFAULT NULL,
  `PrimaryPhoneNbr` varchar(25) DEFAULT NULL,
  `PrimaryPhoneType` varchar(15) DEFAULT NULL,
  `BestTimePrimary` varchar(15) DEFAULT NULL,
  `SecondaryPhoneNbr` varchar(25) DEFAULT NULL,
  `SecondaryPhoneType` varchar(15) DEFAULT NULL,
  `BestTimeSecondary` varchar(15) DEFAULT NULL,
  `PreferEmail` int(11) DEFAULT NULL,
  `PreferPhone` int(11) DEFAULT NULL,
  `RelationshipToOrganization` varchar(25) DEFAULT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Profile_PK`),
  UNIQUE KEY `Profile_Volunteer_PK` (`Volunteer_PK`),
  KEY `Profile_FamilyID` (`FamilyId`),
  CONSTRAINT `Profile_Volunteer_PK` FOREIGN KEY (`Volunteer_PK`) REFERENCES `volunteer` (`Volunteer_PK`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `availability` (
  `Availability_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Volunteer_PK` int(11) NOT NULL,
  `DayOfWeek` varchar(10) NOT NULL,
  `TimeOfDay` varchar(10) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Availability_PK`),
  UNIQUE KEY `UserId` (`Volunteer_PK`,`DayOfWeek`,`TimeOfDay`),
  KEY `UserAvailability_Volunteer_PK` (`Volunteer_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `interestarea` (
  `InterestArea_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `SortOrder` int(11) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`InterestArea_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `interest` (
  `Interest_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Volunteer_PK` int(11) NOT NULL,
  `InterestArea_PK` int(11) NOT NULL,
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Interest_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


CREATE TABLE `hours` (
  `Hours_PK` int(11) NOT NULL AUTO_INCREMENT,
  `Volunteer_PK` int(11) NOT NULL,
  `InterestArea_PK` int(11) NOT NULL,
  `Date` date NOT NULL,
  `NbrOfHours` decimal(10,2) NOT NULL,
  `Description` varchar(500) DEFAULT NULL,
  `Status` varchar(10) NOT NULL DEFAULT 'Pending',
  `sys_CreateDate` datetime DEFAULT NULL,
  `sys_LastUpdate` datetime DEFAULT NULL,
  PRIMARY KEY (`Hours_PK`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8$$


