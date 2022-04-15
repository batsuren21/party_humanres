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
        case "addorgan":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_ORGANLIST);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['organ'])?$_POST['organ']:array();
            $_errors=array();
            
            $mainObj=\Office\OrganListClass::getInstance();
            
            $_data=$_param;    
            
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
        case "editorgan":
            $_param=isset($_POST['organ'])?$_POST['organ']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\OrganListClass::getInstance()->getRow(array("organ_id"=>$_param['id']));
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
            
            $res=$mainObj->updateRow($_param);
            
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
        case "removeorgan":
            $_param=isset($_POST['organ'])?$_POST['organ']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\OrganListClass::getInstance()->getRow(array("organ_id"=>$_param['id']));
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
        case "addpetorgan":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_PETORGANLIST);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['class'])?$_POST['class']:array();
            $_errors=array();
            
            $mainObj=\Office\RefGovClassClass::getInstance();
            
            $_data=$_param;
            
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
        case "editpetorgan":
            $_param=isset($_POST['class'])?$_POST['class']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefGovClassClass::getInstance()->getRow(array("class_id"=>$_param['id']));
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
            
            $res=$mainObj->updateRow($_param);
            
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
        case "removepetorgan":
            $_param=isset($_POST['class'])?$_POST['class']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefGovClassClass::getInstance()->getRow(array("class_id"=>$_param['id']));
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
        case "addpetdirection":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_PETDIRECTIONLIST);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['direction'])?$_POST['direction']:array();
            $_errors=array();
            
            $mainObj=\Office\RefDirectionClass::getInstance();
            
            $_data=$_param;
            
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
        case "editpetdirection":
            $_param=isset($_POST['direction'])?$_POST['direction']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefDirectionClass::getInstance()->getRow(array("direction_id"=>$_param['id']));
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
            
            $res=$mainObj->updateRow($_param);
            
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
        case "removepetdirection":
            $_param=isset($_POST['direction'])?$_POST['direction']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefDirectionClass::getInstance()->getRow(array("direction_id"=>$_param['id']));
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
        case "addpetsector":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_PETSECTORLIST);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['sector'])?$_POST['sector']:array();
            $_errors=array();
            
            $mainObj=\Office\RefSectorClass::getInstance();
            
            $_data=$_param;
            
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
        case "editpetsector":
            $_param=isset($_POST['sector'])?$_POST['sector']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefSectorClass::getInstance()->getRow(array("sector_id"=>$_param['id']));
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
            
            $res=$mainObj->updateRow($_param);
            
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
        case "removepetsector":
            $_param=isset($_POST['sector'])?$_POST['sector']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\RefSectorClass::getInstance()->getRow(array("sector_id"=>$_param['id']));
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
        case "addarea":
            $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_AREA);
            
            if(!$_priv_reg){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Танд бүртгэх эрх байхгүй байна.")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            
            $_param=isset($_POST['area'])?$_POST['area']:array();
            $_errors=array();
            
            $mainObj=\Office\AreaClass::getInstance();
            if(isset($_param['AreaParentID']) && $_param['AreaParentID']>0){
                $areaParentObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_param['AreaParentID']]);
                $_globalid=0;
                if($areaParentObj->AreaGlobalID==2){
                    $_globalid=4;
                }elseif($areaParentObj->AreaGlobalID==3){
                    $_globalid=5;
                }elseif($areaParentObj->AreaGlobalID==4){
                    $_globalid=6;
                }elseif($areaParentObj->AreaGlobalID==5){
                    $_globalid=7;
                }
                $_param=array_merge($_param,["AreaGlobalID"=>$_globalid]);
            }
            $_data=$_param;
            
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
        case "editarea":
            $_param=isset($_POST['area'])?$_POST['area']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\AreaClass::getInstance()->getRow(array("area_id"=>$_param['id']));
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
            
            $res=$mainObj->updateRow($_param);
            
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
        case "removearea":
            $_param=isset($_POST['area'])?$_POST['area']:array();
            $_errors=array();
            
            if(!isset($_param['id']) || $_param['id']==""){
                $result['_state'] = false;
                $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Бичлэгийн дугаар хоосон байна")));
                header("Content-type: application/json");
                echo json_encode($result);
                exit;
            }
            $mainObj= \Office\AreaClass::getInstance()->getRow(array("area_id"=>$_param['id']));
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
        default :
            break;
    }
