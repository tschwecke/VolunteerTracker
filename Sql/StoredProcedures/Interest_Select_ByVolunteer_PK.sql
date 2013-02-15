DELIMITER $$
drop procedure if exists `Interest_Select_ByVolunteer_PK`
$$
CREATE PROCEDURE `Interest_Select_ByVolunteer_PK` (volunteer_PK int)
BEGIN

SELECT
`interest`.`Interest_PK`,
`interest`.`Volunteer_PK`,
`interest`.`InterestArea_PK`
FROM `interest`
WHERE `interest`.`Volunteer_PK` = volunteer_PK;


END$$

