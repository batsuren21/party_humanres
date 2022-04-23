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
    $billObj=\Humanres\PersonHolidayBillClass::getInstance()->getRow(['bill_id'=>$_id]);
    
    $_empids=[$billObj->BillEmployeeID,$billObj->BillChiefEmployeeID, $billObj->BillHumanresEmployeeID];
    $_employeeList=\Humanres\EmployeeClass::getInstance()->getRowList(['_mainindex'=>'EmployeeID','employee_get_table'=>6,"employee_id"=>$_empids]);
    
    $personBillObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$billObj->BillEmployeeID));
    $personChiefObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$billObj->BillChiefEmployeeID));
    $personHumanresObj=\Humanres\PersonClass::getInstance(\Database::getParam($_employeeList,$billObj->BillHumanresEmployeeID));
    
    $now=new \DateTime();
    $_records['general'] = array("OfficeName"=>$officeObj->OfficeTitle,'nowdate'=>$now->format("Y оны m-р сарын d"));
    $_records['maindata'] = $billObj->getData();
    $_records['person'] =[
        'DepartmentName'=>$personBillObj->DepartmentName,
        'DepartmentFullName'=>$personBillObj->DepartmentFullName,
        'PositionName'=>$personBillObj->PositionName,
        'PositionFullName'=>$personBillObj->PositionFullName,
        'PersonLastName'=>$personBillObj->PersonLastName,
        'PersonFirstName'=>$personBillObj->PersonFirstName,
    ];
    $_records['personchief'] =[
        'PersonName'=>$personChiefObj->PersonLFName,
    ];
    $_records['personhumanres'] =[
        'PersonName'=>$personHumanresObj->PersonLFName,
    ];
    
    echo json_encode($_records);
    exit;