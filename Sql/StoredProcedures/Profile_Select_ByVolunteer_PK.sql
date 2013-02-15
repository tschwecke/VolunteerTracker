DELIMITER $$
drop procedure if exists `Profile_Select_ByVolunteer_PK`
$$
CREATE PROCEDURE `Profile_Select_ByVolunteer_PK` (volunteer_PK int)
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
WHERE `profile`.`Volunteer_PK` = volunteer_PK;

END$$

