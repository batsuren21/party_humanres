INSERT INTO `party_humanres`.`person` (PersonID,`PersonRegisterNumber`, PersonLastName, `PersonFirstName`, `PersonMiddleName`, `PersonBirthDate`, `PersonGender`, `PersonPartyEnterDate`,
`PersonAddressCityID`, `PersonAddressDistrictID`, `PersonAddressHorooID`, `PersonAddress`, `PersonAddressFull`)
SELECT NULL,T3.`PersonRegisterNumber`, T3.PersonLastName, T3.`PersonFirstName`, T3.`PersonMiddleName`, T3.`PersonBirthDate`, T3.`PersonGender`, CONCAT(T3.`PersonMemberYear`,'-01-01'),
T3.`PersonAddressCityID`, T3.`PersonAddressDistrictID`, T3.`PersonAddressKhorooID`, T3.`PersonAddressHome`, T3.`PersonAddressDescr`
FROM `party_hrdb_old`.`department` T
INNER JOIN `party_hrdb_old`.`position` T1 ON T.DepartmentID=T1.`PositionDepartmentID`
AND T.DepartmentOrganID IN (28,297,299,301,303,305,307,308,311,313,314,316,318,1504,1842,1843,1844,1845,1846,1847,1848,1849,1850)
INNER JOIN `party_hrdb_old`.employee T2 ON T1.`PositionID`=T2.`EmployeePositionID`
INNER JOIN `party_hrdb_old`.person T3 ON T2.`EmployeePersonID`=T3.`PersonID`
;
