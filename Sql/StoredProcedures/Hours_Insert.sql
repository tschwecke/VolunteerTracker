DELIMITER $$
drop procedure if exists `Hours_Insert`
$$
CREATE PROCEDURE `Hours_Insert` (volunteer_PK int,
                                    interestArea_PK int,
                                    hoursDate date,
                                    nbrOfHours decimal(10,2),
                                    description varchar(500),
                                    status varchar(10))
BEGIN

INSERT INTO `hours`
(`hours`.`Volunteer_PK`,
`hours`.`InterestArea_PK`,
`hours`.`Date`,
`hours`.`NbrOfHours`,
`hours`.`Description`,
`hours`.`Status`)
VALUES
(
volunteer_PK,
interestArea_PK,
hoursDate,
nbrOfHours,
description,
status
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$
