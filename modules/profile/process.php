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
        case "changepassword":
            $_param=isset($_POST['password'])?$_POST['password']:array();
            $_errors=array();
            
            if(!isset($_param['PasswordOld']) || $_param['PasswordOld']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хуучин нууц үг хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['PasswordNew']) || $_param['PasswordNew']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Шинэ нууц үг хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if(!isset($_param['PasswordNewRepeat']) || $_param['PasswordNewRepeat']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Шинэ нууц үгийн давталт хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            if($_param['PasswordNew']!=$_param['PasswordNewRepeat']){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Шинэ нууц үгийн давталт таарахгүй байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_SESSION[SESSSYSINFO]->PersonID]);
            if(!$mainObj->isExist()){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хэрэглэгч олдсонгүй")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $_old = md5($_param['PasswordOld']);
            if($_old!=$mainObj->PersonUserPassword){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хуучин нууц үг буруу байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $res=$mainObj->updateRow([
                "PersonUserPassword"=>$_param['PasswordNew'],
                "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID
            ]);
            if(!$res){
                $result['_state'] = false;
                $result['_errors'] = $mainObj->Error;
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_result['_state'] = true;
            $_result['_text'] = "Амжилттай хадгаллаа.";
           
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
       
        default :
            break;
    }
