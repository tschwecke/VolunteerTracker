DELIMITER $$

drop procedure if exists `Report_Select_FamilyHours`
$$
CREATE PROCEDURE `Report_Select_FamilyHours` (schoolYearStartDate date)
BEGIN

SELECT
  v.FamilyId, 
  GROUP_CONCAT( DISTINCT v.EmailAddress ) as 'EmailAddress',
  GROUP_CONCAT( DISTINCT CONCAT_WS(' ', v.FirstName, v.LastName ) ORDER BY v.LastName, v.FirstName SEPARATOR ', ') as 'Names',
  SUM( h.NbrOfHours ) as 'NbrHours'
FROM 
  volunteer v
LEFT OUTER JOIN 
  hours h ON h.Volunteer_PK = v.Volunteer_PK AND h.Status = 'Approved' AND h.Date >= schoolYearStartDate
WHERE
  v.Role_PK = 2
AND
  v.FamilyId > 999
AND
  v.FamilyId < 9999
GROUP BY 
  v.FamilyId
ORDER BY 
  SUM( h.NbrOfHours );

END$$

