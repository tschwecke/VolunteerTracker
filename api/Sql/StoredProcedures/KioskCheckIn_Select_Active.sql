DELIMITER $$
drop procedure if exists `KioskCheckIn_Select_Active`
$$
CREATE PROCEDURE `KioskCheckIn_Select_Active` ()
BEGIN

SELECT
    k.`KioskCheckIn_PK`,
    k.`Volunteer_PK`,
    k.`InterestArea_PK`,
    k.`Classroom`,
    k.`CheckInTime`,
    k.`CheckOutTime`
FROM `kioskCheckIn` k
WHERE k.CheckOutTime IS NULL
AND k.CheckInTime > DATE_ADD(NOW(),INTERVAL -18 HOUR);

END$$
