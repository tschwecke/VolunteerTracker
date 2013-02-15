DELIMITER $$

drop procedure if exists `Volunteer_Select_All`
$$
CREATE PROCEDURE `Volunteer_Select_All` ()
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
ORDER BY `volunteer`.`LastName`, `volunteer`.`FirstName`;


END$$

