DELIMITER $$

drop procedure if exists `Volunteer_Delete_ByPK`
$$

CREATE PROCEDURE `Volunteer_Delete_ByPK` (volunteer_PK int)    
BEGIN

DELETE FROM `volunteer`
WHERE `volunteer`.`Volunteer_PK` = volunteer_PK;

END$$

