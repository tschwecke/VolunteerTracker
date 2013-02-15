DELIMITER $$
drop procedure if exists `Profile_Select_ByFamilyId`
$$
CREATE PROCEDURE `Profile_Select_ByFamilyId` (familyId int)
BEGIN
SELECT
    `profile`.`Profile_PK`,
    `profile`.`Volunteer_PK`,
    `profile`.`FamilyId`,
    `profile`.`StreetAddress`,
    `profile`.`City`,
    `profile`.`State`,
    `profile`.`ZipCode`,
    `profile`.`PrimaryPhoneNbr`,
    `profile`.`PrimaryPhoneType`,
    `profile`.`BestTimePrimary`,
    `profile`.`SecondaryPhoneNbr`,
    `profile`.`SecondaryPhoneType`,
    `profile`.`BestTimeSecondary`,
    `profile`.`PreferEmail`,
    `profile`.`PreferPhone`,
    `profile`.`RelationshipToOrganization`
FROM `profile`
WHERE `profile`.`FamilyId` = familyId;

END$$

