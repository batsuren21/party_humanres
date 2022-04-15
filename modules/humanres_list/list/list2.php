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
    
    $_levelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_EDUCATION_DEGREE);
    $_schoolList=$refObj->getRowList(["_mainindex"=>"RefSchoolID"],\Humanres\ReferenceClass::TBL_EDUCATION_SCHOOL);
    
    $mainObj=new \Humanres\PersonEducationClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["education_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "education_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $eduObj= \Humanres\PersonEducationClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $_levelObj=\Humanres\ReferenceClass::getInstance(isset($_levelList[$eduObj->EducationLevelID])?$_levelList[$eduObj->EducationLevelID]:"");
        $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$tmpObj->EducationDegreeID])?$_degreeList[$tmpObj->EducationDegreeID]:"");
        $_schoolObj=\Humanres\ReferenceClass::getInstance(isset($_schoolList[$tmpObj->EducationSchoolID])?$_schoolList[$tmpObj->EducationSchoolID]:"");
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm"  data-url="'.RF.'/m/humanres/detailemployee/main?_spage=education" title="Дэлгэрэнгүй" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $eduObj->EducationCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $_schoolObj->RefSchoolTitle,
            $_levelObj->RefLevelTitle,
            $_degreeObj->RefDegreeTitle,
            $eduObj->EducationSchoolTitle,
            $eduObj->EducationDateStart,
            $eduObj->EducationIsNow===""?"":$eduObj->EducationIsNow?"Тийм":"Үгүй",
            $eduObj->EducationDateEnd,
            $eduObj->EducationLicence,
            $eduObj->EducationProfession,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;