DELIMITER $$

drop procedure if exists `Volunteer_Select_ByPK`
$$
CREATE PROCEDURE `Volunteer_Select_ByPK` (volunteer_PK int)
BEGIN

SELECT
    `volunteer`.`Volunteer_PK`,
    `volunteer`.`FirstName`,
    `volunteer`.`LastName`,
    `volunteer`.`EmailAddress`,
    `volunteer`.`PasswordHash`,
    `volunteer`.`Salt`,
    `volunteer`.`Role_PK`
FROM `volunteer`
WHERE `volunteer`.`Volunteer_PK` = volunteer_PK;


END$$

