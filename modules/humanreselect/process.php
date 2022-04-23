<?php
    require_once("../../libraries/connect.php");
    $__con = new Database();
    if(!\Office\Permission::isLoginPerson()){
        $result['_state'] = false;
        $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    }
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    if($_officeid<1){
        $result['_state'] = false;
        $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    }

    $action = isset($_GET['action']) ? $_GET['action'] : "";
    
    switch ($action) {
        case "adddepartment":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['department'])?$_POST['department']:array();
            $_errors=array();
            
            $mainObj=\Humanres\DepartmentClass::getInstance();
            
            $_data=array_merge($_param,
                array("DepartmentPeriodID"=>0,
                    "DepartmentIsActive"=>1,
                    "DepartmentAreaIDs"=>(isset($_POST['areaids'])?implode(",", $_POST['areaids']):""),
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));    
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "editdepartment":
            $_param=isset($_POST['department'])?$_POST['department']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\DepartmentClass::getInstance()->getRow(array("department_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,[
                "DepartmentAreaIDs"=>(isset($_POST['areaids'])?implode(",", array_filter($_POST['areaids'])):""),
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removedepartment":
            $_param=isset($_POST['department'])?$_POST['department']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\DepartmentClass::getInstance()->getRow(array("department_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Устгах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addposition":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['position'])?$_POST['position']:array();
            $_errors=array();
            
            $mainObj=\Humanres\PositionClass::getInstance();
            $is_error=$mainObj->validateAddRow($_param, 2);
            if(!$is_error){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_param['PositionDepartmentID']]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж сонгогдоогүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_data=array_merge($_param,
                array(
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_position=$mainObj->getRowCount(["position_departmentid"=>$departmentObj->DepartmentID]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountPosition"=>$_count_position,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "editposition":
            $_param=isset($_POST['position'])?$_POST['position']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PositionClass::getInstance()->getRow(array("position_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeposition":
            $_param=isset($_POST['position'])?$_POST['position']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PositionClass::getInstance()->getRow(array("position_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Устгах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$mainObj->PositionDepartmentID]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_position=$mainObj->getRowCount(["position_departmentid"=>$departmentObj->DepartmentID]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountPosition"=>$_count_position,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addemployee":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['employee'])?$_POST['employee']:array();
            $_param_person=isset($_POST['person'])?$_POST['person']:array();
            $_errors=array();
            
            $mainObj=\Humanres\EmployeeClass::getInstance();
            
            $__con->beginCommit();
            
            $_personID=0;
            if(!isset($_param_person['PersonID'])){
                $personObj=\Humanres\PersonClass::getInstance();
                $_personID=$personObj->addRow(array_merge($_param_person,[
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ]));
                if($_personID<1){
                    $result['_state'] = false;
                    $result['_errors'] = $personObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
            }else{
                $_personID=$_param_person['PersonID'];
            }
            $_data=array_merge($_param,
                array(
                    "EmployeePersonID"=>$_personID,
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_personID]);
            
            $_count_employee=$mainObj->getRowCount(["employee_personid"=>$personObj->PersonID]);
            
            $res=$personObj->updateRow(array(
                "PersonCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            if(isset($_FILES['filesource'])){
                $res=$personObj->uploadFile($_FILES['filesource']);
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $mainObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
            }
            
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_param['EmployeeDepartmentID']]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж сонгогдоогүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$_param['EmployeePositionID']]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал сонгогдоогүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$mainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$mainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
            $res=$positionObj->updateRow(array(
                "PositionCountEmployeed"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $positionObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "editemployee":
            $_param=isset($_POST['employee'])?$_POST['employee']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\EmployeeClass::getInstance()->getRow(array("employee_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$mainObj->EmployeeDepartmentID]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$mainObj->EmployeePositionID]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            if(isset($_param['EmployeeQuitID']) && $_param['EmployeeQuitID']>0){
                $_param=array_merge($_param,['EmployeeIsActive'=>0]);
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(isset($_param['EmployeeQuitID']) && $_param['EmployeeQuitID']>0){
                $_count_employee=$mainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
                $res=$departmentObj->updateRow(array(
                    "DepartmentCountEmployee"=>$_count_employee,
                    "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
                ));
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $departmentObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                
                $_count_employee=$mainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
                $res=$positionObj->updateRow(array(
                    "PositionCountEmployeed"=>$_count_employee,
                    "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
                ));
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $positionObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeemployee":
            $_param=isset($_POST['employee'])?$_POST['employee']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\EmployeeClass::getInstance()->getRow(array("employee_id"=> $_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Устгах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$mainObj->EmployeePersonID]);
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$mainObj->EmployeeDepartmentID]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$mainObj->EmployeePositionID]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_employee=$mainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$mainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
            $res=$positionObj->updateRow(array(
                "PositionCountEmployeed"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $positionObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_lastEmployeeObj=$mainObj->getRow(["employee_personid"=>$personObj->PersonID,'orderby'=>"EmployeeID desc","rowstart"=>0,"rowlength"=>1]);
            $_count_employee=$mainObj->getRowCount(["employee_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $positionObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "removeemployeequit":
            $_param=isset($_POST['employee'])?$_POST['employee']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\EmployeeClass::getInstance()->getRow(array("employee_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$mainObj->EmployeeDepartmentID]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$mainObj->EmployeePositionID]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $_param=array_merge($_param,[
                'EmployeeIsActive'=>1,
                'EmployeeQuitID'=>"",
                'EmployeeQuitSubID'=>"",
                'EmployeeQuitDate'=>"",
                'EmployeeQuitOrderNo'=>"",
                'EmployeeQuitOrderDate'=>"",
            ]);
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            
            $_count_employee=$mainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$mainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
            $res=$positionObj->updateRow(array(
                "PositionCountEmployeed"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $positionObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "editperson":
            $_param=isset($_POST['person'])?$_POST['person']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:[];
            $_errors=array();
             
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            if(isset($_param['PersonRegisterNumber'])){
                $reg_num=mb_strtoupper($_param['PersonRegisterNumber'],"UTF-8");
                if(mb_strtoupper($_param['PersonRegisterNumber'],"UTF-8")!=mb_strtoupper($mainObj->PersonRegisterNumber,"UTF-8")){
                    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_registernumber'=>$_param['PersonRegisterNumber']]);
                    if($personObj->isExist()){
                        $result['_state'] = false;
                        $result['_errors'] = array("field"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Регистрийн дугаар давхцаж байна","person[PersonRegisterNumber]")));
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit;
                    }
                }
            }
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $__con->beginCommit();
            
            if(isset($_param["PersonBirthCityID"]) && isset($_param["PersonBirthDistrictID"])){
                $cityObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonBirthCityID"]]);
                $districtObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonBirthDistrictID"]]);
                $str_address=$cityObj->AreaName.", ".$districtObj->AreaName;
                $_param=array_merge($_param,["PersonBirthPlace"=>$str_address]);
            }
            if(isset($_param["PersonBasicCityID"]) && isset($_param["PersonBasicDistrictID"])){
                $cityObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonBasicCityID"]]);
                $districtObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonBasicDistrictID"]]);
                $str_address=$cityObj->AreaName.", ".$districtObj->AreaName;
                $_param=array_merge($_param,["PersonBasicPlace"=>$str_address]);
            }
            if(isset($_param["PersonAddressCityID"]) && isset($_param["PersonAddressDistrictID"]) && isset($_param["PersonAddress"])){
                $cityObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonAddressCityID"]]);
                $districtObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonAddressDistrictID"]]);
                $horooObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param["PersonAddressHorooID"]]);
                $str_address=$cityObj->AreaName.", ".$districtObj->AreaName.", ".$horooObj->AreaName.", ".$_param["PersonAddress"];
                $_param=array_merge($_param,["PersonAddressFull"=>$str_address]);
            }
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            
            if(!$res && (!isset($_POST['isdelimg']) || $_POST['isdelimg']!=1)){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(isset($_POST['isdelimg']) && $_POST['isdelimg']== 1){
                $res=$mainObj->removeFile();
                if(!$res){
                    $json['state'] = false;
                    $json['error'] = $mainObj->Error;
                    echo json_encode($json);
                    break;
                }
            }
            
            if(isset($_FILES['filesource'])){
                if($mainObj->PersonImageSource!="" && (!isset($_POST['isdelimg']) || $_POST['isdelimg']!= 1)){
                    $res=$mainObj->removeFile(false);
                    if(!$res){
                        $json['state'] = false;
                        $json['error'] = $mainObj->Error;
                        echo json_encode($json);
                        break;
                    }
                }
                $res=$mainObj->uploadFile($_FILES['filesource']);
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $mainObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "addeducation":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['education'])?$_POST['education']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_errors=array();
            
            $mainObj=\Humanres\PersonEducationClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "EducationPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_education=$mainObj->getRowCount(["education_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountEducation"=>$_count_education,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updateeducation":
            $_param=isset($_POST['education'])?$_POST['education']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonEducationClass::getInstance()->getRow(array("education_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeeducation":
            $_param=isset($_POST['education'])?$_POST['education']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonEducationClass::getInstance()->getRow(array("education_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->EducationPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_education=$mainObj->getRowCount(["education_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountEducation"=>$_count_education,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addstudy":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['study'])?$_POST['study']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            if(!isset($_param['StudyDirSubID'])){
                $_param=array_merge($_param,['StudyDirSubID'=>""]);
            }
            if(!isset($_param['StudyDirSub1ID'])){
                $_param=array_merge($_param,['StudyDirSub1ID'=>""]);
            }
            
            $mainObj=\Humanres\PersonStudyClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "StudyPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_study=$mainObj->getRowCount(["study_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountStudy"=>$_count_study,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatestudy":
            $_param=isset($_POST['study'])?$_POST['study']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonStudyClass::getInstance()->getRow(array("study_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['StudyDirSubID'])){
                $_param=array_merge($_param,['StudyDirSubID'=>""]);
            }
            if(!isset($_param['StudyDirSub1ID'])){
                $_param=array_merge($_param,['StudyDirSub1ID'=>""]);
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removestudy":
            $_param=isset($_POST['study'])?$_POST['study']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonStudyClass::getInstance()->getRow(array("study_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->StudyPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_study=$mainObj->getRowCount(["study_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountStudy"=>$_count_study,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addlanguage":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['language'])?$_POST['language']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonLanguageClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "LanguagePersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_language=$mainObj->getRowCount(["language_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountLanguage"=>$_count_language,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatelanguage":
            $_param=isset($_POST['language'])?$_POST['language']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonLanguageClass::getInstance()->getRow(array("language_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removelanguage":
            $_param=isset($_POST['language'])?$_POST['language']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonLanguageClass::getInstance()->getRow(array("language_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->LanguagePersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_language=$mainObj->getRowCount(["language_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountLanguage"=>$_count_language,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addedurank":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['edurank'])?$_POST['edurank']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonEduRankClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "EduPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_edurank=$mainObj->getRowCount(["edurank_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountDegree"=>$_count_edurank,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updateedurank":
            $_param=isset($_POST['edurank'])?$_POST['edurank']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonEduRankClass::getInstance()->getRow(array("edurank_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeedurank":
            $_param=isset($_POST['edurank'])?$_POST['edurank']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonEduRankClass::getInstance()->getRow(array("edurank_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->EduPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_edurank=$mainObj->getRowCount(["edurank_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountDegree"=>$_count_edurank,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addaward":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['award'])?$_POST['award']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonAwardClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "AwardPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_award=$mainObj->getRowCount(["award_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountAward"=>$_count_award,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updateaward":
            $_param=isset($_POST['award'])?$_POST['award']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonAwardClass::getInstance()->getRow(array("award_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['AwardRefSubID'])){
                $_param=array_merge($_param,['AwardRefSubID'=>0]);
            }
            if(!isset($_param['AwardOrganTitle'])){
                $_param=array_merge($_param,['AwardOrganTitle'=>""]);
            }
            if(!isset($_param['AwardTitle'])){
                $_param=array_merge($_param,['AwardTitle'=>""]);
            }
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeaward":
            $_param=isset($_POST['award'])?$_POST['award']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonAwardClass::getInstance()->getRow(array("award_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->AwardPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_award=$mainObj->getRowCount(["award_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountAward"=>$_count_award,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addjob":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['job'])?$_POST['job']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            if(isset($_param['JobIsNow']) && $_param['JobIsNow']){
                if(isset($_param['JobOrganID']) && $_param['JobOrganID']==6){
                    $result['_state'] = false;
                    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцсон ангилал одоо ажиллаж байгаа хэлбэртэй байх боломжгүй.")));
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                $jobCount=\Humanres\PersonJobClass::getInstance()->getRowCount([
                    "job_personid"=>$personObj->PersonID,
                    "job_isnow"=>1
                ]);
                if($jobCount>0){
                    $result['_state'] = false;
                    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хөдөлмөр эрхлэлтэд одоо ажиллаж байгаа төлөвтэй бүртгэл байгаа учир бүртгэх боломжгүй.")));
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;  
                }
            }
            if(isset($_param['JobOrganID']) && $_param['JobOrganID']==6){
                if(isset($_param['JobOrganTitle'])) $_param['JobOrganTitle']="-";
                else $_param=array_merge($_param,['JobOrganTitle'=>"-"]);
                if(isset($_param['JobDepartmentTitle'])) $_param['JobDepartmentTitle']="-";
                else $_param=array_merge($_param,['JobDepartmentTitle'=>"-"]);
                if(isset($_param['JobStartOrder'])) $_param['JobStartOrder']="-";
                else $_param=array_merge($_param,['JobStartOrder'=>"-"]);
                if(isset($_param['JobQuitReason'])) $_param['JobQuitReason']="-";
                else $_param=array_merge($_param,['JobQuitReason'=>"-"]);
                if(isset($_param['JobQuitOrder'])) $_param['JobQuitOrder']="-";
                else $_param=array_merge($_param,['JobQuitOrder'=>"-"]);
                if(isset($_param['JobQuitOrderDate'])) $_param['JobQuitOrderDate']="";
                else $_param=array_merge($_param,['JobQuitOrderDate'=>""]);
            }
            $_errors=array();
            
            $_time=['year'=>0,'month'=>0, 'day'=>0];
            if(!isset($_param['JobDateStart']) || $_param['JobDateStart']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажиллаж эхэлсэн хугацаа хоосон байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }else{
                if(isset($_param['JobIsNow']) && $_param['JobIsNow']){
                    $_time=['year'=>0,'month'=>0, 'day'=>0];
                }else{
                    if(!isset($_param['JobDateQuit']) || $_param['JobDateQuit']==""){
                        $result['_state'] = false;
                        $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажлаас гарсан хугацаа хоосон байна.")));
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit;
                    }else{
                        $date1 = new \DateTime($_param['JobDateStart']);
                        $date2 = new \DateTime($_param['JobDateQuit']);
                        if($date1>$date2){
                            $result['_state'] = false;
                            $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажилд орсон огноо ажлаас гарсан огнооны урд байх ёстой.")));
                            header("Content-type: application/json");
                            echo json_encode($result);
                            exit;
                        }
                        $_time=\System\Util::getDaysDiff($_param['JobDateStart'], $_param['JobDateQuit']);
                    }
                }
                
            }
            
            $mainObj=\Humanres\PersonJobClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "JobPersonID"=>$_editparam['id'],
                    "JobWorkedYear"=>$_time['year'],
                    "JobWorkedMonth"=>$_time['month'],
                    "JobWorkedDay"=>$_time['day'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_job=$mainObj->getRowCount(["job_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountJob"=>$_count_job,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $res=$personObj->updateYears();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatejob":
            $_param=isset($_POST['job'])?$_POST['job']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonJobClass::getInstance()->getRow(array("job_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->JobPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(isset($_param['JobIsNow']) && $_param['JobIsNow']){
                if(isset($_param['JobOrganID']) && $_param['JobOrganID']==6){
                    $result['_state'] = false;
                    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцсон ангилал одоо ажиллаж байгаа хэлбэртэй байх боломжгүй.")));
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                if(isset($_param['JobIsNow']) && $_param['JobIsNow']){
                    $jobCount=\Humanres\PersonJobClass::getInstance()->getRowCount([
                        "job_personid"=>$personObj->PersonID,
                        "job_isnow"=>1,
                        "job_notid"=>$mainObj->JobID
                    ]);
                    if($jobCount>0){
                        $result['_state'] = false;
                        $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хөдөлмөр эрхлэлтэд одоо ажиллаж байгаа төлөвтэй бүртгэл байгаа учир бүртгэх боломжгүй.")));
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit;
                    }
                }
                
                if(isset($_param['JobDateQuit'])){
                    $_param['JobDateQuit']="";
                }else{
                    $_param=array_merge($_param,['JobDateQuit'=>""]);
                }
                if(isset($_param['JobQuitReason'])){
                    $_param['JobQuitReason']="";
                }else{
                    $_param=array_merge($_param,['JobQuitReason'=>""]);
                }
                if(isset($_param['JobQuitOrder'])){
                    $_param['JobQuitOrder']="";
                }else{
                    $_param=array_merge($_param,['JobQuitOrder'=>""]);
                }
                if(isset($_param['JobQuitOrderDate'])){
                    $_param['JobQuitOrderDate']="";
                }else{
                    $_param=array_merge($_param,['JobQuitOrderDate'=>""]);
                }
                
            }
            if(isset($_param['JobOrganID']) && $_param['JobOrganID']==6){
                if(isset($_param['JobOrganTitle'])) $_param['JobOrganTitle']="-";
                else $_param=array_merge($_param,['JobOrganTitle'=>"-"]);
                if(isset($_param['JobDepartmentTitle'])) $_param['JobDepartmentTitle']="-";
                else $_param=array_merge($_param,['JobDepartmentTitle'=>"-"]);
                if(isset($_param['JobStartOrder'])) $_param['JobStartOrder']="-";
                else $_param=array_merge($_param,['JobStartOrder'=>"-"]);
                if(isset($_param['JobQuitReason'])) $_param['JobQuitReason']="-";
                else $_param=array_merge($_param,['JobQuitReason'=>"-"]);
                if(isset($_param['JobQuitOrder'])) $_param['JobQuitOrder']="-";
                else $_param=array_merge($_param,['JobQuitOrder'=>"-"]);
                if(isset($_param['JobQuitOrderDate'])) $_param['JobQuitOrderDate']="";
                else $_param=array_merge($_param,['JobQuitOrderDate'=>""]);
            }
            $_time=['year'=>0,'month'=>0, 'day'=>0];
            if(!isset($_param['JobDateStart']) || $_param['JobDateStart']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажиллаж эхэлсэн хугацаа хоосон байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }else{
                if(isset($_param['JobIsNow']) && $_param['JobIsNow']){
                    $_time=['year'=>0,'month'=>0, 'day'=>0];
                }else{
                    if(!isset($_param['JobDateQuit']) || $_param['JobDateQuit']==""){
                        $result['_state'] = false;
                        $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажлаас гарсан хугацаа хоосон байна.")));
                        header("Content-type: application/json");
                        echo json_encode($result);
                        exit;
                    }else{
                        $date1 = new \DateTime($_param['JobDateStart']);
                        $date2 = new \DateTime($_param['JobDateQuit']);
                        if($date1>$date2){
                            $result['_state'] = false;
                            $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажилд орсон огноо ажлаас гарсан огнооны урд байх ёстой.")));
                            header("Content-type: application/json");
                            echo json_encode($result);
                            exit;
                        }
                        $_time=\System\Util::getDaysDiff($_param['JobDateStart'], $_param['JobDateQuit']);
                    }
                }
                
            }
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,[
                "JobWorkedYear"=>$_time['year'],
                "JobWorkedMonth"=>$_time['month'],
                "JobWorkedDay"=>$_time['day'],
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $res=$personObj->updateYears();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $res=$personObj->updateJobAbsent();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Ажилласан жил завсардсан байдал засах боломжгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removejob":
            $_param=isset($_POST['job'])?$_POST['job']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonJobClass::getInstance()->getRow(array("job_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->JobPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_job=$mainObj->getRowCount(["job_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountJob"=>$_count_job,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $res=$personObj->updateYears();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addpunishment":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['punishment'])?$_POST['punishment']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_errors=array();
            
            $mainObj=\Humanres\PersonPunishmentClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "PunishmentPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_punishment=$mainObj->getRowCount(["punishment_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountPunishment"=>$_count_punishment,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatepunishment":
            $_param=isset($_POST['punishment'])?$_POST['punishment']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonPunishmentClass::getInstance()->getRow(array("punishment_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            if(isset($_param['PunishmentRefID']) && $_param['PunishmentRefID']!=2){
                if(!isset($_param['PunishmentTime'])){
                    $_param=array_merge($_param,["PunishmentTime"=>""]);
                }
                if(!isset($_param['PunishmentPercent'])){
                    $_param=array_merge($_param,["PunishmentPercent"=>""]);
                }
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removepunishment":
            $_param=isset($_POST['punishment'])?$_POST['punishment']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonPunishmentClass::getInstance()->getRow(array("punishment_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->PunishmentPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_punishment=$mainObj->getRowCount(["punishment_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountPunishment"=>$_count_punishment,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addtrip":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['trip'])?$_POST['trip']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonTripClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "TripPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_trip=$mainObj->getRowCount(["trip_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountTrip"=>$_count_trip,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatetrip":
            $_param=isset($_POST['trip'])?$_POST['trip']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonTripClass::getInstance()->getRow(array("trip_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removetrip":
            $_param=isset($_POST['trip'])?$_POST['trip']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonTripClass::getInstance()->getRow(array("trip_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->TripPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_trip=$mainObj->getRowCount(["trip_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountTrip"=>$_count_trip,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addsalary":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['salary'])?$_POST['salary']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonSalaryClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "SalaryPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_salary=$mainObj->getRowCount(["salary_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountSalary"=>$_count_salary,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatesalary":
            $_param=isset($_POST['salary'])?$_POST['salary']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonSalaryClass::getInstance()->getRow(array("salary_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removesalary":
            $_param=isset($_POST['salary'])?$_POST['salary']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonSalaryClass::getInstance()->getRow(array("salary_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->SalaryPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_salary=$mainObj->getRowCount(["salary_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountSalary"=>$_count_salary,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addfamily":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['family'])?$_POST['family']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonFamilyClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "FamilyPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_family=$mainObj->getRowCount(["family_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountFamily"=>$_count_family,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatefamily":
            $_param=isset($_POST['family'])?$_POST['family']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonFamilyClass::getInstance()->getRow(array("family_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['FamilyJobOrgan'])){
                $_param=array_merge($_param,['FamilyJobOrgan'=>""]);
            }
            if(!isset($_param['FamilyJobPosition'])){
                $_param=array_merge($_param,['FamilyJobPosition'=>""]);
            }
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removefamily":
            $_param=isset($_POST['family'])?$_POST['family']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonFamilyClass::getInstance()->getRow(array("family_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->FamilyPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_family=$mainObj->getRowCount(["family_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountFamily"=>$_count_family,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addrelation":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['relation'])?$_POST['relation']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonRelationClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "RelationPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_relation=$mainObj->getRowCount(["relation_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountRelate"=>$_count_relation,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updaterelation":
            $_param=isset($_POST['relation'])?$_POST['relation']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonRelationClass::getInstance()->getRow(array("relation_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['RelationJobOrgan'])){
                $_param=array_merge($_param,['RelationJobOrgan'=>""]);
            }
            if(!isset($_param['RelationJobPosition'])){
                $_param=array_merge($_param,['RelationJobPosition'=>""]);
            }
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removerelation":
            $_param=isset($_POST['relation'])?$_POST['relation']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonRelationClass::getInstance()->getRow(array("relation_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->RelationPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_relation=$mainObj->getRowCount(["relation_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountRelate"=>$_count_relation,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "transferemployee":
            $_param=isset($_POST['employee'])?$_POST['employee']:array();
            $_param_start=isset($_POST['employee_start'])?$_POST['employee_start']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\EmployeeClass::getInstance()->getRow(array("employee_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$mainObj->EmployeePersonID]);
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$mainObj->EmployeeDepartmentID]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$mainObj->EmployeePositionID]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал олдсонгүй.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->validateUpdateRow($_param,2);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            if(isset($_param['EmployeeQuitID']) && $_param['EmployeeQuitID']>0){
                $_param=array_merge($_param,['EmployeeIsActive'=>0]);
            }
            $_old_EmployeeID=$mainObj->EmployeeID;
            
            $__con->beginCommit();
            
            /***ЧӨЛӨӨЛӨЛТ***/
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            
            if(isset($_param['EmployeeQuitID']) && $_param['EmployeeQuitID']>0){
                $_count_employee=$mainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
                $res=$departmentObj->updateRow(array(
                    "DepartmentCountEmployee"=>$_count_employee,
                    "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
                ));
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $departmentObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                
                $_count_employee=$mainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
                $res=$positionObj->updateRow(array(
                    "PositionCountEmployeed"=>$_count_employee,
                    "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
                ));
                if(!$res){
                    $result['_state'] = false;
                    $result['_errors'] = $positionObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
            }
            /***ТОМИЛОЛТ***/
            $newMainObj=\Humanres\EmployeeClass::getInstance();
            
            $_data=array_merge($_param_start,
                array(
                    "EmployeePersonID"=>$mainObj->EmployeePersonID,
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            $_new_EmployeeID=$newMainObj->addRow($_data);
            if($_new_EmployeeID<1){
                $result['_state'] = false;
                $result['_errors'] = $newMainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$newMainObj->getRowCount(["employee_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonEmployeeID"=>$_new_EmployeeID,
                "PersonCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_param_start['EmployeeDepartmentID']]);
            if(!$departmentObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэгж сонгогдоогүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$_param_start['EmployeePositionID']]);
            if(!$positionObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Албан тушаал сонгогдоогүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$newMainObj->getRowCount(["employee_departmentid"=>$departmentObj->DepartmentID,"employee_isactive"=>1]);
            $res=$departmentObj->updateRow(array(
                "DepartmentCountEmployee"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $departmentObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_employee=$newMainObj->getRowCount(["employee_positionid"=>$positionObj->PositionID,"employee_isactive"=>1]);
            $res=$positionObj->updateRow(array(
                "PositionCountEmployeed"=>$_count_employee,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $positionObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $res=$mainObj->transferAllData($_old_EmployeeID,$_new_EmployeeID);
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "addposrank":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['pos'])?$_POST['pos']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg && $_SESSION[SESSSYSINFO]->PersonID!=$personObj->PersonID){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $mainObj=\Humanres\PersonPosRankClass::getInstance();
            $_data=array_merge($_param,
                array(
                    "PosPersonID"=>$_editparam['id'],
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_posrank=$mainObj->getRowCount(["posrank_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountPosRank"=>$_count_posrank,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updateposrank":
            $_param=isset($_POST['pos'])?$_POST['pos']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonPosRankClass::getInstance()->getRow(array("posrank_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeposrank":
            $_param=isset($_POST['pos'])?$_POST['pos']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonPosRankClass::getInstance()->getRow(array("posrank_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->PosPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_posrank=$mainObj->getRowCount(["posrank_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountPosRank"=>$_count_posrank,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addrefholiday":
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            
            $_errors=array();
            
            $mainObj= \Humanres\RefHolidayClass::getInstance();
            $is_error=$mainObj->validateAddRow($_param, 2);
            if(!$is_error){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            $_days=[];
            
            $date=new \DateTime($_param['RefHolidayDateStart']);
            $_param['RefHolidayYear']=$date->format("Y");
            $_days=\System\Util::getDatesFromRange($_param['RefHolidayDateStart'], $_param['RefHolidayDateEnd']);
            $_data=array_merge($_param, array(
                "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
            ));
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
           
            $daysObj=\Humanres\RefHolidayDayClass::getInstance();
            foreach ($_days as $r){
                $tmpDay=new \DateTime($r);
                $_tmpid=$daysObj->addRow([
                    'RefDayHolidayID'=>$_id,
                    'RefDayYear'=>$tmpDay->format("Y"),
                    'RefDayDate'=>$tmpDay->format("Y-m-d")
                ]);
                if($_tmpid<1){
                    $result['_state'] = false;
                    $result['_errors'] = $daysObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                
            }
           
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "editrefholiday":
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\RefHolidayClass::getInstance()->getRow(array("refholiday_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $_param=array_merge($_param,['RefHolidayType'=>$mainObj->RefHolidayType]);
            $res=$mainObj->updateRow(array_merge($_param,[
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
            ]));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_days=[];
            
            $date=new \DateTime($_param['RefHolidayDateStart']);
            $_param['RefHolidayYear']=$date->format("Y");
            $_days=\System\Util::getDatesFromRange($_param['RefHolidayDateStart'], $_param['RefHolidayDateEnd']);
            
            
            $daysObj=\Humanres\RefHolidayDayClass::getInstance();
            
            $res=$daysObj->deleteRow(['cond'=>['RefDayHolidayID="'.$mainObj->RefHolidayID.'"']]);
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $daysObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            foreach ($_days as $r){
                $tmpDay=new \DateTime($r);
                $_tmpid=$daysObj->addRow([
                    'RefDayHolidayID'=>$mainObj->RefHolidayID,
                    'RefDayYear'=>$tmpDay->format("Y"),
                    'RefDayDate'=>$tmpDay->format("Y-m-d")
                ]);
                if($_tmpid<1){
                    $result['_state'] = false;
                    $result['_errors'] = $daysObj->Error;
                    header("Content-type: application/json");
                    echo json_encode($result);
                    exit;
                }
                
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removerefholiday":
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\RefHolidayClass::getInstance()->getRow(array("refholiday_id"=>$_param['id']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addholiday":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$personObj->PersonEmployeeID]);
            if(!$employeeObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['HolidayDateStart'])){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Эхлэх огноо сонгоогүй байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_HolidayIsFirstYear=isset($_param['HolidayIsFirstYear'])?$_param['HolidayIsFirstYear']:0;
            $activeJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
                "job_personid"=>$personObj->PersonID,
                "job_isnow"=>1,
                "rowstart"=>0,
                "rowlength"=>1,
            ]);
            
            $_cheifEmployeeObj=\Humanres\EmployeeClass::getInstance()->getRow([
                'employee_get_table'=>6,
                'department_parentid'=>0,
                'department_typeid'=>5,
                'position_typeid'=>3,
                'rowstart'=>0,
                'rowlength'=>1,
                "orderby"=>['EmployeeID desc']
            ]);
            
            $privObj=new \System\SystemPrivClass();
            $_personids=$privObj->getPersonListByPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_humanresEmployeeObj=\Humanres\EmployeeClass::getInstance()->getRow([
                'employee_personid'=>$_personids,
                'employee_isactive'=>1,
                'rowstart'=>0,
                'rowlength'=>1,
                "orderby"=>['EmployeeID desc']
            ]);
            
            $PersonWorkYearAll=$personObj->PersonWorkYearAll;
            $PersonWorkMonthAll=$personObj->PersonWorkMonthAll;
            $PersonWorkDayAll=$personObj->PersonWorkDayAll;
            $now = new DateTime();
            
            $_all_daymonth= 0;
            $_atg_daymonth= 0;
            $date_start=new \DateTime($employeeObj->EmployeeStartDate);
            if($activeJobObj->isExist()){
                $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
                $tmpDay=$personObj->PersonWorkDayAll==""?0:($personObj->PersonWorkDayAll+$_time['day'])%30;
                
                $tmpMonth=$personObj->PersonWorkMonthAll==""?$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30):$personObj->PersonWorkMonthAll+$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30);
                
                $PersonWorkYearAll=$personObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthAll=($tmpMonth%12);
                $PersonWorkDayAll=$tmpDay;
                
                $_all_daymonth= floatval(($PersonWorkYearAll*12 + $PersonWorkMonthAll).".".$PersonWorkDayAll);
                
                if($activeJobObj->JobOrganTypeID==1 && $activeJobObj->JobOrganSubID==1){
                    $tmpDay=$personObj->PersonWorkDayOrgan==""?0:($personObj->PersonWorkDayOrgan+$_time['day'])%30;
                    $tmpMonth=$personObj->PersonWorkMonthOrgan==""?$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30):$personObj->PersonWorkMonthOrgan+$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30);
                    $PersonWorkYearOrgan=$personObj->PersonWorkYearOrgan==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearOrgan+$_time['year']+floor($tmpMonth/12);
                    $PersonWorkMonthOrgan=$tmpMonth%12;
                    $PersonWorkDayOrgan=$tmpDay;
                    
                    $_atg_daymonth= floatval(($PersonWorkYearOrgan*12 + $PersonWorkMonthOrgan).".".$PersonWorkDayOrgan);
                }
                $date_start=new \DateTime($activeJobObj->JobDateStart);
            }
            $lastOrganJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
                "job_personid"=>$personObj->PersonID,
                "job_isabsent"=>1,
                "job_organsubid"=>1,
                "orderby"=>['JobDateStart'],
                "rowstart"=>0,
                "rowlength"=>1,
            ]);
            if($lastOrganJobObj->isExist()){
                $date_start=new \DateTime($lastOrganJobObj->JobDateStart);
            }
            $_additionList1=\Humanres\RefHolidayAdditionClass::getInstance()->getRowList(["holidayaddition_type"=>1,"orderby"=>"RefAdditionOrder"]);
            $_additionList2=\Humanres\RefHolidayAdditionClass::getInstance()->getRowList(["holidayaddition_type"=>2,"orderby"=>"RefAdditionOrder"]);
            
            $_day_holiday=0;
            $_day_holiday_add=0;
            
            if($_all_daymonth>0){
                foreach ($_additionList1 as $rs){
                    $refAddObj=\Humanres\RefHolidayAdditionClass::getInstance($rs);
                    if($_all_daymonth> floatval($refAddObj->RefAdditionStart) && $_all_daymonth<= floatval($refAddObj->RefAdditionEnd)){
                        $_day_holiday=$refAddObj->RefAddition;
                    }
                }
            }
            if($_atg_daymonth>0){
                foreach ($_additionList2 as $rs){
                    $refAddObj=\Humanres\RefHolidayAdditionClass::getInstance($rs);
                    if($_atg_daymonth> floatval($refAddObj->RefAdditionStart) && $_atg_daymonth<= floatval($refAddObj->RefAdditionEnd)){
                        $_day_holiday_add=$refAddObj->RefAddition;
                        $_day_holiday+=$_day_holiday_add;
                    }
                }
            }
            
            $_errors=array();
            $mainObj=\Humanres\PersonHolidayClass::getInstance();
            
            $reg_date=$now->format('Y-m-d');
            $reg_arr=$mainObj->getNextNumber($reg_date);
            
            $_yearend=$now->format("Y");
            if($_HolidayIsFirstYear){
                $_yearend++;
            }
            $_yearstart=$_yearend-1;
            
            $_holiday_start=$_param['HolidayDateStart'];
            
            $_tmpdate= new DateTime($_param['HolidayDateStart']);
            $_tmp_md_start=$_tmpdate->format('Y-m-d');
            
            $j=1;
            while($j<$_day_holiday){
                $_tmpdate->add(new DateInterval('P1D'));
                if(!\System\Util::isWeekend($_tmpdate->format("Y-m-d"))){
                    $j++;
                }
            }
            
            $_listDate=\Humanres\RefHolidayDayClass::getInstance()->getRowList([
                '_column'=>"RefDayDate",
                'holiday_datestart'=>$_tmp_md_start,
                'holiday_dateend'=>$_tmpdate->format('Y-m-d'),
            ]);
            if(count(array_unique($_listDate))>0){
                $_count_hol=0;
                $_yyyy=$now->format('Y');
                foreach (array_unique($_listDate) as $r){
                    if(!\System\Util::isWeekend($_yyyy."-".$r)){
                        $_count_hol++;
                    }
                }
                $_tmpdate->add(new DateInterval('P'.$_count_hol.'D'));
            }
            
            /**Ажилдаа ирэх өдөр */
            $_tmpdate->add(new DateInterval('P1D'));
            
            $_holiday_end=\Humanres\RefHolidayClass::getStartWorkDateHoliday($_tmpdate);
            
            $_data=array_merge($_param,$reg_arr,
                array(
                    "HolidayPersonID"=>$_editparam['id'],
                    "HolidayEmployeeID"=>$personObj->PersonEmployeeID,
                    "HolidayRegisterDate"=>$reg_date,
                    "HolidayIsFirstYear"=>$_HolidayIsFirstYear,
                    "HolidayJobDateStart"=>$_yearstart."-".$date_start->format('m-d'),
                    "HolidayJobDateEnd"=>$_yearend."-".$date_start->format('m-d'),
                    "HolidayDateStart"=>$_holiday_start,
                    "HolidayDateEnd"=>$_holiday_end,
                    "HolidayDays"=>$_day_holiday,
                    "HolidayAddition"=>$_day_holiday_add,
                    "HolidayChiefPersonID"=>$_cheifEmployeeObj->isExist()?$_cheifEmployeeObj->EmployeePersonID:0,
                    "HolidayChiefEmployeeID"=>$_cheifEmployeeObj->isExist()?$_cheifEmployeeObj->EmployeeID:0,
                    "HolidayHumanresPersonID"=>$_humanresEmployeeObj->isExist()?$_humanresEmployeeObj->EmployeePersonID:0,
                    "HolidayHumanresEmployeeID"=>$_humanresEmployeeObj->isExist()?$_humanresEmployeeObj->EmployeeID:0,
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_holiday=$mainObj->getRowCount(["holiday_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountHoliday"=>$_count_holiday,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updateholiday":
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonHolidayClass::getInstance()->getRow(array("holiday_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->HolidayPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$personObj->PersonEmployeeID]);
            if(!$employeeObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['HolidayDateStart'])){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Эхлэх огноо сонгоогүй байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_HolidayIsFirstYear=isset($_param['HolidayIsFirstYear'])?$_param['HolidayIsFirstYear']:0;
            
            $activeJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
                "job_personid"=>$personObj->PersonID,
                "job_isnow"=>1,
                "rowstart"=>0,
                "rowlength"=>1,
            ]);
            $PersonWorkYearAll=$personObj->PersonWorkYearAll;
            $PersonWorkMonthAll=$personObj->PersonWorkMonthAll;
            $PersonWorkDayAll=$personObj->PersonWorkDayAll;
            $now = new DateTime();
            
            $_all_daymonth= 0;
            $_atg_daymonth= 0;
            
            $date_start=new \DateTime($employeeObj->EmployeeStartDate);
            
            if($activeJobObj->isExist()){
                $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
                $tmpDay=$personObj->PersonWorkDayAll==""?0:($personObj->PersonWorkDayAll+$_time['day'])%30;
                
                $tmpMonth=$personObj->PersonWorkMonthAll==""?$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30):$personObj->PersonWorkMonthAll+$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30);
                
                $PersonWorkYearAll=$personObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthAll=($tmpMonth%12);
                $PersonWorkDayAll=$tmpDay;
                
                $_all_daymonth= floatval(($PersonWorkYearAll*12 + $PersonWorkMonthAll).".".$PersonWorkDayAll);
                
                if($activeJobObj->JobOrganTypeID==1 && $activeJobObj->JobOrganSubID==1){
                    $tmpDay=$personObj->PersonWorkDayOrgan==""?0:($personObj->PersonWorkDayOrgan+$_time['day'])%30;
                    $tmpMonth=$personObj->PersonWorkMonthOrgan==""?$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30):$personObj->PersonWorkMonthOrgan+$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30);
                    $PersonWorkYearOrgan=$personObj->PersonWorkYearOrgan==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearOrgan+$_time['year']+floor($tmpMonth/12);
                    $PersonWorkMonthOrgan=$tmpMonth%12;
                    $PersonWorkDayOrgan=$tmpDay;
                    
                    $_atg_daymonth= floatval(($PersonWorkYearOrgan*12 + $PersonWorkMonthOrgan).".".$PersonWorkDayOrgan);
                }
                $date_start=new \DateTime($activeJobObj->JobDateStart);
            }
            $lastOrganJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
                "job_personid"=>$personObj->PersonID,
                "job_isabsent"=>1,
                "job_organsubid"=>1,
                "orderby"=>['JobDateStart'],
                "rowstart"=>0,
                "rowlength"=>1,
            ]);
            if($lastOrganJobObj->isExist()){
                $date_start=new \DateTime($lastOrganJobObj->JobDateStart);
            }
            $_additionList1=\Humanres\RefHolidayAdditionClass::getInstance()->getRowList(["holidayaddition_type"=>1,"orderby"=>"RefAdditionOrder"]);
            $_additionList2=\Humanres\RefHolidayAdditionClass::getInstance()->getRowList(["holidayaddition_type"=>2,"orderby"=>"RefAdditionOrder"]);
            
            $_day_holiday=0;
            $_day_holiday_add=0;
            
            if($_all_daymonth>0){
                foreach ($_additionList1 as $rs){
                    $refAddObj=\Humanres\RefHolidayAdditionClass::getInstance($rs);
                    if($_all_daymonth> floatval($refAddObj->RefAdditionStart) && $_all_daymonth<= floatval($refAddObj->RefAdditionEnd)){
                        $_day_holiday=$refAddObj->RefAddition;
                    }
                }
            }
            if($_atg_daymonth>0){
                foreach ($_additionList2 as $rs){
                    $refAddObj=\Humanres\RefHolidayAdditionClass::getInstance($rs);
                    if($_atg_daymonth> floatval($refAddObj->RefAdditionStart) && $_atg_daymonth<= floatval($refAddObj->RefAdditionEnd)){
                        $_day_holiday_add=$refAddObj->RefAddition;
                        $_day_holiday+=$_day_holiday_add;
                    }
                }
            }
            
            $_yearend=$now->format("Y");
            
            if($_HolidayIsFirstYear){
                $_yearend++;
               
            }
            $_yearstart=$_yearend-1;
            
            $_holiday_start=$_param['HolidayDateStart'];
            
            $_tmpdate= new DateTime($_param['HolidayDateStart']);
            $_tmp_md_start=$_tmpdate->format('Y-m-d');
            
            $j=1;
            while($j<$_day_holiday){
                $_tmpdate->add(new DateInterval('P1D'));
                if(!\System\Util::isWeekend($_tmpdate->format("Y-m-d"))){
                    $j++;
                }
            }
            
            $_listDate=\Humanres\RefHolidayDayClass::getInstance()->getRowList([
                '_column'=>"RefDayDate",
                'holiday_datestart'=>$_tmp_md_start,
                'holiday_dateend'=>$_tmpdate->format('Y-m-d'),
            ]);
            if(count(array_unique($_listDate))>0){
                $_count_hol=0;
                $_yyyy=$now->format('Y');
                foreach (array_unique($_listDate) as $r){
                    if(!\System\Util::isWeekend($_yyyy."-".$r)){
                        $_count_hol++;
                    }
                }
                $_tmpdate->add(new DateInterval('P'.$_count_hol.'D'));
            }
            
            /**Ажилдаа ирэх өдөр */
            $_tmpdate->add(new DateInterval('P1D'));
            
            $_holiday_end=\Humanres\RefHolidayClass::getStartWorkDateHoliday($_tmpdate);
            
            $_data=array_merge($_param,
            array(
                "HolidayJobDateStart"=>$_yearstart."-".$date_start->format('m-d'),
                "HolidayJobDateEnd"=>$_yearend."-".$date_start->format('m-d'),
                "HolidayDateStart"=>$_holiday_start,
                "HolidayDateEnd"=>$_holiday_end,
                "HolidayDays"=>$_day_holiday,
                "HolidayAddition"=>$_day_holiday_add,
                "HolidayIsFirstYear"=>$_HolidayIsFirstYear
            ));
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_data,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeholiday":
            $_param=isset($_POST['holiday'])?$_POST['holiday']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonHolidayClass::getInstance()->getRow(array("holiday_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->HolidayPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_holiday=$mainObj->getRowCount(["holiday_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountHoliday"=>$_count_holiday,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "addbill":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_param=isset($_POST['bill'])?$_POST['bill']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
            if(!isset($_editparam['id']) || $_editparam['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$_editparam['id']));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$personObj->PersonEmployeeID]);
            if(!$employeeObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_cheifEmployeeObj=\Humanres\EmployeeClass::getInstance()->getRow([
                'employee_get_table'=>6,
                'department_parentid'=>0,
                'department_typeid'=>5,
                'position_typeid'=>3,
                'rowstart'=>0,
                'rowlength'=>1,
                "orderby"=>['EmployeeID desc']
            ]);
            
            $privObj=new \System\SystemPrivClass();
            $_personids=$privObj->getPersonListByPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
            
            $_humanresEmployeeObj=\Humanres\EmployeeClass::getInstance()->getRow([
                'employee_personid'=>$_personids,
                'employee_isactive'=>1,
                'rowstart'=>0,
                'rowlength'=>1,
                "orderby"=>['EmployeeID desc']
            ]);
           
            $_errors=array();
            $mainObj=\Humanres\PersonHolidayBillClass::getInstance();
            $now=new \DateTime();
            $reg_date=$now->format('Y-m-d');
            $reg_arr=$mainObj->getNextNumber($reg_date);
            
            $_data=array_merge($_param,$reg_arr,
                array(
                    "BillPersonID"=>$_editparam['id'],
                    "BillEmployeeID"=>$personObj->PersonEmployeeID,
                    "BillRegisterDate"=>$reg_date,
                    "BillChiefPersonID"=>$_cheifEmployeeObj->isExist()?$_cheifEmployeeObj->EmployeePersonID:0,
                    "BillChiefEmployeeID"=>$_cheifEmployeeObj->isExist()?$_cheifEmployeeObj->EmployeeID:0,
                    "BillHumanresPersonID"=>$_humanresEmployeeObj->isExist()?$_humanresEmployeeObj->EmployeePersonID:0,
                    "BillHumanresEmployeeID"=>$_humanresEmployeeObj->isExist()?$_humanresEmployeeObj->EmployeeID:0,
                    "CreatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                    "CreateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
                ));
            $__con->beginCommit();
            
            $_id=$mainObj->addRow($_data);
            if($_id<1){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_count_bill=$mainObj->getRowCount(["bill_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountBill"=>$_count_bill,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refreshid'] = $_id;
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "updatebill":
            $_param=isset($_POST['bill'])?$_POST['bill']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonHolidayBillClass::getInstance()->getRow(array("bill_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->HolidayPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$personObj->PersonEmployeeID]);
            if(!$employeeObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow(array_merge($_param,["UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]) );
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 0;
            $_result['_refreshform'] = 0;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removebill":
            $_param=isset($_POST['bill'])?$_POST['bill']:array();
            $_errors=array();
            
            if(!isset($_POST['paramid']) || $_POST['paramid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Humanres\PersonHolidayBillClass::getInstance()->getRow(array("bill_id"=>$_POST['paramid']));
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $personObj= \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>$mainObj->BillPersonID));
            if(!$personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Засах бичлэг олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->beginCommit();
            
            $res=$mainObj->deleteRow();
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_count_bill=$mainObj->getRowCount(["bill_personid"=>$personObj->PersonID]);
            $res=$personObj->updateRow(array(
                "PersonCountBill"=>$_count_bill,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ));
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $personObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        default :
            break;
    }
