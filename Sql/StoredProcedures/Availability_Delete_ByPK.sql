DELIMITER $$
drop procedure if exists `Availability_Delete_ByPK`
$$
CREATE PROCEDURE `Availability_Delete_ByPK` (availability_PK int)
BEGIN

DELETE FROM `availability` 
WHERE `availability`.`Availability_PK` = availability_PK;

END$$

