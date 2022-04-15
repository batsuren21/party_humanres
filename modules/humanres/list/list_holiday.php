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
    $mainObj=new \Humanres\RefHolidayClass();
   
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_HOLIDAY);
    
    if(!$_priv_reg){
        $search=array_merge($search,['number_createpersonid'=>$_SESSION[SESSSYSINFO]->PersonID]);
    }
    
    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "orderby"=>array("RefHolidayType, RefHolidayYear desc, RefHolidayDateStart"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    foreach ($_resultList as $j => $row){
        $tmpObj= \Humanres\RefHolidayClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$tmpObj->RefHolidayID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/humanres/form_holiday/detail">
            <i class="la la-edit"></i>
        </a>';
        
        $_str=isset(\Humanres\RefHolidayClass::$_type[$tmpObj->RefHolidayType]['title'])?\Humanres\RefHolidayClass::$_type[$tmpObj->RefHolidayType]['title']:"";
        $_date=$tmpObj->RefHolidayDateStart!=$tmpObj->RefHolidayDateEnd?$tmpObj->RefHolidayDateStart." -с ".$tmpObj->RefHolidayDateEnd:$tmpObj->RefHolidayDateStart;
        $data[]=array(
            ($rownum++),
            $btn,
            $tmpObj->RefHolidayYear==""?"*":$tmpObj->RefHolidayYear,
            $_str,
            $tmpObj->RefHolidayTitle,
            $_date,
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;