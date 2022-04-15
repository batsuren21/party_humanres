<?php
if(!\Office\Permission::isLoginPerson()){
    $result['_state'] = false;
    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}
$q=isset($_GET['q'])?$_GET['q']:"";
$type=isset($_GET['type'])?$_GET['type']:"";
if($q==""){
    $result['total_count'] = 0;
    $result['incomplete_results'] = false;
    $result['items'] = [];
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}
$length=30;
$page=isset($_GET['page'])?(int)$_GET['page']:1;
$mainObj=new \Office\EntityClass();
$search=["entity_name"=>$q];
if($type!="" && $type>0){
    $search=array_merge($search,["entity_typeid"=>$type]);
}

$result=[];
$data=[];
$iTotalRecords = $mainObj->getRowCount($search);

$_resultList=$mainObj->getRowList(array_merge($search,array("orderby"=>array("EntityName"),"rowstart"=>(($page-1)*$length),"rowlength"=>$length)));

foreach ($_resultList as $j => $row){
    $entityObj= \Office\EntityClass::getInstance($row);
    $data[]=['id'=>$entityObj->EntityID,'text'=>$entityObj->EntityName];
}
$result['total_count'] = $iTotalRecords;
$result['incomplete_results'] = false;
$result['items'] = $data;
header("Content-type: application/json");
echo json_encode($result);
exit;