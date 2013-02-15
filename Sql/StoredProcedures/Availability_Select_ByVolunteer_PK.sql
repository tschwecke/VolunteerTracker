DELIMITER $$
drop procedure if exists `Availability_Select_ByVolunteer_PK`
$$
CREATE PROCEDURE `Availability_Select_ByVolunteer_PK` (volunteer_PK int)
BEGIN
SELECT
    `availability`.`Availability_PK`,
    `availability`.`Volunteer_PK`,
    `availability`.`DayOfWeek`,
    `availability`.`TimeOfDay`
FROM `availability`
WHERE `availability`.`Volunteer_PK` = volunteer_PK;

END$$

