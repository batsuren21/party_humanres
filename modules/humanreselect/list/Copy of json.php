<?php
    if(!\Office\Permission::isLoginPerson()){
        echo json_encode(array());
        exit;
    }
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    if($_officeid<1){
        echo json_encode(array());
        exit;
    }
    $officeObj=\Office\OfficeConfig::getOffice();
    if(!$officeObj->isExist()){
        echo json_encode(array());
        exit;
    }
    
    $_id=isset($_GET['id'])?$_GET['id']:0;
    if($_id<1){
        echo json_encode(array());
        exit;
    }
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_refAwardList=$refObj->getRowList(["_mainindex"=>"RefAwardID"],\Humanres\ReferenceClass::TBL_AWARD);
    $_refLevelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_refDegreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_EDUCATION_DEGREE);
    $_refSchoolList=$refObj->getRowList(["_mainindex"=>"RefSchoolID"],\Humanres\ReferenceClass::TBL_EDUCATION_SCHOOL);
    $_refEdurankList=$refObj->getRowList(["_mainindex"=>"RefRankID"],\Humanres\ReferenceClass::TBL_EDUCATION_RANK);
    $_refRelationList=$refObj->getRowList(["_mainindex"=>"RefRelationID"],\Humanres\ReferenceClass::TBL_RELATION);
    $_refJobList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
    $_refOrganList=$refObj->getRowList(["_mainindex"=>"RefOrganID"],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
    $_refOrganSubList=$refObj->getRowList(["_mainindex"=>"RefOrganSubID"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
    $_refPositionList=$refObj->getRowList(["_mainindex"=>"RefPositionID"],\Humanres\ReferenceClass::TBL_JOB_POSITION);
    $_refLangList=$refObj->getRowList(["_mainindex"=>"RefLanguageID"],\Humanres\ReferenceClass::TBL_LANGUAGE);
    $_refLangLevelList=$refObj->getRowList(['_mainindex'=>"RefLevelID"],\Humanres\ReferenceClass::TBL_LANGUAGE_LEVEL);
    $_refPosrankList=$refObj->getRowList(["_mainindex"=>"RefRankID"],\Humanres\ReferenceClass::TBL_POS_RANK);
    $_refPunishTypeList=$refObj->getRowList(["_mainindex"=>"RefPunishmentID"],\Humanres\ReferenceClass::TBL_PUNISHMENT);
    $_refSalaryDegreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_SALARY_DEGREE);
    $_refSalaryCondList=$refObj->getRowList(["_mainindex"=>"RefConditionID"],\Humanres\ReferenceClass::TBL_SALARY_CONDITION);
    $_refSalaryEduList=$refObj->getRowList(["_mainindex"=>"RefEduID"],\Humanres\ReferenceClass::TBL_SALARY_EDU);
    $_refDirectionList=$refObj->getRowList(["_mainindex"=>"RefDirectionID"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION);
    $_refCountryList=\Office\RefCountryClass::getInstance()->getRowList(['_mainindex'=>"CountryID"]);
    $_refTripList=$refObj->getRowList(["_mainindex"=>"RefTripID"],\Humanres\ReferenceClass::TBL_TRIP);
    
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_get_table'=>1,'person_id'=>$_id]);
    
    $now=new \DateTime();
    
    $_awardList=\Humanres\PersonAwardClass::getInstance()->getRowList(['award_personid'=>$personObj->PersonID,"orderby"=>"AwardDate"]);
    $_educationList=\Humanres\PersonEducationClass::getInstance()->getRowList(['education_personid'=>$personObj->PersonID,"orderby"=>"EducationDateStart"]);
    $_edurankList=\Humanres\PersonEduRankClass::getInstance()->getRowList(['edurank_personid'=>$personObj->PersonID,"orderby"=>"EduDate"]);
    $__familyList=\Humanres\PersonFamilyClass::getInstance()->getRowList(['_getparams'=>['FamilyBirthCityID','FamilyBirthDistrictID'],'family_personid'=>$personObj->PersonID]);
    $_familyList=isset($__familyList['_list'])?$__familyList['_list']:[];
    $_jobList=\Humanres\PersonJobClass::getInstance()->getRowList(['job_personid'=>$personObj->PersonID,"orderby"=>"JobDateStart"]);
    $_languageList=\Humanres\PersonLanguageClass::getInstance()->getRowList(['language_personid'=>$personObj->PersonID,"orderby"=>"LanguageID"]);
    $_posRankList=\Humanres\PersonPosRankClass::getInstance()->getRowList(['pos_personid'=>$personObj->PersonID,"orderby"=>"PosDate"]);
    $_punishmentList=\Humanres\PersonPunishmentClass::getInstance()->getRowList(['punishment_personid'=>$personObj->PersonID,"orderby"=>"PunishmentOrderDate"]);
    $_relateionList=\Humanres\PersonRelationClass::getInstance()->getRowList(['relation_personid'=>$personObj->PersonID]);
    $_salaryList=\Humanres\PersonSalaryClass::getInstance()->getRowList(['salary_personid'=>$personObj->PersonID,"orderby"=>"SalaryID desc","rowstart"=>0,"rowlength"=>1]);
    $_studyList=\Humanres\PersonStudyClass::getInstance()->getRowList(['study_personid'=>$personObj->PersonID,"orderby"=>"StudyDateStart"]);
    $_tripList=\Humanres\PersonTripClass::getInstance()->getRowList(['trip_personid'=>$personObj->PersonID,"orderby"=>"TripDateStart"]);
    
    $_cityids=isset($__familyList['FamilyBirthCityID'])?$__familyList['FamilyBirthCityID']:[];
    $_districtids=isset($__familyList['FamilyBirthDistrictID'])?$__familyList['FamilyBirthDistrictID']:[];
    
    $_areaList=\Office\AreaClass::getInstance()->getRowList(['_mainindex'=>'AreaID','area_id'=>array_unique(array_merge($_cityids,$_districtids)),'orderby'=>'AreaGlobalID, AreaName']);
    
    $_eduLevelObj=$refObj->getRowRef(["ref_id"=>$personObj->PersonEducationLevelID],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_ethicObj=$refObj->getRowRef(["ref_id"=>$personObj->PersonEthnicID],\Humanres\ReferenceClass::TBL_ETHNICITY);
    $_startObj=$refObj->getRowRef(["ref_id"=>$personObj->EmployeeStartID],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
    
    $_postypeObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionTypeID],\Humanres\ReferenceClass::TBL_POSITION_TYPE);
    $_posclassObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionClassID],\Humanres\ReferenceClass::TBL_POSITION_CLASS);
    $_posdegreeObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionDegreeID],\Humanres\ReferenceClass::TBL_POSITION_DEGREE);
    $_posrankObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionRankID],\Humanres\ReferenceClass::TBL_POSITION_RANK);
    
    $_people=[
        "PersonID"=>$personObj->PersonID,
        "PersonEmployeeID"=>$personObj->PersonEmployeeID,
        "PersonEducationLevelID"=>$personObj->PersonEducationLevelID,
        "PersonEducationLevelTitle"=>$_eduLevelObj->RefLevelTitle,
        "PersonEthnicID"=>$personObj->PersonEthnicID,
        "PersonEthnicTitle"=>$_ethicObj->RefEthnicTitle,
        "PersonRegisterNumber"=>$personObj->PersonRegisterNumber,
        "PersonLastName"=>$personObj->PersonLastName,
        "PersonFirstName"=>$personObj->PersonFirstName,
        "PersonMiddleName"=>$personObj->PersonMiddleName,
        "PersonBirthDate"=>$personObj->PersonBirthDate,
        "PersonGender"=>($personObj->PersonGender?"Эр":"Эм"),
        "PersonBirthCityID"=>$personObj->PersonBirthCityID,
        "PersonBirthDistrictID"=>$personObj->PersonBirthDistrictID,
        "PersonBirthPlace"=>$personObj->PersonBirthPlace,
        "PersonBasicCityID"=>$personObj->PersonBasicCityID,
        "PersonBasicDistrictID"=>$personObj->PersonBasicDistrictID,
        "PersonBasicPlace"=>$personObj->PersonBasicPlace,
        "PersonImageSource"=> $personObj->getImage(['size'=>'medium']),
        "PersonIsSoldiering"=>$personObj->PersonIsSoldiering,
        "PersonSoldierPassNo"=>$personObj->PersonSoldierPassNo,
        "PersonSoldierYear"=>$personObj->PersonSoldierYear,
        "PersonSoldierID"=>$personObj->PersonSoldierID,
        "PersonSoldierDescr"=>$personObj->PersonSoldierDescr,
        "PersonContactMobilePhone"=>$personObj->PersonContactMobilePhone,
        "PersonContactWorkPhone"=>$personObj->PersonContactWorkPhone,
        "PersonContactFax"=>$personObj->PersonContactFax,
        "PersonContactEmail"=>$personObj->PersonContactEmail,
        "PersonContactEmailOrgan"=>$personObj->PersonContactEmailOrgan,
        "PersonContactWebsite"=>$personObj->PersonContactWebsite,
        "PersonContactEmergencyName"=>$personObj->PersonContactEmergencyName,
        "PersonContactEmergencyPhone"=>$personObj->PersonContactEmergencyPhone,
        "PersonAddressCityID"=>$personObj->PersonAddressCityID,
        "PersonAddressDistrictID"=>$personObj->PersonAddressDistrictID,
        "PersonAddressHorooID"=>$personObj->PersonAddressHorooID,
        "PersonAddress"=>$personObj->PersonAddress,
        "PersonAddressFull"=>$personObj->PersonAddressFull,
        "DepartmentName"=>$personObj->DepartmentName,
        "DepartmentFullName"=>$personObj->DepartmentFullName,
        "PositionName"=>$personObj->PositionName,
        "PositionFullName"=>$personObj->PositionFullName,
        "PositionTypeTitle"=>$_postypeObj->RefTypeTitle,
        "PositionClassTitle"=>$_posclassObj->RefClassTitle,
        "PositionDegreeTitle"=>$_posdegreeObj->RefDegreeTitle,
        "PositionRankTitle"=>$_posrankObj->RefRankTitle,
    ];
    
    $_records['general'] = array("OfficeName"=>$officeObj->OfficeTitle);
    $_records['people'] = $_people;
    
    /*** Work Year ***/
    $activeJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
        "job_personid"=>$personObj->PersonID,
        "job_isnow"=>1,
        "rowstart"=>0,
        "rowlength"=>1,
    ]);
    $PersonWorkYearAll=$personObj->PersonWorkYearAll;
    $PersonWorkMonthAll=$personObj->PersonWorkMonthAll;
    $PersonWorkDayAll=$personObj->PersonWorkDayAll;
    
    $PersonWorkYearGov=$personObj->PersonWorkYearGov;
    $PersonWorkMonthGov=$personObj->PersonWorkMonthGov;
    $PersonWorkDayGov=$personObj->PersonWorkDayGov;
    
    $PersonWorkYearMilitary=$personObj->PersonWorkYearMilitary;
    $PersonWorkMonthMilitary=$personObj->PersonWorkMonthMilitary;
    $PersonWorkDayMilitary=$personObj->PersonWorkDayMilitary;
    
    $PersonWorkYearCompany=$personObj->PersonWorkYearCompany;
    $PersonWorkMonthCompany=$personObj->PersonWorkMonthCompany;
    $PersonWorkDayCompany=$personObj->PersonWorkDayCompany;
    
    if($activeJobObj->isExist()){
        $now = new DateTime();
        $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
        $tmpDay=$personObj->PersonWorkDayAll==""?0:($personObj->PersonWorkDayAll+$_time['day'])%30;
        
        $tmpMonth=$personObj->PersonWorkMonthAll==""?$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30):$personObj->PersonWorkMonthAll+$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30);
        
        $PersonWorkYearAll=$personObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
        $PersonWorkMonthAll=($tmpMonth%12);
        $PersonWorkDayAll=$tmpDay;
        if($activeJobObj->JobOrganID==1){
            $tmpDay=$personObj->PersonWorkDayGov==""?0:($personObj->PersonWorkDayGov+$_time['day'])%30;
            $tmpMonth=$personObj->PersonWorkMonthGov==""?$_time['month']+floor(($personObj->PersonWorkDayGov+$_time['day'])/30):$personObj->PersonWorkMonthGov+$_time['month']+floor(($personObj->PersonWorkDayGov+$_time['day'])/30);
            $PersonWorkYearGov=$personObj->PersonWorkYearGov==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearGov+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthGov=$tmpMonth%12;
            $PersonWorkDayGov=$tmpDay;
        }
        if($activeJobObj->JobOrganSubID>0){
            $tmpDay=$personObj->PersonWorkDayMilitary==""?0:(($personObj->PersonWorkDayMilitary+$_time['day'])%30);
            $tmpMonth=$personObj->PersonWorkMonthMilitary==""?$_time['month']+floor(($personObj->PersonWorkDayMilitary+$_time['day'])/30):$personObj->PersonWorkMonthMilitary+$_time['month']+floor(($personObj->PersonWorkDayMilitary+$_time['day'])/30);
            $PersonWorkYearMilitary=$personObj->PersonWorkYearMilitary==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearMilitary+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthMilitary=$tmpMonth%12;
            $PersonWorkDayMilitary=$tmpDay;
        }
        if($activeJobObj->JobOrganID>1){
            $tmpDay=$personObj->PersonWorkDayCompany==""?0:(($personObj->PersonWorkDayCompany+$_time['day'])%30);
            $tmpMonth=$personObj->PersonWorkMonthCompany==""?$_time['month']+floor(($personObj->PersonWorkDayCompany+$_time['day'])/30):$personObj->PersonWorkMonthCompany+$_time['month']+floor(($personObj->PersonWorkDayCompany+$_time['day'])/30);
            $PersonWorkYearCompany=$personObj->PersonWorkYearCompany==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearCompany+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthCompany=$tmpMonth%12;
            $PersonWorkDayCompany=$tmpDay;
        }
    }
    /*** Salary ***/
    $tmpObj= \Humanres\PersonSalaryClass::getInstance(isset($_salaryList[0])?$_salaryList[0]:[]);
    $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_refSalaryDegreeList[$tmpObj->SalaryDegreeID])?$_refSalaryDegreeList[$tmpObj->SalaryDegreeID]:[]);
    $_condObj=\Humanres\ReferenceClass::getInstance(isset($_refSalaryCondList[$tmpObj->SalaryConditionID])?$_refSalaryCondList[$tmpObj->SalaryConditionID]:[]);
    $_eduObj=\Humanres\ReferenceClass::getInstance(isset($_refSalaryEduList[$tmpObj->SalaryEduID])?$_refSalaryEduList[$tmpObj->SalaryEduID]:[]);
    
    
    
    
    
    $_titless="";
    if($tmpObj->SalaryDegreeID>0){
        $_titless.=$_degreeObj->RefDegreeTitle;
    }
    if($tmpObj->SalaryConditionID>0){
        $_titless.=($_titless!=""?", ":"").$_condObj->RefConditionTitle;
    }
    if($tmpObj->SalaryEduID>0){
        $_titless.=($_titless!=""?", ":"").$_eduObj->RefEduTitle;
    }
    
    if($PersonWorkYearGov>=26){
        $_titless.=($_titless!=""?", ":"")."Төрийн албан хаасан хугацааны нэмэгдэл - 25%";
    }elseif($PersonWorkYearGov>=21){
        $_titless.=($_titless!=""?", ":"")."Төрийн албан хаасан хугацааны нэмэгдэл - 20%";
    }elseif($PersonWorkYearGov>=16){
        $_titless.=($_titless!=""?", ":"")."Төрийн албан хаасан хугацааны нэмэгдэл - 15%";
    }elseif($PersonWorkYearGov>=11){
        $_titless.=($_titless!=""?", ":"")."Төрийн албан хаасан хугацааны нэмэгдэл - 10%";
    }elseif($PersonWorkYearGov>=5){
        $_titless.=($_titless!=""?", ":"")."Төрийн албан хаасан хугацааны нэмэгдэл - 5%";
    }
    $_records['salary'] =[
        "SalaryValue"=>$tmpObj->SalaryValue,
        "SalaryDegreeTitle"=>$_degreeObj->RefDegreeTitle,
        "SalaryConditionTitle"=>$_condObj->RefConditionTitle,
        "SalaryEduTitle"=>$_eduObj->RefEduTitle,
        "SalaryTitle"=>$_titless
    ];
    /*** Job ***/
    $_data=[];
    $_job_first_all="";
    $_job_first_military="";
    foreach ($_jobList as $row){
        $tmpObj= \Humanres\PersonJobClass::getInstance($row);
        $_organObj=\Humanres\ReferenceClass::getInstance(isset($_refOrganList[$tmpObj->JobOrganID])?$_refOrganList[$tmpObj->JobOrganID]:[]);
        $_organSubObj=\Humanres\ReferenceClass::getInstance(isset($_refOrganSubList[$tmpObj->JobOrganSubID])?$_refOrganSubList[$tmpObj->JobOrganSubID]:[]);
        $_positionObj=\Humanres\ReferenceClass::getInstance(isset($_refPositionList[$tmpObj->JobPositionID])?$_refPositionList[$tmpObj->JobPositionID]:[]);
        if($_job_first_all==""){
            $_job_first_all=$tmpObj->JobDateStart;
        }
        if($_job_first_military=="" && $tmpObj->JobOrganSubID>0){
            $_job_first_military=$tmpObj->JobDateStart;
        }
        $_data[]=[
            "JobOrganTitle"=>$tmpObj->JobOrganTitle,
            "JobDepartmentTitle"=>$tmpObj->JobDepartmentTitle,
            "JobPositionTitle"=>($tmpObj->JobPositionID>0?$_positionObj->RefPositionTitle:"")." ".$tmpObj->JobPositionTitle,
            "JobDateRange"=>$tmpObj->JobDateStart.(!$tmpObj->JobIsNow?" - ".$tmpObj->JobDateQuit:""),
        ];
    }
    $_records['job'] =$_data;
    $_records['general']=array_merge($_records['general'],[
        'JobFirstDate'=>$_job_first_all,
        'JobFirstMilitaryDate'=>$_job_first_military,
        'JobYearsAll'=>\System\Util::formatDaysMonth($PersonWorkYearAll,$PersonWorkMonthAll,$PersonWorkDayAll),
        'JobYearsGov'=>\System\Util::formatDaysMonth($PersonWorkYearGov,$PersonWorkMonthGov,$PersonWorkDayGov),
        'JobYearsMilitary'=>\System\Util::formatDaysMonth($PersonWorkYearMilitary,$PersonWorkMonthMilitary,$PersonWorkDayMilitary),
        'JobYearsCompany'=>\System\Util::formatDaysMonth($PersonWorkYearCompany,$PersonWorkMonthCompany,$PersonWorkDayCompany)
    ]);
    /*** Job Rank ***/
    $_data=[];
    foreach ($_posRankList as $row){
        $tmpObj= \Humanres\PersonPosRankClass::getInstance($row);
        $_posrankObj=\Humanres\ReferenceClass::getInstance(isset($_refPosrankList[$tmpObj->PosRankID])?$_refPosrankList[$tmpObj->PosRankID]:[]);
        
        $_data[]=[
            "PosRankTitle"=>$_posrankObj->RefRankTitle,
            "PosDate"=>$tmpObj->PosDate,
            "PosNumber"=>$tmpObj->PosNumber,
        ];
    }
    $_records['posrank'] =$_data;
    /*** Education ***/
    $_data=[];
    foreach ($_educationList as $row){
        $tmpObj= \Humanres\PersonEducationClass::getInstance($row);
        
        $_levelObj=\Humanres\ReferenceClass::getInstance(isset($_refLevelList[$tmpObj->EducationLevelID])?$_refLevelList[$tmpObj->EducationLevelID]:[]);
        $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_refDegreeList[$tmpObj->EducationDegreeID])?$_refDegreeList[$tmpObj->EducationDegreeID]:[]);
        $_schoolObj=\Humanres\ReferenceClass::getInstance(isset($_refSchoolList[$tmpObj->EducationSchoolID])?$_refSchoolList[$tmpObj->EducationSchoolID]:[]);
        
        $_data[]=[
            "EducationSchoolTitle"=>$_schoolObj->RefSchoolTitle,
            "EducationSchoolTitle"=>$tmpObj->EducationSchoolTitle,
            "EducationDateStart"=>$tmpObj->EducationDateStart,
            "EducationDateEnd"=>!$tmpObj->EducationIsNow?$tmpObj->EducationDateEnd:"",
            "EducationLevelTitle"=>$_levelObj->RefLevelTitle,
            "EducationProfession"=>$tmpObj->EducationProfession,
            "EducationDegreeTitle"=>$_degreeObj->RefDegreeTitle,
            "EducationLicence"=>$tmpObj->EducationLicence,
        ];
    }
    $_records['education'] =$_data;
    
    /*** Award ***/
    $_data=[];
    foreach ($_awardList as $row){
        $tmpObj= \Humanres\PersonAwardClass::getInstance($row);
        $_awardObj=\Humanres\ReferenceClass::getInstance(isset($_refAwardList[$tmpObj->AwardRefID])?$_refAwardList[$tmpObj->AwardRefID]:[]);
        $_awardSubObj=\Humanres\ReferenceClass::getInstance(isset($_refAwardList[$tmpObj->AwardRefSubID])?$_refAwardList[$tmpObj->AwardRefSubID]:[]);
        
        $_title="";
        if(in_array($tmpObj->AwardRefID, [4])){
            $_title=$tmpObj->AwardOrganTitle; 
		}
		if(in_array($tmpObj->AwardRefID, [1,3])){
		    $_title.=$_awardSubObj->RefAwardID>0?$_awardSubObj->RefAwardTitle:$tmpObj->AwardTitle;
		}else{
		    $_title.=$tmpObj->AwardTitle;
		}
        
        $_data[]=[
            "AwardRefTitle"=>$_awardObj->RefAwardTitle.($_awardSubObj->RefAwardID>0 && $tmpObj->AwardRefID==2?", ".$_awardSubObj->RefAwardTitle:""),
            "AwardTitle"=>$_title,
            "AwardDate"=>$tmpObj->AwardDate,
            "AwardLicence"=>$tmpObj->AwardLicence,
        ];
    }
    $_records['award'] =$_data;
    /*** Punishment ***/
    $_data=[];
    foreach ($_punishmentList as $row){
        $tmpObj= \Humanres\PersonPunishmentClass::getInstance($row);
        $_typeObj=\Humanres\ReferenceClass::getInstance(isset($_refPunishTypeList[$tmpObj->PunishmentRefID])?$_refPunishTypeList[$tmpObj->PunishmentRefID]:[]);
        
        $_data[]=[
            "PunishmentRefTitle"=>$_typeObj->RefPunishmentTitle,
            "PunishmentOrder"=>$tmpObj->PunishmentOrder,
            "PunishmentOrderDate"=>$tmpObj->PunishmentOrderDate,
            "PunishmentReason"=>$tmpObj->PunishmentReason.($_typeObj->RefPunishmentID==2?" Хугацаа: ".$tmpObj->PunishmentTime." сар, Хувь: ".$tmpObj->PunishmentPercent."%":""),
        ];
    }
    $_records['punishment'] =$_data;
    /*** Family ***/
    $_data=[];
    foreach ($_familyList as $row){
        $tmpObj= \Humanres\PersonFamilyClass::getInstance($row);
        
        $_relationObj=\Humanres\ReferenceClass::getInstance(isset($_refRelationList[$tmpObj->FamilyRelationID])?$_refRelationList[$tmpObj->FamilyRelationID]:[]);
        $_jobObj=\Humanres\ReferenceClass::getInstance(isset($_refJobList[$tmpObj->FamilyJobTypeID])?$_refJobList[$tmpObj->FamilyJobTypeID]:[]);
        $_cityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->FamilyBirthCityID])?$_areaList[$tmpObj->FamilyBirthCityID]:[]);
        $_districtObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->FamilyBirthDistrictID])?$_areaList[$tmpObj->FamilyBirthDistrictID]:[]);
        
        $_data[]=[
            "FamilyRelationTitle"=>$_relationObj->RefRelationTitle,
            "FamilyLastName"=>$tmpObj->FamilyLastName,
            "FamilyFirstName"=>$tmpObj->FamilyFirstName,
            "FamilyBirthDate"=>$tmpObj->FamilyBirthDate,
            "FamilyBirthPlace"=>$_cityObj->AreaName.", ".$_districtObj->AreaName,
            "FamilyRefTypeTitle"=>$_jobObj->RefTypeTitle,
            "FamilyLastName"=>$tmpObj->FamilyLastName,
            "FamilyJobOrgan"=>$tmpObj->FamilyJobOrgan,
            "FamilyJobPosition"=>$tmpObj->FamilyJobPosition,
        ];
    }
    $_records['family'] =$_data;
    /*** Language ***/
    $_data=[];
    foreach ($_languageList as $row){
        $tmpObj= \Humanres\PersonLanguageClass::getInstance($row);
        
        $_langObj=\Humanres\ReferenceClass::getInstance(isset($_refLangList[$tmpObj->LanguageRefID])?$_refLangList[$tmpObj->LanguageRefID]:[]);
        $_langLevelObj=\Humanres\ReferenceClass::getInstance(isset($_refLangLevelList[$tmpObj->LanguageLevelID])?$_refLangLevelList[$tmpObj->LanguageLevelID]:[]);
        
        $_data[]=[
            "LanguageTitle"=>$_langObj->RefLanguageTitle,
            "LanguageLevelTitle"=>$_langLevelObj->RefLevelTitle,
            "LanguageYears"=>$tmpObj->LanguageYears
        ];
    }
    $_records['language'] =$_data;
    /*** Study ***/
    $_data=[];
    foreach ($_studyList as $row){
        $tmpObj= \Humanres\PersonStudyClass::getInstance($row);
        
        $_directionObj=\Humanres\ReferenceClass::getInstance(isset($_refDirectionList[$tmpObj->StudyDirectionID])?$_refDirectionList[$tmpObj->StudyDirectionID]:[]);
        $_countryObj=\Office\RefCountryClass::getInstance(isset($_refCountryList[$tmpObj->StudyCountryID])?$_refCountryList[$tmpObj->StudyCountryID]:[]);
        
        $_data[]=[
            "StudySchoolTitle"=>$tmpObj->StudySchoolTitle,
            "StudyTitle"=>$tmpObj->StudyTitle,
            "StudyCountryName"=>$_countryObj->CountryName,
            "StudyDateStart"=>$tmpObj->StudyDateStart,
            "StudyDateEnd"=>$tmpObj->StudyDateEnd,
            "StudyDay"=>$tmpObj->StudyDay,
            "StudyRefDirectionTitle"=>$_directionObj->RefDirectionTitle,
            "StudyLicence"=>$tmpObj->StudyLicence,
            "StudyLicenceDate"=>$tmpObj->StudyLicenceDate,
        ];
    }
    $_records['study'] =$_data;
    
    echo json_encode($_records);
    exit;