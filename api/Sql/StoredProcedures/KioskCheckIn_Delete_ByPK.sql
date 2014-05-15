DELIMITER $$
drop procedure if exists `KioskCheckIn_Delete_ByPK`
$$
CREATE PROCEDURE `KioskCheckIn_Delete_ByPK` (kioskCheckIn_PK int)
BEGIN

DELETE FROM `kioskCheckIn`
WHERE `kioskCheckIn`.`KioskCheckIn_PK` = kioskCheckIn_PK;

END$$

