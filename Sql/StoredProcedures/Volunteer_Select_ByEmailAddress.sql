DELIMITER $$

drop procedure if exists `Volunteer_Select_ByEmailAddress`
$$
CREATE  PROCEDURE `Volunteer_Select_ByEmailAddress`(IN emailAddress varchar(100))
BEGIN

SELECT 
    v.Volunteer_PK,
    v.FirstName,
    v.LastName,
    v.EmailAddress,
    v.PasswordHash,
    v.Salt,
    v.Role_PK
FROM 
    volunteer v
WHERE
    v.EmailAddress = emailAddress;
    
END$$

