DELIMITER $$
drop procedure if exists `Interest_Insert`
$$
CREATE PROCEDURE `Interest_Insert` (volunteer_PK int,
                                        interestArea_PK int)
BEGIN
INSERT INTO `interest`
(`interest`.`Volunteer_PK`,
`interest`.`InterestArea_PK`)
VALUES
(
volunteer_PK,
interestArea_PK
);

SELECT LAST_INSERT_ID() as 'NewId';

END$$

