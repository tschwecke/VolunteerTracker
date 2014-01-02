DELIMITER $$
drop procedure if exists `Hours_Select_ByPK`
$$
CREATE PROCEDURE `Hours_Select_ByPK` (hours_PK int)
BEGIN

SELECT
    h.`Hours_PK`,
    h.`Volunteer_PK`,
    h.`InterestArea_PK`,
    h.`Date`,
    h.`NbrOfHours`,
    h.`Description`,
    h.`Status`,
    h.`Classroom`
FROM `hours` h
WHERE h.Hours_PK = hours_PK;

END$$
