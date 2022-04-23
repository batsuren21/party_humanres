<?php 
$j=0;
$_all_month=0;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$personObj->PersonLFName);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$allStatObj->PositionFullName);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_jj);
$j++;
/****/
$_month=$personObj->AllPersonWorkMonth>0?$personObj->AllPersonWorkMonth:0;
$_all_month=$_month;
$_day=$personObj->AllPersonWorkDay>0?$personObj->AllPersonWorkDay:0;

$tmpDay=$_day%30;
$tmpMonth=$_month+floor($_day/30);
$_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);

$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
$j++;
/****/
$_sub_id=[2,3,4,5,6];
foreach ($_sub_id as $id){
    $_month=$allStatObj->{"RefOrganMonth".$id}>0?$allStatObj->{"RefOrganMonth".$id}:0;
    $_day=$allStatObj->{"RefOrganDay".$id}>0?$allStatObj->{"RefOrganDay".$id}:0;
    
    $tmpDay=$_day%30;
    $tmpMonth=$_month+floor($_day/30);
    $_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);
    $activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
    $j++;
}
/****/
$_month=$personObj->AllPersonGovMonth>0?$personObj->AllPersonGovMonth:0;
$_day=$personObj->AllPersonGovDay>0?$personObj->AllPersonGovDay:0;

$tmpDay=$_day%30;
$tmpMonth=$_month+floor($_day/30);
$_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);

$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
$j++;
/****/

$_month=$allStatObj->JobOrganSubMonth1>0?$allStatObj->JobOrganSubMonth1:0;
$_day=$allStatObj->JobOrganSubDay1>0?$allStatObj->JobOrganSubDay1:0;
$tmpDay=$_day%30;
$tmpMonth=$_month+floor($_day/30);
$_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);

$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
$j++;
/****/
$_month=$allStatObj->JobOrganSubMonthOther>0?$allStatObj->JobOrganSubMonthOther:0;
$_day=$allStatObj->JobOrganSubDayOther>0?$allStatObj->JobOrganSubDayOther:0;
$tmpDay=$_day%30;
$tmpMonth=$_month+floor($_day/30);
$_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);

$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
$j++;

/****/
$_month=$personObj->AllPersonMilitaryMonth>0?$personObj->AllPersonMilitaryMonth:0;
$_day=$personObj->AllPersonMilitaryDay>0?$personObj->AllPersonMilitaryDay:0;

$tmpDay=$_day%30;
$tmpMonth=$_month+floor($_day/30);
$_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);

$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
$j++;
/****/
foreach ($_jobOrganSubList as $row){
    $objOrgSub=\Humanres\ReferenceClass::getInstance($row);
    
    $_month=$allStatObj->{"RefOrganSubMonth".$objOrgSub->RefOrganSubID}>0?$allStatObj->{"RefOrganSubMonth".$objOrgSub->RefOrganSubID}:0;
    $_day=$allStatObj->{"RefOrganSubDay".$objOrgSub->RefOrganSubID}>0?$allStatObj->{"RefOrganSubDay".$objOrgSub->RefOrganSubID}:0;
    
    $tmpDay=$_day%30;
    $tmpMonth=$_month+floor($_day/30);
    $_days=\System\Util::formatDaysMonth(0,$tmpMonth,$tmpDay);
    $activesheet->setCellValueByColumnAndRow($j,$_row_body,$_days);
    $j++;
}
/****/
$_all_percent=0;
$_salary_percent=0;
foreach ($_salaryPercentList as $rs){
    $refSalaryObj=\Humanres\ReferenceClass::getInstance($rs);
    if($_all_month>= $refSalaryObj->RefPercentStart && $_all_month<= $refSalaryObj->RefPercentEnd){
        $_salary_percent=$refSalaryObj->RefPercent;
        $_all_percent+=$_salary_percent;
    }
}
$_salary_degree=0;
$_salary_cond=0;
$_salary_edu=0;
$refSalDegreeObj=\Humanres\ReferenceClass::getInstance(\Database::getParam($_refSalaryDegreeList,$allStatObj->SalaryDegreeID));
if($refSalDegreeObj->RefDegreeID>0){
    $_salary_degree=$refSalDegreeObj->RefDegreePercent;
}
$refSalCondObj=\Humanres\ReferenceClass::getInstance(\Database::getParam($_refSalaryCondList,$allStatObj->SalaryConditionID));
if($refSalCondObj->RefConditionID>0){
    $_salary_cond=$refSalCondObj->RefConditionPercent;
}
$refSalEdutObj=\Humanres\ReferenceClass::getInstance(\Database::getParam($_refSalaryEduList,$allStatObj->SalaryEduID));
if($refSalEdutObj->RefEduID>0){
    $_salary_edu=$refSalEdutObj->RefEduID;
}
$_all_percent+=$_salary_degree;
$_all_percent+=$_salary_cond;
$_all_percent+=$_salary_edu;

/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_all_percent);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$allStatObj->SalaryValue);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_salary_degree);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_salary_cond);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_salary_edu);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_salary_percent);
$j++;
/****/
$_row_body++;