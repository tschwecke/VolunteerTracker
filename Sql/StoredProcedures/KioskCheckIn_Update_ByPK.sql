DELIMITER $$
drop procedure if exists `KioskCheckIn_Update_ByPK`
$$
CREATE PROCEDURE `KioskCheckIn_Update_ByPK` (kioskCheckIn_PK int,
                                            volunteer_PK int,
                                            interestArea_PK int,
                                            classroom varchar(50),
                                            checkInTime datetime,
                                            checkOutTime datetime)
BEGIN

UPDATE `kioskCheckIn`
SET
    `kioskCheckIn`.`Volunteer_PK` = volunteer_PK,
    `kioskCheckIn`.`InterestArea_PK` = interestArea_PK,
    `kioskCheckIn`.`Classroom` = classroom,
    `kioskCheckIn`.`CheckInTime` = checkInTime,
    `kioskCheckIn`.`CheckOutTime` = checkOutTime,
    `kioskCheckIn`.`sys_LastUpdate` = NOW()
WHERE `kioskCheckIn`.`KioskCheckIn_PK` = kioskCheckIn_PK;

END$$
