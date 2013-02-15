
DELIMITER $$
drop procedure if exists `SelectedInterest_Get_ByVolunteer_PK`
$$
CREATE PROCEDURE `SelectedInterest_Get_ByVolunteer_PK` (volunteer_PK int)
BEGIN

SELECT 
    ia.InterestArea_PK,
    ia.Name,
    ia.SortOrder,
    i.Interest_PK as 'Selected',
    i.Interest_PK
FROM
    `InterestArea` ia
LEFT OUTER JOIN 
    `Interest` i ON ia.InterestArea_PK = i.InterestArea_PK AND i.Volunteer_PK = volunteer_PK
ORDER BY 
    ia.SortOrder;
    
END$$

