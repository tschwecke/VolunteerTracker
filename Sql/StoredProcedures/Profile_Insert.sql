DELIMITER $$
drop procedure if exists `Profile_Insert`
$$
CREATE PROCEDURE `Profile_Insert` (volunteer_PK int,
                                        familyId int,
                                        streetAddress varchar(100),
                                        city varchar(50),
                                        state varchar(2),
                                        zipCode varchar(10),
                                        primaryPhoneNbr varchar(25),
                                        primaryPhoneType varchar(15),
                                        bestTimePrimary varchar(15),
                                        secondaryPhoneNbr varchar(25),
                                        secondaryPhoneType varchar(15),
                                        bestTimeSecondary varchar(15),
                                        preferEmail int,
                                        preferPhone int,
                                        relationshipToOrganization varchar(25))
BEGIN
INSERT INTO `profile`
(`Volunteer_PK`,
`FamilyId`,
`StreetAddress`,
`City`,
`State`,
`ZipCode`,
`PrimaryPhoneNbr`,
`PrimaryPhoneType`,
`BestTimePrimary`,
`SecondaryPhoneNbr`,
`SecondaryPhoneType`,
`BestTimeSecondary`,
`PreferEmail`,
`PreferPhone`,
`RelationshipToOrganization`)
VALUES
(
volunteer_PK,
familyId,
streetAddress,
city,
state,
zipCode,
primaryPhoneNbr,
primaryPhoneType,
bestTimePrimary,
secondaryPhoneNbr,
secondaryPhoneType,
bestTimeSecondary,
preferEmail,
preferPhone,
relationshipToOrganization
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

