DELIMITER $$
drop procedure if exists `InterestArea_Insert`
$$
CREATE PROCEDURE `InterestArea_Insert` (name varchar(45),
                                            sortOrder int)
BEGIN
INSERT INTO `interestarea`
(`interestarea`.`Name`,
`interestarea`.`SortOrder`,
`interestarea`.`sys_CreateDate`,
`interestarea`.`sys_LastUpdate`)
VALUES
(
name,
sortOrder,
NOW(),
NOW()
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

