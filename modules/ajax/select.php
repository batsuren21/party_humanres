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
    case "fln_place":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"PlaceID","title"=>array("PlaceTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\FelonyPlaceClass::getInstance()->getRowList(array(
           
            "place_classid"=>$parent_value,
            "orderby"=>array("T.PlaceOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"PlaceID","title"=>array("PlaceTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "defposition":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"PositionSubID","title"=>array("PositionSubTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\AccusedDefendantPositionSubClass::getInstance()->getRowList(array(
            "positionsub_positionid"=>$parent_value,
            "orderby"=>array("T.PositionSubOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"PositionSubID","title"=>array("PositionSubTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "fln_seal_class":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"ClassID","title"=>array("ClassTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\FelonySealClassClass::getInstance()->getRowList(array(
            "class_parentid"=>$parent_value,
            "orderby"=>array("T.ClassOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"ClassID","title"=>array("ClassTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "fln_accused_terms":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"ClassID","title"=>array("ClassTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        
        $_list=\Office\AccusedTermClass::getInstance()->getRowList([
            "_getparams"=>['ATermLastTermID'],
            "aterm_accusedid"=>$parent_value]);
        
        if(isset($_list['ATermLastTermID']) && count($_list['ATermLastTermID'])>0) $selectedTerms=$_list['ATermLastTermID'];
        $_termList=\Office\TermClass::getInstance()->getRowList([
            "_select"=>["T1.ClassID, T1.ClassShortName, T.TermID, CONCAT(T1.ClassShortName,' ',T.TermNumber,IF(T.TermNumberSub IS NULL,'',CONCAT('.',T.TermNumberSub))) as TermName"],
            "term_get_table"=>1,
            "term_id"=>$selectedTerms,
            "orderby"=>"T1.ClassOrder, T.TermNumber, T.TermNumberSub"]);
    
        ob_start();
        \System\Combo::getComboGroup(["data"=>$_termList,"title"=>"TermName","value"=>"TermID","group"=>"ClassShortName","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$val_selected]);
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "fln_decide":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"DecideID","title"=>array("DecideTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\FelonyDecideTypeClass::getInstance()->getRowList(array(
            "decide_parentid"=>$parent_value,
            "orderby"=>array("T.DecideOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"DecideID","title"=>array("DecideTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "petition_decide":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"DecideID","title"=>array("DecideTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\PetitionDecideClass::getInstance()->getRowList(array(
            "decide_parentid"=>$parent_value,
            "orderby"=>array("T.DecideOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"DecideID","title"=>array("DecideTitle"),"flag"=> $_flag,"selected"=>$val_selected));
        $_content = ob_get_contents();
        ob_end_clean();
        
        $result['_state'] = true;
        $result['_html'] = $_content;
        header("Content-type: application/json");
        echo json_encode($result);
        exit;
    case "fln_organ":
        $_flag=isset($_POST['val_default'])?$_POST['val_default']:"1";
        $_param=isset($_POST['param']) && $_POST['param']!=""?$_POST['param']:0;
        $parent_value=isset($_POST['parent_value'])?$_POST['parent_value']:"";
        if($parent_value==""){
            ob_start();
            System\Combo::getCombo(array("data"=>[],"value"=>"OrganID","title"=>array("OrganTitle"),"flag"=> $_flag,"selected"=>$val_selected));
            $_content = ob_get_contents();
            ob_end_clean();
            $result['_state'] = true;
            $result['_html'] = $_content;
            header("Content-type: application/json");
            echo json_encode($result);
            exit;
        }
        $_list=\Office\FelonyOrganClass::getInstance()->getRowList(array(
            "organ_classid"=>$parent_value,
            "orderby"=>array("T.OrganOrder")));
        
        ob_start();
        System\Combo::getCombo(array("data"=>$_list,"value"=>"OrganID","title"=>array("OrganTitle"),"flag"=> $_flag,"selected"=>$val_selected));
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
        if(isset($_GET['notid'])){
            $notids=explode(",", $_GET['notid']);
            if(count($notids)>0){
                $_search=array_merge($_search,['position_notid'=>$notids]);
            }
        }
        
        
        $_list=\Humanres\PositionClass::getInstance()->getRowList($_search);
        
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

