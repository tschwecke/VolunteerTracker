DELIMITER $$

drop procedure if exists `Volunteer_Select_ByInterestArea_PK`
$$
CREATE  PROCEDURE `Volunteer_Select_ByInterestArea_PK`(IN interestArea_PK int(11))
BEGIN

SELECT 
    v.Volunteer_PK,
    v.FirstName,
    v.LastName,
    v.EmailAddress,
    v.PasswordHash,
    v.Salt,
    v.FamilyId,
    v.PrimaryPhoneNbr,
    v.Role_PK
FROM 
    `volunteer` v 
INNER JOIN 
    `interest` i ON i.Volunteer_PK = v.Volunteer_PK
INNER JOIN 
    `role` r ON r.Role_PK = v.Role_PK
WHERE
    i.InterestArea_PK = interestArea_PK
AND
    r.Name = 'Active';

END$$

