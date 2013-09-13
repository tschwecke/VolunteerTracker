DELIMITER $$
drop procedure if exists `Hours_Select_ByFamilyId`
$$
CREATE PROCEDURE `Hours_Select_ByFamilyId` (familyId int)
BEGIN

SELECT
    h.Hours_PK,
    h.Volunteer_PK,
    h.Date,
    h.NbrOfHours,
    h.Description,
    h.Status
FROM `hours` h
INNER JOIN `volunteer` v ON h.Volunteer_PK = v.Volunteer_PK
WHERE v.FamilyId = familyId;

END$$

