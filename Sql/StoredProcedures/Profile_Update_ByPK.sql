DELIMITER $$
drop procedure if exists `Profile_Update_ByPK`
$$
CREATE PROCEDURE `Profile_Update_ByPK` (profile_PK int,
                                            familyId int,
                                            streetAddress varchar(100),
                                            city varchar(50),
                                            state varchar(2),
                                            zipCode varchar(10),
                                            primaryPhoneNbr varchar(25),
                                            primaryPhoneType varchar(15),
                                            bestTimePrimary varchar(15),
                                            secondaryPhoneNbr varchar(25),
                                            secondaryPhoneType varchar(15),
                                            bestTimeSecondary varchar(15),
                                            preferEmail int,
                                            preferPhone int,
                                            relationshipToOrganization varchar(25))
BEGIN

UPDATE `profile`
SET
    `FamilyId` = familyId,
    `StreetAddress` = streetAddress,
    `City` = city,
    `State` = state,
    `ZipCode` = zipCode,
    `PrimaryPhoneNbr` = primaryPhoneNbr,
    `PrimaryPhoneType` = primaryPhoneType,
    `BestTimePrimary` = bestTimePrimary,
    `SecondaryPhoneNbr` = secondaryPhoneNbr,
    `SecondaryPhoneType` = secondaryPhoneType,
    `BestTimeSecondary` = bestTimeSecondary,
    `PreferEmail` = preferEmail,
    `PreferPhone` = preferPhone,
    `RelationshipToOrganization` = relationshipToOrganization
WHERE`Profile_PK` = profile_PK;


END$$

