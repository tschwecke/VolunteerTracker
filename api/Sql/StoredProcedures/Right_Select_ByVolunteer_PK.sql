DELIMITER $$

drop procedure if exists `Right_Select_ByVolunteer_PK`
$$
CREATE  PROCEDURE `Right_Select_ByVolunteer_PK`(IN volunteerPK int)
BEGIN

SELECT 
    ri.Right_PK,
    ri.Code,
    ri.sys_CreateDate,
    ri.sys_LastUpdate
FROM 
    `right` ri
    JOIN `roletoright` rtr ON ri.Right_PK = rtr.Right_PK
    JOIN `volunteer` v ON rtr.Role_PK = v.Role_PK
WHERE
    v.Volunteer_PK = volunteerPk;
    
END$$

