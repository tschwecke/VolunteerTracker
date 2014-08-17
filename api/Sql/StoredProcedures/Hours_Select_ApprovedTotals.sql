DELIMITER $$
drop procedure if exists `Hours_Select_ApprovedTotals`
$$
CREATE PROCEDURE `Hours_Select_ApprovedTotals` (schoolYearStartDate date)
BEGIN

SELECT
    h.`Volunteer_PK` as 'Id',
    SUM(NbrOfHours) as 'Hours'
FROM `hours` h
WHERE h.`Status` = 'Approved'
GROUP BY h.`Volunteer_PK`
AND h.Date >= schoolYearStartDate;

END$$
