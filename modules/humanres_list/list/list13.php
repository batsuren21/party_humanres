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
    
    $mainObj=new \Humanres\PersonHolidayClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["holiday_get_table"=>1,"person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "holiday_get_table"=>1,
        "person_get_table"=>1,
        "rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
  
    foreach ($_resultList as $j => $row){
        $tmpMainObj= \Humanres\PersonHolidayClass::getInstance($row);
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-url="'.RF.'/m/humanres/detailemployee/main?_spage=holiday" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $tmpMainObj->HolidayCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $tmpMainObj->HolidayRegisterNumberFull,
            $tmpMainObj->HolidayJobDateStart." - ".$tmpMainObj->HolidayJobDateEnd,
            $tmpMainObj->HolidayDays,
            $tmpMainObj->HolidayDateStart,
            $tmpMainObj->HolidayDateEnd,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;