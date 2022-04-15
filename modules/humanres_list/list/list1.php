<?php
    $_records = array();
    $sEcho = intval($_REQUEST['draw']);
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
   
    $now=new \DateTime();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_eduLevelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_ethnicList=$refObj->getRowList(["_mainindex"=>"RefEthnicID"],\Humanres\ReferenceClass::TBL_ETHNICITY);
    $_areaList=\Office\AreaClass::getInstance()->getRowList(["_mainindex"=>"AreaID"]);
    $_soldierList=$refObj->getRowList(["_mainindex"=>"RefSoldierID"],\Humanres\ReferenceClass::TBL_SOLDIER);
    
    $mainObj=new \Humanres\PersonClass();
    $search=$searchdata;
    
    $iTotalRecords = $mainObj->getRowCount(array_merge(["person_get_table"=>1],$search));
    $iDisplayLength = intval($_REQUEST['length']);
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = intval($_REQUEST['start']);
    
    $_resultList=$mainObj->getRowList(array_merge($search,array(
        "_getparams"=>['PersonID'],
        "person_get_table"=>1,
        "orderby"=>array("T3.DepartmentOrder, T1.PositionOrder"),"rowstart"=>$iDisplayStart,"rowlength"=>$iDisplayLength)));
    $data=array();
    
    $_personids=isset($_resultList['PersonID'])?array_unique(array_filter($_resultList['PersonID'])):[];
    $_mainList=isset($_resultList['_list'])?$_resultList['_list']:[];
    $activeJobList=[];
    if(count($_personids)>0){
        $activeJobList=\Humanres\PersonJobClass::getInstance()->getRowList([
            "_mainindex"=>"JobPersonID",
            "job_personid"=>$_personids,
            "job_isnow"=>1,
        ]);
    }
    
    foreach ($_mainList as $j => $row){
        $tmpObj= \Humanres\PersonClass::getInstance($row);
        $rownum= ($j +$iDisplayStart+ 1);
        
        $btn='<a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="Дэлгэрэнгүй" data-id="'.$tmpObj->PersonID.'" data-toggle="modal" data-target="#detailModalFelony">
            <i class="la la-search"></i>
        </a>';
       
        $eduObj=\Humanres\ReferenceClass::getInstance(isset($_eduLevelList[$tmpObj->PersonEducationLevelID])?$_eduLevelList[$tmpObj->PersonEducationLevelID]:"");
        $ethnicObj=\Humanres\ReferenceClass::getInstance(isset($_ethnicList[$tmpObj->PersonEthnicID])?$_ethnicList[$tmpObj->PersonEthnicID]:"");
        $soldierObj=\Humanres\ReferenceClass::getInstance(isset($_soldierList[$tmpObj->PersonSoldierID])?$_soldierList[$tmpObj->PersonSoldierID]:"");
        $birthCityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonBirthCityID])?$_areaList[$tmpObj->PersonBirthCityID]:"");
        $birthSumObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonBirthDistrictID])?$_areaList[$tmpObj->PersonBirthDistrictID]:"");
        $addressCityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonAddressCityID])?$_areaList[$tmpObj->PersonAddressCityID]:"");
        $addressDistrictObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonAddressDistrictID])?$_areaList[$tmpObj->PersonAddressDistrictID]:"");
        
        $activeJobObj=\Humanres\PersonJobClass::getInstance(isset($activeJobList[$tmpObj->PersonID])?$activeJobList[$tmpObj->PersonID]:[]);
        
        $PersonWorkYearAll=$tmpObj->PersonWorkYearAll;
        $PersonWorkMonthAll=$tmpObj->PersonWorkMonthAll;
        $PersonWorkDayAll=$tmpObj->PersonWorkDayAll;
        
        $PersonWorkYearGov=$tmpObj->PersonWorkYearGov;
        $PersonWorkMonthGov=$tmpObj->PersonWorkMonthGov;
        $PersonWorkDayGov=$tmpObj->PersonWorkDayGov;
        
        $PersonWorkYearMilitary=$tmpObj->PersonWorkYearMilitary;
        $PersonWorkMonthMilitary=$tmpObj->PersonWorkMonthMilitary;
        $PersonWorkDayMilitary=$tmpObj->PersonWorkDayMilitary;
        
        $PersonWorkYearCompany=$tmpObj->PersonWorkYearCompany;
        $PersonWorkMonthCompany=$tmpObj->PersonWorkMonthCompany;
        $PersonWorkDayCompany=$tmpObj->PersonWorkDayCompany;
        
        $PersonWorkYearOrgan=$tmpObj->PersonWorkYearOrgan;
        $PersonWorkMonthOrgan=$tmpObj->PersonWorkMonthOrgan;
        $PersonWorkDayOrgan=$tmpObj->PersonWorkDayOrgan;
        
        if($activeJobObj->isExist()){
            $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
            $tmpDay=$tmpObj->PersonWorkDayAll==""?0:($tmpObj->PersonWorkDayAll+$_time['day'])%30;
            
            $tmpMonth=$tmpObj->PersonWorkMonthAll==""?$_time['month']+floor(($tmpObj->PersonWorkDayAll+$_time['day'])/30):$tmpObj->PersonWorkMonthAll+$_time['month']+floor(($tmpObj->PersonWorkDayAll+$_time['day'])/30);
            
            $PersonWorkYearAll=$tmpObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthAll=($tmpMonth%12);
            $PersonWorkDayAll=$tmpDay;
            if($activeJobObj->JobOrganID==1){
                $tmpDay=$tmpObj->PersonWorkDayGov==""?0:($tmpObj->PersonWorkDayGov+$_time['day'])%30;
                $tmpMonth=$tmpObj->PersonWorkMonthGov==""?$_time['month']+floor(($tmpObj->PersonWorkDayGov+$_time['day'])/30):$tmpObj->PersonWorkMonthGov+$_time['month']+floor(($tmpObj->PersonWorkDayGov+$_time['day'])/30);
                $PersonWorkYearGov=$tmpObj->PersonWorkYearGov==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearGov+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthGov=$tmpMonth%12;
                $PersonWorkDayGov=$tmpDay;
            }
            if($activeJobObj->JobOrganTypeID==1){
                $tmpDay=$tmpObj->PersonWorkDayMilitary==""?0:(($tmpObj->PersonWorkDayMilitary+$_time['day'])%30);
                $tmpMonth=$tmpObj->PersonWorkMonthMilitary==""?$_time['month']+floor(($tmpObj->PersonWorkDayMilitary+$_time['day'])/30):$tmpObj->PersonWorkMonthMilitary+$_time['month']+floor(($tmpObj->PersonWorkDayMilitary+$_time['day'])/30);
                $PersonWorkYearMilitary=$tmpObj->PersonWorkYearMilitary==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearMilitary+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthMilitary=$tmpMonth%12;
                $PersonWorkDayMilitary=$tmpDay;
            }
            if($activeJobObj->JobOrganID>1){
                $tmpDay=$tmpObj->PersonWorkDayCompany==""?0:(($tmpObj->PersonWorkDayCompany+$_time['day'])%30);
                $tmpMonth=$tmpObj->PersonWorkMonthCompany==""?$_time['month']+floor(($tmpObj->PersonWorkDayCompany+$_time['day'])/30):$tmpObj->PersonWorkMonthCompany+$_time['month']+floor(($tmpObj->PersonWorkDayCompany+$_time['day'])/30);
                $PersonWorkYearCompany=$tmpObj->PersonWorkYearCompany==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearCompany+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthCompany=$tmpMonth%12;
                $PersonWorkDayCompany=$tmpDay;
            }
            if($activeJobObj->JobOrganSubID==1){
                $tmpDay=$tmpObj->PersonWorkDayOrgan==""?0:(($tmpObj->PersonWorkDayOrgan+$_time['day'])%30);
                $tmpMonth=$tmpObj->PersonWorkMonthOrgan==""?$_time['month']+floor(($tmpObj->PersonWorkDayOrgan+$_time['day'])/30):$tmpObj->PersonWorkMonthOrgan+$_time['month']+floor(($tmpObj->PersonWorkDayOrgan+$_time['day'])/30);
                $PersonWorkYearOrgan=$tmpObj->PersonWorkYearOrgan==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearOrgan+$_time['year']+floor($tmpMonth/12);
                $PersonWorkMonthOrgan=$tmpMonth%12;
                $PersonWorkDayOrgan=$tmpDay;
            }
        }
        
        $data[]=array(
            ($rownum++),
            $btn,
            "<img src='".$tmpObj->getImage()."' width='50'>",
            $tmpObj->PersonCreateDate,
            $tmpObj->PersonRegisterNumber,
            $tmpObj->DepartmentName,
            $tmpObj->PositionName,
            $tmpObj->PersonMiddleName,
            $tmpObj->PersonLastName,
            $tmpObj->PersonFirstName,
            $tmpObj->PersonBirthDate,
            $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй",
            $eduObj->RefLevelTitle,
            $ethnicObj->RefEthnicTitle,
            $birthCityObj->AreaTitle,
            $birthSumObj->AreaTitle,
            $addressCityObj->AreaTitle,
            $addressDistrictObj->AreaTitle,
            $tmpObj->PersonIsSoldiering===""?"":$tmpObj->PersonIsSoldiering?"Тийм":"Үгүй",
            $tmpObj->PersonSoldierPassNo,
            $tmpObj->PersonSoldierYear,
            $soldierObj->RefSoldierTitle,
            \System\Util::formatDaysMonth($PersonWorkYearAll,$PersonWorkMonthAll,$PersonWorkDayAll),
            \System\Util::formatDaysMonth($PersonWorkYearGov,$PersonWorkMonthGov,$PersonWorkDayGov),
            \System\Util::formatDaysMonth($PersonWorkYearMilitary,$PersonWorkMonthMilitary,$PersonWorkDayMilitary),
            \System\Util::formatDaysMonth($PersonWorkYearCompany,$PersonWorkMonthCompany,$PersonWorkDayCompany),
        );
    }
  
    $_records["draw"] = $sEcho;
    $_records["recordsTotal"] = $iTotalRecords;
    $_records["recordsFiltered"] = $iTotalRecords;
    $_records['data']=$data;
    header("Content-type: application/json");
    echo json_encode($_records);
    exit;