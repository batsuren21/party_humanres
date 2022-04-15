<?php
if(!\Office\Permission::isLoginPerson()){
    $result['_state'] = false;
    $result['_errors'] = array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Таны SESSION тасарсан тул та системд дахин шинээр нэвтэрнэ үү")));
    header("Content-type: application/json");
    echo json_encode($result);
    exit;
}
$q=isset($_GET['q'])?$_GET['q']:"";
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
$mainObj=new \Office\LetterClass();
$search=["letter_regnumber"=>$q,"letter_sentletterid"=>0,"letter_statusid"=>\Office\LetterClass::LETTER_DECIDED];

$result=[];
$data=[];
$iTotalRecords = $mainObj->getRowCount($search);

$_resultList=$mainObj->getRowList(array_merge($search,array("orderby"=>array("LetterRegisterNumberFull"),"rowstart"=>(($page-1)*$length),"rowlength"=>$length)));

foreach ($_resultList as $j => $row){
    $letterObj= \Office\LetterClass::getInstance($row);
    $data[]=['id'=>$letterObj->LetterID,'text'=>$letterObj->LetterRegisterNumberFull,'intro'=>$letterObj->LetterIntro];
}
$result['total_count'] = $iTotalRecords;
$result['incomplete_results'] = false;
$result['items'] = $data;
header("Content-type: application/json");
echo json_encode($result);
exit;