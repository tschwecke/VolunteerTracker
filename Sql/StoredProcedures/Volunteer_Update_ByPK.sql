DELIMITER $$

drop procedure if exists `Volunteer_Update_ByPK`
$$

CREATE PROCEDURE `Volunteer_Update_ByPK` (volunteer_PK int,
                                            firstName varchar(50),
                                            lastName varchar(50),
                                            emailAddress varchar(100),
                                            passwordHash varchar(100),
                                            salt varchar(36),
                                            role_PK int)    
BEGIN

UPDATE `volunteer`
SET
    `volunteer`.`FirstName` = firstName,
    `volunteer`.`LastName` = lastName,
    `volunteer`.`EmailAddress` = emailAddress,
    `volunteer`.`PasswordHash` = passwordHash,
    `volunteer`.`Salt` = salt,
    `volunteer`.`Role_PK` = role_PK
WHERE `volunteer`.`Volunteer_PK` = volunteer_PK;



END$$

