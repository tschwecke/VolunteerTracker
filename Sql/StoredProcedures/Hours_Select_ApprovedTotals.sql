DELIMITER $$
drop procedure if exists `Hours_Select_ApprovedTotals`
$$
CREATE PROCEDURE `Hours_Select_ApprovedTotals` ()
BEGIN

SELECT
    h.`Volunteer_PK`,
    SUM(NbrOfHours)
FROM `hours` h
WHERE h.`Status` = 'Approved'
GROUP BY h.`Volunteer_PK`;

END$$

