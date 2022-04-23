<?php
if(!\Office\Permission::isLoginPerson()){
    $result['_state'] = false;
    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : "";
$val_selected = isset($_POST['val_selected']) ? $_POST['val_selected'] : "";

switch ($action) {
    case "employee":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:"departmentid";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"AreaID","title"=>array("AreaName"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $tmp_param=$_param=="departmentid" || $_param==""?"employee_departmentid":$_param;
      
        $_list=Humanres\EmployeeClass::getInstance()->getRowList(array(
            "_select"=>array("T.EmployeeID, CONCAT(T2.PersonFirstName,'.',SUBSTRING(T2.PersonLastName, 1, 1)) as PersonLastFirstName"),
            "employee_get_table"=>5,
            $tmp_param=>$parent_value,"orderby"=>array("T2.PersonFirstName")));
  
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"EmployeeID","title"=>array("PersonLastFirstName"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "employeeactive":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:"departmentid";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        $parent_positiontype=isset($_GET['positiontype'])?$_GET['positiontype']:"";
        
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"AreaID","title"=>array("AreaName"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $tmp_param=$_param=="departmentid" || $_param==""?"employee_departmentid":$_param;
        
        $_searcharr=array(
            "_select"=>array("T.EmployeeID, CONCAT(T2.PersonFirstName,'.',SUBSTRING(T2.PersonLastName, 1, 1)) as PersonLastFirstName"),
            "employee_get_table"=>5,
            "employee_isactive"=>1,
            $tmp_param=>$parent_value,"orderby"=>array("T2.PersonFirstName"));
        if($parent_positiontype!=""){
            $_searcharr=array_merge($_searcharr,["position_typeid"=>$parent_positiontype]);
        }
        
        $_list=Humanres\EmployeeClass::getInstance()->getRowList($_searcharr);
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"EmployeeID","title"=>array("PersonLastFirstName"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "area":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"AreaID","title"=>array("AreaName"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\AreaClass::getInstance()->getRowList(array(
            "area_parentid"=>$parent_value,"orderby"=>array("T.AreaName")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"AreaID","title"=>array("AreaName"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    
    case "position":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"PositionID","title"=>array("PositionName"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_search=["position_departmentid"=>$parent_value,
            "orderby"=>array("T.PositionOrder")];
        if(isset($_GET['type'])){
            $typeids=explode(",", $_GET['type']);
            if(count($typeids)>0){
                $_search=array_merge($_search,['position_typeid'=>$typeids]);
            }
        }
        if(isset($_GET['classid'])){
            $classid=explode(",", $_GET['classid']);
            if(count($classid)>0){
                $_search=array_merge($_search,['position_classid'=>$classid]);
            }
        }
        if(isset($_GET['notid'])){
            $notids=explode(",", $_GET['notid']);
            if(count($notids)>0){
                $_search=array_merge($_search,['position_notid'=>$notids]);
            }
        }
        
        $_list=\Humanres\PositionClass::getInstance()->getRowList($_search);
        
        if(empty($val_selected)){
            if(count($_list)==1){
                $_tmp=reset($_list);
                if(isset($_tmp['PositionID'])){
                    $val_selected=$_tmp['PositionID'];
                }
            }
        }
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"PositionID","title"=>array("PositionFullName"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "ref_quit":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"RefSubID","title"=>array("RefSubTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_parentid"=>$parent_value,"orderby"=>"RefSubOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT_SUB);
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"RefSubID","title"=>array("RefSubTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "organlistdistrict":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"AreaID","title"=>array("AreaName"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\OrganAddressClass::getInstance()->getRowList(array(
            "address_parentid"=>$parent_value,"orderby"=>array("T.Address")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"AddressID","title"=>array("Address"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "ref_jobposition":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"RefPositionID","title"=>array("RefPositionTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_refid=[];
        if($parent_value==1){
            $_refid=[5,6,7,8,9,10,11,12];
        }else{
            $_refid=[1,2,3,4,5];
        }
        
        $_list=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_id"=>$_refid,
            "orderby"=>"RefPositionOrder"],\Humanres\ReferenceClass::TBL_JOB_POSITION);
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"RefPositionID","title"=>array("RefPositionTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "ref_studydirsub":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"RefDirSub1ID","title"=>array("RefDirSub1Title"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        if($parent_value==1){
            $_refid=[5,6,7,8,9,10,11,12];
        }else{
            $_refid=[1,2,3,4,5];
        }
        
        $_list=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_dirsub1_dirsubid"=>$parent_value,
            "orderby"=>"RefDirSub1Order"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB1);
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"RefDirSub1ID","title"=>array("RefDirSub1Title"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    default :
        break;
}

