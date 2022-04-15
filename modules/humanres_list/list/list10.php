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
    
    $_relationList=$refObj->getRowList(["_mainindex"=>"RefRelationID"],\Humanres\ReferenceClass::TBL_RELATION);
    $_jobList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
    
    $_areaList=\Office\AreaClass::getInstance()->getRowList(["_mainindex"=>"AreaID"]);
    
    $mainObj=new \Humanres\PersonFamilyClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["family_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "family_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $tmpMainObj= \Humanres\PersonFamilyClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $_relationObj=\Humanres\ReferenceClass::getInstance(isset($_relationList[$tmpMainObj->FamilyRelationID])?$_relationList[$tmpMainObj->FamilyRelationID]:"");
        $_jobObj=\Humanres\ReferenceClass::getInstance(isset($_jobList[$tmpMainObj->FamilyJobTypeID])?$_jobList[$tmpMainObj->FamilyJobTypeID]:"");
        $_cityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpMainObj->FamilyBirthCityID])?$_areaList[$tmpMainObj->FamilyBirthCityID]:"");
        $_districtObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpMainObj->FamilyBirthDistrictID])?$_areaList[$tmpMainObj->FamilyBirthDistrictID]:"");
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-url="'.RF.'/m/humanres/detailemployee/main?_spage=family" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $tmpMainObj->FamilyCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $_relationObj->RefRelationTitle,
            $tmpMainObj->FamilyLastName,
            $tmpMainObj->FamilyFirstName,
            $tmpMainObj->FamilyBirthDate,
            $_cityObj->AreaName,
            $_districtObj->AreaName,
            $_jobObj->RefTypeTitle,
            $tmpMainObj->FamilyJobOrgan,
            $tmpMainObj->FamilyJobPosition,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;