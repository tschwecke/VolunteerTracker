DELIMITER $$

drop procedure if exists `Volunteer_Insert`
$$

CREATE PROCEDURE `Volunteer_Insert` (firstName varchar(50),
                                            lastName varchar(50),
                                            emailAddress varchar(100),
                                            passwordHash varchar(100),
                                            salt varchar(36),
                                            familyId int(11),
                                            primaryPhoneNbr varchar(25),
                                            role_PK int)    
BEGIN

INSERT INTO `volunteer`
(`volunteer`.`FirstName`,
`volunteer`.`LastName`,
`volunteer`.`EmailAddress`,
`volunteer`.`PasswordHash`,
`volunteer`.`Salt`,
`volunteer`.`FamilyId`,
`volunteer`.`PrimaryPhoneNbr`,
`volunteer`.`Role_PK`,
`volunteer`.`sys_CreateDate`,
`volunteer`.`sys_LastUpdate`)
VALUES
(
firstName,
lastName,
emailAddress,
passwordHash,
salt,
familyId,
primaryPhoneNbr,
role_PK,
NOW(),
NOW());

SELECT LAST_INSERT_ID() as 'NewId';

END$$

