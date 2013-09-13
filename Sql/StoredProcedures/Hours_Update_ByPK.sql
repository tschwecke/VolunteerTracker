DELIMITER $$
drop procedure if exists `Hours_Update_ByPK`
$$
CREATE PROCEDURE `Hours_Update_ByPK` (hours_PK int,
                                            volunteer_PK int,
                                            hoursDate date,
                                            nbrOfHours decimal(10,2),
                                            description varchar(500),
                                            status varchar(10))
BEGIN

UPDATE `hours`
SET
    `hours`.`Volunteer_PK` = volunteer_PK,
    `hours`.`Date` = hoursDate,
    `hours`.`NbrOfHours` = nbrOfHours,
    `hours`.`Description` = description,
    `hours`.`Status` = status,
    `hours`.`sys_LastUpdate` = NOW()
WHERE `hours`.`Hours_PK` = hours_PK;

END$$

