<?php
if(!\Office\Permission::isLoginPerson()){
    $result['_state'] = false;
    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}

$_data=array("colors"=>array(),"data"=>array());
$__departmentid= isset($_GET['departmentid'])?$_GET['departmentid']:"";

$_departmentObj=\Humanres\DepartmentClass::getInstance();
if($__departmentid>0){
    $_departmentObj=\Humanres\DepartmentClass::getInstance()->getRow([
        'department_id'=>$__departmentid
    ]);
}
$_srch=[
    "person_get_table"=>1,
    "position_classid"=>1,
];
if($_departmentObj->isExist()){
    $_srch=array_merge($_srch,['department_id'=>$_departmentObj->DepartmentID]);
}
$_srch=array_merge($_srch,["_select"=>['PersonGender','COUNT(PersonID) as AllCountGender'],'_mainindex'=>"PersonGender",'groupby'=>["PersonGender"]]);

$_genderCount=\Humanres\PersonClass::getInstance()->getRowList($_srch);


$_data=array("colors"=>array("#c05b17","#008a00","#fb6800","#87784d","#633630","#f1a30b","#00356b","#ce352d","#333333","#58169a","#4390e0"),"data"=>array());

$_tmp=["column"=>"Эрэгтэй","value"=>$_genderCount[1]['AllCountGender'],'color'=>"#000000"];
$_data["data"][]=$_tmp;
$_tmp=["column"=>"Эмэгтэй","value"=>$_genderCount[0]['AllCountGender'],'color'=>"#000000"];
$_data["data"][]=$_tmp;


echo json_encode($_data);
exit;