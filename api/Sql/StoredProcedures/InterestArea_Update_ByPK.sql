DELIMITER $$
drop procedure if exists `InterestArea_Update_ByPK`
$$
CREATE PROCEDURE `InterestArea_Update_ByPK` (interestarea_PK int,
                                            name varchar(45),
                                            sortOrder int)
BEGIN

UPDATE `interestarea`
SET
	`interestarea`.`Name` = name,
	`interestarea`.`SortOrder` = sortOrder,
	`interestarea`.`sys_LastUpdate` = NOW()
WHERE `interestarea`.`InterestArea_PK` = interestarea_PK;

END$$

