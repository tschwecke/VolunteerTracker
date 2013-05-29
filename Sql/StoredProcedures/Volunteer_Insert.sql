DELIMITER $$

drop procedure if exists `Volunteer_Insert`
$$

CREATE PROCEDURE `Volunteer_Insert` (firstName varchar(50),
                                            lastName varchar(50),
                                            emailAddress varchar(100),
                                            passwordHash varchar(100),
                                            salt varchar(36),
                                            role_PK int)    
BEGIN

INSERT INTO `volunteer`
(`volunteer`.`FirstName`,
`volunteer`.`LastName`,
`volunteer`.`EmailAddress`,
`volunteer`.`PasswordHash`,
`volunteer`.`Salt`,
`volunteer`.`Role_PK`)
VALUES
(
firstName,
lastName,
emailAddress,
passwordHash,
salt,
role_PK);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

