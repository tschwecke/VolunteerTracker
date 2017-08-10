DELIMITER $$

drop procedure if exists `Volunteer_Select_ByDate`
$$
CREATE PROCEDURE `Volunteer_Select_ByDate` (comparisonDate int)
BEGIN

SELECT
    `volunteer`.`Volunteer_PK`,
    `volunteer`.`FirstName`,
    `volunteer`.`LastName`,
    `volunteer`.`EmailAddress`,
    `volunteer`.`PasswordHash`,
    `volunteer`.`Salt`,
    `volunteer`.`FamilyId`,
    `volunteer`.`PrimaryPhoneNbr`,
    `volunteer`.`Role_PK`
FROM `volunteer`
WHERE `volunteer`.`sys_LastUpdate` > comparisonDate;


END$$

