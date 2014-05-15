DELIMITER $$
drop procedure if exists `Interest_Delete_ByPK`
$$
CREATE PROCEDURE `Interest_Delete_ByPK` (interest_PK int)
BEGIN

DELETE FROM `interest`
WHERE `interest`.`Interest_PK` = interest_PK;

END$$

