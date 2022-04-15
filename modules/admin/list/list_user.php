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
    $search=array_merge($searchdata,['person_get_table'=>1,'employee_isactive'=>1]);
    
    $mainObj=new \Humanres\PersonClass();

    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "orderby"=>array("T3.DepartmentOrder","T3.DepartmentID","T1.PositionOrder","T1.PositionID"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
     
    foreach ($_resultList as $j => $row){
        $personObj= \Humanres\PersonClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        if($personObj->PersonUserIsCreated){
            $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$personObj->PersonID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/admin/detail/user">
                <i class="la la-edit"></i>
            </a>';
        }else{
            $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Хэрэглэгч үүсгэх" data-id="'.$personObj->PersonID.'" data-toggle="modal" data-target="#regModal" data-url="'.RF.'/m/admin/form/user">
                <i class="la la-plus"></i>
            </a>';
        }
        $startObj=\Humanres\ReferenceClass::getInstance(isset($_startList[$personObj->EmployeeStartID])?$_startList[$personObj->EmployeeStartID]:[]);
        
        $data[]=array(
            ($rownum++),
            $btn,
            $personObj->DepartmentFullName,
            $personObj->PositionFullName,
            $personObj->PersonRegisterNumber,
            $personObj->PersonLFName,
            $personObj->PersonUserName,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;