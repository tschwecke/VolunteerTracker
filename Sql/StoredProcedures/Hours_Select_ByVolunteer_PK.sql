DELIMITER $$
drop procedure if exists `Hours_Select_ByVolunteer_PK`
$$
CREATE PROCEDURE `Hours_Select_ByVolunteer_PK` (volunteer_PK int)
BEGIN

SELECT
    h.`Hours_PK`,
    h.`Volunteer_PK`,
    h.`InterestArea_PK`,
    h.`Date`,
    h.`NbrOfHours`,
    h.`Description`,
    h.`Status`
FROM `hours` h
WHERE h.Volunteer_PK = volunteer_PK;

END$$

