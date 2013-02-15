DELIMITER $$
drop procedure if exists `Availability_Insert`
$$
CREATE PROCEDURE `Availability_Insert` (volunteer_PK int,
                                            dayOfWeek varchar(10),
                                            timeOfDay varchar(10))
BEGIN
INSERT INTO `availability`
(`availability`.`Volunteer_PK`,
`availability`.`DayOfWeek`,
`availability`.`TimeOfDay`)
VALUES
(
volunteer_PK,
dayOfWeek,
timeOfDay
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

