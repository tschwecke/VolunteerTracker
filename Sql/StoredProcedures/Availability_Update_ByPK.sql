DELIMITER $$
drop procedure if exists `Availability_Update_ByPK`
$$
CREATE PROCEDURE `Availability_Update_ByPK` (availability_PK int,
                                                volunteer_PK int,
                                                dayOfWeek varchar(10),
                                                timeOfDay varchar(10))
BEGIN

UPDATE `availability`
SET
`availability`.`Volunteer_PK` = volunteer_PK,
`availability`.`DayOfWeek` = dayOfWeek,
`availability`.`TimeOfDay` = timeOfDay
WHERE `availability`.`Availability_PK` = availability_PK;


END$$

