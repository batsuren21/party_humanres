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
    $mainObj=new \Humanres\PositionClass();

    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "position_get_table"=>1,
        "orderby"=>array("TDep.DepartmentOrder","TDep.DepartmentID","T.PositionOrder"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_typeList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_POSITION_TYPE);
    $_classList=$refObj->getRowList(["_mainindex"=>"RefClassID"],\Humanres\ReferenceClass::TBL_POSITION_CLASS);
    $_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_POSITION_DEGREE);
    $_rankList=$refObj->getRowList(["_mainindex"=>"RefRankID"],\Humanres\ReferenceClass::TBL_POSITION_RANK);
    
    foreach ($_resultList as $j => $row){
        $positionObj= \Humanres\PositionClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$positionObj->PositionID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/department/detail/position">
            <i class="la la-edit"></i>
        </a>';
        $typeObj=\Humanres\ReferenceClass::getInstance(isset($_typeList[$positionObj->PositionTypeID])?$_typeList[$positionObj->PositionTypeID]:[]);
        $classObj=\Humanres\ReferenceClass::getInstance(isset($_classList[$positionObj->PositionClassID])?$_classList[$positionObj->PositionClassID]:[]);
        $degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$positionObj->PositionDegreeID])?$_degreeList[$positionObj->PositionDegreeID]:[]);
        $rankObj=\Humanres\ReferenceClass::getInstance(isset($_rankList[$positionObj->PositionRankID])?$_rankList[$positionObj->PositionRankID]:[]);
        
        $data[]=array(
            ($rownum++),
            $btn,
            $positionObj->DepartmentFullName,
            $positionObj->PositionName,
            $positionObj->PositionFullName,
            $classObj->RefClassTitle,
            $positionObj->PositionOrder,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;