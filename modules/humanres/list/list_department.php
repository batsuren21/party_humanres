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
    $search=$searchdata;
    $mainObj=new \Humanres\DepartmentClass();
    if(!isset($searchdata['department_isactive'])){
        $search=array_merge(["department_isactive"=>1],$search);
    }
    
    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "orderby"=>array("DepartmentOrder"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $_parentList=$mainObj->getRowList(["_mainindex"=>"DepartmentID"]);
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_typeList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_DEPARTMENT_TYPE);
    $_classList=$refObj->getRowList(["_mainindex"=>"RefClassID"],\Humanres\ReferenceClass::TBL_DEPARTMENT_CLASS);
    
    foreach ($_resultList as $j => $row){
        $depObj= \Humanres\DepartmentClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$depObj->DepartmentID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/humanres/detail/department">
            <i class="la la-edit"></i>
        </a>';
        $parentObj=\Humanres\DepartmentClass::getInstance(isset($_parentList[$depObj->DepartmentID])?$_parentList[$depObj->DepartmentID]:[]);
        $typeObj=\Humanres\ReferenceClass::getInstance(isset($_typeList[$depObj->DepartmentTypeID])?$_typeList[$depObj->DepartmentTypeID]:[]);
        $classObj=\Humanres\ReferenceClass::getInstance(isset($_classList[$depObj->DepartmentClassID])?$_classList[$depObj->DepartmentClassID]:[]);
        
        $data[]=array(
            ($rownum++),
            $btn,
            $depObj->DepartmentName,
            $depObj->DepartmentFullName,
            $depObj->DepartmentCountJob,
            $depObj->DepartmentCountPosition,
            $typeObj->RefTypeTitle,
            $classObj->RefClassTitle,
            $depObj->DepartmentOrder,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;