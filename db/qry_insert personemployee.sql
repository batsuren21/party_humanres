INSERT INTO `party_humanres`.`employee`(EmployeeID, EmployeePersonID, EmployeeDepartmentID,EmployeePositionID,EmployeeIsActive,EmployeeStartID,EmployeeStartDate)
SELECT NULL, T5.PersonID, T4.`PositionDepartmentID`,T4.`PositionID`, 1,1,'2022-04-20'
FROM `party_hrdb_old`.`department` T
INNER JOIN `party_hrdb_old`.`position` T1 ON T.DepartmentID=T1.`PositionDepartmentID`
AND T.DepartmentOrganID IN (28,297,299,301,303,305,307,308,311,313,314,316,318,1504,1842,1843,1844,1845,1846,1847,1848,1849,1850)
INNER JOIN `party_hrdb_old`.employee T2 ON T1.`PositionID`=T2.`EmployeePositionID`
INNER JOIN `party_hrdb_old`.person T3 ON T2.`EmployeePersonID`=T3.`PersonID`
INNER JOIN `party_humanres`.`position` T4 ON T4.`PositionFullName`=T1.`PositionFullName`
INNER JOIN `party_humanres`.person T5 ON T3.`PersonRegisterNumber`=T5.`PersonRegisterNumber`
;
