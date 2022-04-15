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
        case "adduser":
            $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_ACCESS);
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_USER_PRIV);
            
            if(!$_priv_reg || !$_priv_access){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['person'])?$_POST['person']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
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
            $_param=array_merge($_param,[
                "PersonUserIsCreated"=>1,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]
            );
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
            $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_username'=>$_param['PersonUserName'],"person_username_cond"=>"eq"]);
            if($personObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("field"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэвтрэх нэр давхцаж байна","person[PersonUserName]")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_errors=array();
            
            $__con->beginCommit();
            
            $res=$mainObj->updateRow($_param);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshfull'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "edituser":
            $_param=isset($_POST['person'])?$_POST['person']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
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
            $_param=array_merge($_param,[
                "PersonUserIsCreated"=>1,
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]
                );
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
            
            $res=$mainObj->updateRow($_param);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
            $_result['_refresh'] = 1;
            $_result['_refreshdetail'] = 1;
            $_result['_refreshform'] = 1;
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
            break;
        case "removeuser":
            $_param=isset($_POST['person'])?$_POST['person']:array();
            $_editparam=isset($_POST['editparam'])?$_POST['editparam']:array();
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
            $_errors=array();
           
            $__con->beginCommit();
            $_param=array_merge($_param,[
                "PersonUserIsCreated"=>0,
                "PersonUserName"=>"",
                "PersonUserPassword"=>"",
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]
                );
            $res=$mainObj->updateRow($_param);
            if(!$res){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$mainObj->Error
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            
            $__con->closeCommit();
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай устгалаа.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "userpriv":
            $_param=isset($_POST['user'])?$_POST['user']:array();
            
            if(!isset($_param['moduleid']) || $_param['moduleid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Модулийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['personid']) || $_param['personid']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хэрэглэгчийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $privObj=new \System\SystemPrivClass();
            $res=$privObj->savePriv($_param);
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,$privObj->error->getErrorText())));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_result['_state'] = true;
            $_result['_text'] = "Эрх амжилттай солигдлоо.";
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
           
            break;
        default :
            break;
    }
