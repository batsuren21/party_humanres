<?php
    $_records = array();
    $sEcho = intval($_REQUEST['draw']);
    
    if(!\Office\Permission::isLoginPerson()){
        $_records["customActionStatus"] = "NO";
        $_records["customActionMessage"] = "Та нэвтрэх эрхгүй байна. Системийн админтай холбоо барина уу";
        $_records["sEcho"] = $sEcho;
        $_records["iTotalDisplayRecords"] = 0;
        $_records["iTotalRecords"] = 0;
        echo json_encode($_records);
        exit;
    }
     
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    if($_officeid<1){
        $_records["customActionStatus"] = "NO";
        $_records["customActionMessage"] = "Жагсаалт харуулах боломжгүй байна. Та системийн админтай холбоо барина уу";
        $_records["sEcho"] = $sEcho;
        $_records["iTotalDisplayRecords"] = 0;
        $_records["iTotalRecords"] = 0;
        echo json_encode($_records);
        exit;
    }
    
    $searchdata=isset($_REQUEST['searchdata'])?$_REQUEST['searchdata']:array();
    
    $now=new \DateTime();
    $search=array_merge($searchdata,['person_get_table'=>1]);
    if(!isset($searchdata['employee_isactive'])){
        $search=array_merge($search,['employee_isactive'=>1]);
    }
    $mainObj=new \Humanres\PersonClass();

    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "orderby"=>array("T3.DepartmentOrder","T3.DepartmentID","T1.PositionOrder","T1.PositionID"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_startList=$refObj->getRowList(["_mainindex"=>"RefStartID"],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
    $_quitList=$refObj->getRowList(["_mainindex"=>"RefQuitID"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT);
    
    foreach ($_resultList as $j => $row){
        $employeeObj= \Humanres\EmployeeClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$employeeObj->PersonID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/humanres/detailemployee/main">
            <i class="la la-edit"></i>
        </a>';
        $startObj=\Humanres\ReferenceClass::getInstance(isset($_startList[$employeeObj->EmployeeStartID])?$_startList[$employeeObj->EmployeeStartID]:[]);
        $quitObj=\Humanres\ReferenceClass::getInstance(isset($_quitList[$employeeObj->EmployeeQuitID])?$_quitList[$employeeObj->EmployeeQuitID]:[]);
        $str="";
        if(!$employeeObj->EmployeeIsActive){
            $str='<br><span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Чөлөөлөгдсөн</span>';    
        }
        $num=($rownum++);
        $num=$employeeObj->PersonIsEditable?'<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">'.$num.'</span>':$num;
        $data[]=array(
            $num,
            $btn,
            $employeeObj->DepartmentFullName,
            $employeeObj->PositionFullName,
            $employeeObj->PersonRegisterNumber.$str,
            $employeeObj->PersonLFName,
            $employeeObj->PersonContactWorkPhone." ".$employeeObj->PersonContactMobilePhone,
            $startObj->RefStartTitle,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;