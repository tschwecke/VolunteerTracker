DELIMITER $$
drop procedure if exists `KioskCheckIn_Insert`
$$
CREATE PROCEDURE `KioskCheckIn_Insert` (volunteer_PK int,
                                    interestArea_PK int,
                                    classroom varchar(50),
                                    checkInTime datetime)
BEGIN

INSERT INTO `kioskCheckIn`
(`kioskCheckIn`.`Volunteer_PK`,
`kioskCheckIn`.`InterestArea_PK`,
`kioskCheckIn`.`Classroom`,
`kioskCheckIn`.`CheckInTime`,
`kioskCheckIn`.`sys_CreateDate`,
`kioskCheckIn`.`sys_LastUpdate`)
VALUES
(
volunteer_PK,
interestArea_PK,
classroom,
checkInTime,
NOW(),
NOW()
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$
