<?php
    $_records = array();
    $sEcho = intval($_REQUEST['draw']);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_FELONY_REG);
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_LIST);
    
    if(!\Office\Permission::isLoginPerson() && $_priv_access){
        $_records["customActionStatus"] = "NO";
        $_records["customActionMessage"] = "Та нэвтрэх эрхгүй байна. Системийн админтай холбоо барина уу";
        $_records["sEcho"] = $sEcho;
        $_records["iTotalDisplayRecords"] = 0;
        $_records["iTotalRecords"] = 0;
        echo json_encode($_records);
        exit;
    }
    
    $searchdata=isset($_REQUEST['searchdata'])?$_REQUEST['searchdata']:array();
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_organType=$refObj->getRowList(["_mainindex"=>"RefOrganTypeID"],\Humanres\ReferenceClass::TBL_JOB_ORGANTYPE);
    $_organList=\Humanres\ReferenceClass::getInstance()->getRowList(["_mainindex"=>"RefOrganID"],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
    $_organSubList=\Humanres\ReferenceClass::getInstance()->getRowList(["_mainindex"=>"RefOrganSubID"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
    $_refPosList=\Humanres\ReferenceClass::getInstance()->getRowList(["_mainindex"=>"RefPositionID"],\Humanres\ReferenceClass::TBL_JOB_POSITION);
    
    $mainObj=new \Humanres\PersonJobClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["job_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "job_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $mainObj= \Humanres\PersonJobClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $_organTypeObj=\Humanres\ReferenceClass::getInstance(isset($_organType[$mainObj->JobOrganTypeID])?$_organType[$mainObj->JobOrganTypeID]:[]);
        $_organObj=\Humanres\ReferenceClass::getInstance(isset($_organList[$mainObj->JobOrganID])?$_organList[$mainObj->JobOrganID]:[]);
        $_organSubObj=\Humanres\ReferenceClass::getInstance(isset($_organSubList[$mainObj->JobOrganSubID])?$_organSubList[$mainObj->JobOrganSubID]:[]);
        $_positionObj=\Humanres\ReferenceClass::getInstance(isset($_refPosList[$mainObj->JobPositionID])?$_refPosList[$mainObj->JobPositionID]:[]);
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-url="'.RF.'/m/humanres/detailemployee/main?_spage=job" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $mainObj->JobCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $_organTypeObj->RefOrganTypeTitle,
            $_organObj->RefOrganTitle,
            $_organSubObj->RefOrganSubTitle,
            $mainObj->JobOrganTitle,
            $mainObj->JobDepartmentTitle,
            ($mainObj->JobPositionID>0?$_positionObj->RefPositionTitle:"").$mainObj->JobPositionTitle,
            (!$mainObj->JobIsNow?"Үгүй":"Тийм"),
            $mainObj->JobDateStart,
            $mainObj->JobDateQuit,
            $mainObj->JobStartOrder,
            $mainObj->JobQuitReason,
            $mainObj->JobQuitOrder,
            $mainObj->JobQuitOrderDate
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;