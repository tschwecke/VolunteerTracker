delimiter $$

INSERT INTO `role`
(`Name`)
VALUES
('Pending');$$



INSERT INTO `role`
(`Name`)
VALUES
('Active');$$



INSERT INTO `role`
(`Name`)
VALUES
('Administrator');$$



INSERT INTO `role`
(`Name`)
VALUES
('Inactive');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyVolunteerInfo');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOthersVolunteerInfo');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateMyVolunteerInfo');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersVolunteerInfo');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyProfile');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOthersProfile');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateMyProfile');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersProfile');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadInterestAreas');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateInterestAreas');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateInterestAreas');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteInterestAreas');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateMyInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateMyInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteMyInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOtherInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateOthersInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteOthersInterests');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateMyAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateMyAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteMyAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOthersAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateOthersAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteOthersAvailability');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateMyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateMyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteMyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOthersHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateOthersHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('DeleteOthersHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadMyFamilyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadOthersFamilyHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('ViewAdminTab');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateAccessToken');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadAllHours');$$



INSERT INTO `right`
(`Code`)
VALUES
('ViewHoursTab');$$



INSERT INTO `right`
(`Code`)
VALUES
('UpdateOthersVolunteerRole');$$



INSERT INTO `right`
(`Code`)
VALUES
('ReadAllVolunteerInfo');$$



INSERT INTO `right`
(`Code`)
VALUES
('CreateMyProfile');$$



INSERT INTO `roletoright`
(`Role_PK`, `Right_PK`)
SELECT ro.Role_PK, ri.Right_PK
FROM `role` ro
CROSS JOIN `right` ri
WHERE ro.Name = 'Pending'
AND ri.Code IN
('ReadMyVolunteerInfo',
'UpdateMyVolunteerInfo',
'ReadMyProfile',
'UpdateMyProfile',
'ReadInterestAreas',
'ReadMyInterests',
'CreateMyInterests',
'UpdateMyInterests',
'DeleteMyInterests',
'ReadMyAvailability',
'UpdateMyAvailability',
'CreateMyAvailability',
'DeleteMyAvailability',
'CreateAccessToken',
'ReadRights',
'CreateMyProfile');$$



INSERT INTO `roletoright`
(`Role_PK`, `Right_PK`)
SELECT ro.Role_PK, ri.Right_PK
FROM `role` ro
CROSS JOIN `right` ri
WHERE ro.Name = 'Active'
AND ri.Code IN
('ReadMyVolunteerInfo',
'UpdateMyVolunteerInfo',
'ReadMyProfile',
'UpdateMyProfile',
'ReadInterestAreas',
'ReadMyInterests',
'CreateMyInterests',
'UpdateMyInterests',
'DeleteMyInterests',
'ReadMyAvailability',
'UpdateMyAvailability',
'CreateMyAvailability',
'DeleteMyAvailability',
'ReadMyHours',
'CreateMyHours',
'DeleteMyHours',
'ReadMyFamilyHours',
'CreateAccessToken',
'ReadRights',
'ViewHoursTab',
'CreateMyProfile');$$



INSERT INTO `roletoright`
(`Role_PK`, `Right_PK`)
SELECT ro.Role_PK, ri.Right_PK
FROM `role` ro
CROSS JOIN `right` ri
WHERE ro.Name = 'Administrator';$$



INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Accounting/Finance', 1);$$



INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Administration', 2);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Fundraising', 3);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Grant Writing', 4);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Human Resources', 5);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Legal', 6);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Marketing', 7);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Technology', 8);$$


INSERT INTO `interestarea`
(`Name`,
`SortOrder`)
VALUES
('Other', 9);$$



INSERT INTO `volunteer`
(`FirstName`,
`LastName`,
`EmailAddress`,
`PasswordHash`,
`Salt`,
`Role_PK`)
VALUES
('Admin',
'Admin',
'admin@admin.com',
'LDRYekbWXJazZzNDN4tLtbrtkdY=',
'4ef0f568-71ec-4e06-9876-b771448d8097',
3);$$
