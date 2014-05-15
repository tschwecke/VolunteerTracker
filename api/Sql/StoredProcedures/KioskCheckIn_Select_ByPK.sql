DELIMITER $$
drop procedure if exists `KioskCheckIn_Select_ByPK`
$$
CREATE PROCEDURE `KioskCheckIn_Select_ByPK` (kioskCheckIn_PK int)
BEGIN

SELECT
    k.`KioskCheckIn_PK`,
    k.`Volunteer_PK`,
    k.`InterestArea_PK`,
    k.`Classroom`,
    k.`CheckInTime`,
    k.`CheckOutTime`
FROM `kioskCheckIn` k
WHERE k.KioskCheckIn_PK = kioskCheckIn_PK;

END$$
