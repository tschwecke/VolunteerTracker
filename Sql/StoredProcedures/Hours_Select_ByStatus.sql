DELIMITER $$
drop procedure if exists `Hours_Select_ByStatus`
$$
CREATE PROCEDURE `Hours_Select_ByStatus` (status varchar(10))
BEGIN

SELECT
    h.`Hours_PK`,
    h.`Volunteer_PK`,
    h.`Date`,
    h.`NbrOfHours`,
    h.`Description`,
    h.`Status`
FROM `hours` h
WHERE h.Status = status
ORDER BY h.`Date`;

END$$

