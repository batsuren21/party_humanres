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
    
    $_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_SALARY_DEGREE);
    $_condList=$refObj->getRowList(["_mainindex"=>"RefConditionID"],\Humanres\ReferenceClass::TBL_SALARY_CONDITION);
    $_eduList=$refObj->getRowList(["_mainindex"=>"RefEduID"],\Humanres\ReferenceClass::TBL_SALARY_EDU);
    
    $mainObj=new \Humanres\PersonSalaryClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["salary_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "salary_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $tmpMainObj= \Humanres\PersonSalaryClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$tmpMainObj->SalaryDegreeID])?$_degreeList[$tmpMainObj->SalaryDegreeID]:"");
        $_condObj=\Humanres\ReferenceClass::getInstance(isset($_condList[$tmpMainObj->SalaryConditionID])?$_condList[$tmpMainObj->SalaryConditionID]:"");
        $_eduObj=\Humanres\ReferenceClass::getInstance(isset($_eduList[$tmpMainObj->SalaryEduID])?$_eduList[$tmpMainObj->SalaryEduID]:"");
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-url="'.RF.'/m/humanres/detailemployee/main?_spage=salary" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $tmpMainObj->SalaryCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $tmpMainObj->SalaryValue,
            $_degreeObj->RefDegreeTitle,
            $tmpMainObj->SalaryConditionID>0?$_condObj->RefConditionTitle:"Нэмэгдэлгүй",
            $tmpMainObj->SalaryEduID>0?$_eduObj->RefEduTitle:"Нэмэгдэлгүй",
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;