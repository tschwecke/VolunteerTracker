DELIMITER $$

drop procedure if exists `Report_Select_FamilyHours`
$$
CREATE PROCEDURE `Report_Select_FamilyHours` (schoolYearStartDate date)
BEGIN

SELECT
  v.FamilyId, 
  SUM( h.NbrOfHours ) as 'NbrHours'
FROM 
  volunteer v
LEFT OUTER JOIN 
  hours h ON h.Volunteer_PK = v.Volunteer_PK AND h.Status = 'Approved'
WHERE
  v.Role_PK = 2
GROUP BY 
  v.FamilyId
ORDER BY 
  SUM( h.NbrOfHours )
AND 
  h.Date >= schoolYearStartDate;

END$$

