<?php
if(!\Office\Permission::isLoginPerson()){
    $result['_state'] = false;
    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}
$__con = new Database();

$_data=array("colors"=>array(),"data"=>array());
$__departmentid= isset($_GET['departmentid'])?$_GET['departmentid']:"";

$_departmentObj=\Humanres\DepartmentClass::getInstance();
if($__departmentid>0){
    $_departmentObj=\Humanres\DepartmentClass::getInstance()->getRow([
        'department_id'=>$__departmentid
    ]);
}

$param_agelist=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"AgeOrder"],\Humanres\ReferenceClass::TBL_AGE);
$now=new \DateTime();
$_date=$now->format("Y-m-d");
$_sub_age="( CASE";
foreach ($param_agelist as $tmp){
    $_yearstart=$tmp['AgeStart'];
    $_yearend=$tmp['AgeEnd']+1;
    
    $start_date = date('Y-m-d', strtotime($_date. '-'.$_yearstart.' year'));
    $end_date = date('Y-m-d', strtotime($_date. '-'.$_yearend.' year'));
    if($_yearend>100){
        $_sub_age.=" WHEN TF.PersonBirthDate <= '".$start_date."' THEN ".$tmp['AgeID'];
    }else{
        $_sub_age.=" WHEN TF.PersonBirthDate <= '".$start_date."' AND TF.PersonBirthDate > '".$end_date."' THEN ".$tmp['AgeID'];
    }
}
$_sub_age.=" END ) as PersonAge";

$qry_sub=" SELECT TF.PersonID, $_sub_age";
$qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TEmp on TF.PersonEmployeeID=TEmp.EmployeeID";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TPos on  TEmp.EmployeePositionID=TPos.PositionID";
$qry_sub.=" and TPos.PositionClassID=1";

if($_departmentObj->isExist()){
    $qry_sub.=" and TPos.PositionDepartmentID=".$_departmentObj->DepartmentID;
}

$qry="
    select T.PersonAge, COUNT(T.PersonID) as AllCountAge
    from (".$qry_sub.") T
    group by T.PersonAge
";
$result = $__con->select($qry);
$_ageCount=\Database::getList($result,["_mainindex"=>"PersonAge"]);

$_data=array("colors"=>array("#c05b17","#008a00","#fb6800","#87784d","#633630","#f1a30b","#00356b","#ce352d","#333333","#58169a","#4390e0"),"data"=>array());

foreach ($param_agelist as $r){
    $_val=isset($_ageCount[$r['AgeID']])?$_ageCount[$r['AgeID']]['AllCountAge']:0;
    $_tmp=["column"=>$r['AgeTitle'],"value"=>$_val,'color'=>"#000000"];
    $_data["data"][]=$_tmp;
}
echo json_encode($_data);
exit;