<?php
    if(!\Office\Permission::isLoginPerson()){
        echo json_encode(array());
        exit;
    }
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    if($_officeid<1){
        echo json_encode(array());
        exit;
    }
    $officeObj=\Office\OfficeConfig::getOffice();
    if(!$officeObj->isExist()){
        echo json_encode(array());
        exit;
    }
    
    $_id=isset($_GET['id'])?$_GET['id']:0;
    if($_id<1){
        echo json_encode(array());
        exit;
    }
    $holidayObj=\Humanres\PersonHolidayClass::getInstance()->getRow(['holiday_id'=>$_id]);
    
    $_empids=[$holidayObj->HolidayEmployeeID,$holidayObj->HolidayChiefEmployeeID, $holidayObj->HolidayHumanresEmployeeID];
    $_employeeList=\Humanres\EmployeeClass::getInstance()->getRowList(['_mainindex'=>'EmployeeID','employee_get_table'=>6,"employee_id"=>$_empids]);
    
    $personHolidayObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$holidayObj->HolidayEmployeeID));
    $personChiefObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$holidayObj->HolidayChiefEmployeeID));
    $personHumanresObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$holidayObj->HolidayHumanresEmployeeID));
    
    $now=new \DateTime();
    $_records['general'] = array("OfficeName"=>$officeObj->OfficeTitle,'nowdate'=>$now->format("Y оны m-р сарын d"));
    $_records['holiday'] = $holidayObj->getData();
    $_records['person'] =[
        'DepartmentName'=>$personHolidayObj->DepartmentName,
        'DepartmentFullName'=>$personHolidayObj->DepartmentFullName,
        'PositionName'=>$personHolidayObj->PositionName,
        'PositionFullName'=>$personHolidayObj->PositionFullName,
        'PersonLastName'=>$personHolidayObj->PersonLastName,
        'PersonFirstName'=>$personHolidayObj->PersonFirstName,
    ];
    $_records['personchief'] =[
        'PersonName'=>$personChiefObj->PersonLFName,
    ];
    $_records['personhumanres'] =[
        'PersonName'=>$personHumanresObj->PersonLFName,
    ];
    
    echo json_encode($_records);
    exit;