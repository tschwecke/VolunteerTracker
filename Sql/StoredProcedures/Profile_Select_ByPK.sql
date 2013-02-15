DELIMITER $$
drop procedure if exists `Profile_Select_ByPK`
$$
CREATE PROCEDURE `Profile_Select_ByPK` (profile_PK int)
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
WHERE `profile`.`Profile_PK` = profile_PK;

END$$

