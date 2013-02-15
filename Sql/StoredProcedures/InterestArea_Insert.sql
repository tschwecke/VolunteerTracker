DELIMITER $$
drop procedure if exists `InterestArea_Insert`
$$
CREATE PROCEDURE `InterestArea_Insert` (name varchar(45),
                                            sortOrder int)
BEGIN
INSERT INTO `interestarea`
(`interestarea`.`Name`,
`interestarea`.`SortOrder`)
VALUES
(
name,
sortOrder
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

