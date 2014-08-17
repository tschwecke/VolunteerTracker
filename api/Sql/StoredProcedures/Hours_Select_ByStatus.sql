DELIMITER $$
drop procedure if exists `Hours_Select_ByStatus`
$$
CREATE PROCEDURE `Hours_Select_ByStatus` (status varchar(10), schoolYearStartDate date)
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
WHERE h.Status = status
AND h.Date >= schoolYearStartDate
ORDER BY h.`Date`;

END$$
