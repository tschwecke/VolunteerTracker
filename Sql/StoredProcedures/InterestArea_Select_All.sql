DELIMITER $$
drop procedure if exists `InterestArea_Select_All`
$$
CREATE PROCEDURE `InterestArea_Select_All` ()
BEGIN
SELECT
`interestarea`.`InterestArea_PK`,
`interestarea`.`Name`,
`interestarea`.`SortOrder`
FROM `interestarea`;

END$$

