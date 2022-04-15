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
    $mainObj=new \Office\OrganListClass();
    
    $iTotalRecords = $mainObj->getRowCount($search);
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "_getparams"=>["OrganFinGenID","OrganFinStrID","OrganCityID","OrganSumID"],
        "orderby"=>array("OrganID"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $_finGenIds=isset($_resultList['OrganFinGenID'])?array_unique(array_filter( $_resultList['OrganFinGenID'])):[];
    $_finStrIds=isset($_resultList['OrganFinStrID'])?array_unique(array_filter( $_resultList['OrganFinStrID'])):[];
    $_finCityIds=isset($_resultList['OrganCityID'])?array_unique(array_filter( $_resultList['OrganCityID'])):[];
    $_finCityIds=array_merge($_finCityIds,isset($_resultList['OrganSumID'])?array_unique(array_filter( $_resultList['OrganSumID'])):[]);
    
    $_genList=\Office\OrganFinanceGeneralClass::getInstance()->getRowList(["_mainindex"=>"FinGenID","fingen_id"=>$_finGenIds]);
    $_strList=\Office\OrganFinanceStraightClass::getInstance()->getRowList(["_mainindex"=>"FinStrID","finstr_id"=>$_finStrIds]);
    $_addrList=\Office\OrganAddressClass::getInstance()->getRowList(["_mainindex"=>"AddressID","address_id"=>$_finCityIds]);
    
    $_mainList=isset($_resultList['_list'])?$_resultList['_list']:[];
    
    foreach ($_mainList as $j => $row){
        $mainObj= \Office\OrganListClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$mainObj->OrganID.'" data-toggle="modal" data-target="#detailModal" data-url="'.RF.'/m/reference/detail/organ_list">
            <i class="la la-edit"></i>
        </a>';
        $genObj=\Office\OrganFinanceGeneralClass::getInstance(isset($_genList[$mainObj->OrganFinGenID])?$_genList[$mainObj->OrganFinGenID]:[]);
        $strObj=\Office\OrganFinanceStraightClass::getInstance(isset($_strList[$mainObj->OrganFinStrID])?$_strList[$mainObj->OrganFinStrID]:[]);
        $addrObj=\Office\OrganAddressClass::getInstance(isset($_addrList[$mainObj->OrganCityID])?$_addrList[$mainObj->OrganCityID]:[]);
        $addr1Obj=\Office\OrganAddressClass::getInstance(isset($_addrList[$mainObj->OrganSumID])?$_addrList[$mainObj->OrganSumID]:[]);
        
        $data[]=array(
            ($rownum++),
            $btn,
            $genObj->FinGenTitle,
            $strObj->FinStrTitle,
            $addrObj->Address,
            $addr1Obj->Address,
            $mainObj->OrganName,
            $mainObj->OrganIsActive?"Тийм":"Үгүй",
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;