DELIMITER $$

drop procedure if exists `Right_Select_ByRole_PK`
$$
CREATE  PROCEDURE `Right_Select_ByRole_PK`(IN rolePK int)
BEGIN

SELECT 
    ri.Right_PK,
    ri.Code,
    ri.sys_CreateDate,
    ri.sys_LastUpdate
FROM 
    `right` ri
    JOIN roletoright rtr ON ri.Right_PK = rtr.Right_PK
WHERE
    rtr.Role_PK = rolePk;
    
END$$

