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
    
    $_directionList=$refObj->getRowList(["_mainindex"=>"RefDirectionID"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION);
    $_countryList=\Office\RefCountryClass::getInstance()->getRowList(['_mainindex'=>"CountryID"]);
    
    $mainObj=new \Humanres\PersonStudyClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["study_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "study_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $studyObj= \Humanres\PersonStudyClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $_directionObj=\Humanres\ReferenceClass::getInstance(isset($_directionList[$studyObj->StudyDirectionID])?$_directionList[$studyObj->StudyDirectionID]:"");
        $_countryObj=\Humanres\ReferenceClass::getInstance(isset($_countryList[$tmpObj->StudyCountryID])?$_countryList[$tmpObj->StudyCountryID]:"");
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй"  data-url="'.RF.'/m/humanres/detailemployee/main?_spage=study" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $studyObj->StudyCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $_directionObj->RefDirectionTitle,
            $_countryObj->CountryName,
            $studyObj->StudySchoolTitle,
            $studyObj->StudyTitle,
            $studyObj->StudyDateStart,
            $studyObj->StudyDateEnd,
            $studyObj->StudyDescr,
            $studyObj->StudyLicence,
            $studyObj->StudyLicenceDate,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;