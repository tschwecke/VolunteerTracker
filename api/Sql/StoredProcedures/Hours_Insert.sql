DELIMITER $$
drop procedure if exists `Hours_Insert`
$$
CREATE PROCEDURE `Hours_Insert` (volunteer_PK int,
                                    interestArea_PK int,
                                    hoursDate date,
                                    nbrOfHours decimal(10,2),
                                    description varchar(500),
                                    status varchar(10),
                                    classroom varchar(50))
BEGIN

INSERT INTO `hours`
(`hours`.`Volunteer_PK`,
`hours`.`InterestArea_PK`,
`hours`.`Date`,
`hours`.`NbrOfHours`,
`hours`.`Description`,
`hours`.`Status`,
`hours`.`Classroom`,
`hours`.`sys_CreateDate`,
`hours`.`sys_LastUpdate`)
VALUES
(
volunteer_PK,
interestArea_PK,
hoursDate,
nbrOfHours,
description,
status,
classroom,
NOW(),
NOW()
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$
