--List of families with less than 30 volunteer hours
SELECT 
  v.FamilyId, 
  SUM(h.NbrOfHours) AS TotalHours,
  GROUP_CONCAT(DISTINCT CONCAT(v.FirstName, ' ', v.LastName) ORDER BY v.LastName, v.FirstName SEPARATOR ', '),
  GROUP_CONCAT(DISTINCT v.EmailAddress ORDER BY v.EmailAddress SEPARATOR ', ')
FROM volunteer v
LEFT OUTER JOIN hours h ON v.Volunteer_PK = h.Volunteer_PK
  AND h.Date > '2015-07-01'
  AND h.Status = 'Approved' 
WHERE v.Role_PK = 2
GROUP BY v.FamilyId
HAVING TotalHours < 30 OR TotalHours IS NULL
ORDER BY TotalHours
