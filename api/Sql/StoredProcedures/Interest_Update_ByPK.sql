DELIMITER $$
drop procedure if exists `Interest_Update_ByPK`
$$
CREATE PROCEDURE `Interest_Update_ByPK` (interest_PK int,
                                        volunteer_PK int,
                                        interestArea_PK int)
BEGIN

UPDATE `interest`
SET
    `interest`.`Volunteer_PK` = volunteer_PK,
    `interest`.`InterestArea_PK` = interestArea_PK,
	`interest`.`sys_LastUpdate` = NOW()
WHERE `interest`.`Interest_PK` = interest_PK;


END$$

