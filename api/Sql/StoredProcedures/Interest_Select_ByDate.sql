DELIMITER $$
drop procedure if exists `Interest_Select_ByDate`
$$
CREATE PROCEDURE `Interest_Select_ByDate` (comparisonDate date)
BEGIN

SELECT
`interest`.`Interest_PK`,
`interest`.`Volunteer_PK`,
`interest`.`InterestArea_PK`
FROM `interest`
WHERE `interest`.`sys_LastUpdate` = comparisonDate;


END$$

