DELIMITER $$
drop procedure if exists `Interest_Insert`
$$
CREATE PROCEDURE `Interest_Insert` (volunteer_PK int,
                                        interestArea_PK int)
BEGIN
INSERT INTO `interest`
(`interest`.`Volunteer_PK`,
`interest`.`InterestArea_PK`,
`interest`.`sys_CreateDate`,
`interest`.`sys_LastUpdate`)
VALUES
(
volunteer_PK,
interestArea_PK,
NOW(),
NOW()
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

