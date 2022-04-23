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
        
        default :
            break;
    }
