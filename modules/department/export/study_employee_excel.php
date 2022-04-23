<?php
if(!\Office\Permission::isLoginPerson()){
    $_records["responseText"] = "Та өөрийн эрхээр нэвтрээгүй байна";
    echo json_encode($_records);
    exit;
}

$_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
if($_officeid<1){
    $_records["responseText"] = "Жагсаалт харуулах боломжгүй байна. Та системийн админтай холбоо барина уу";
    echo json_encode($_records);
    exit;
}
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REPORT);
if(!$_priv_reg){
    $_records["responseText"] = "Танд файлаар авах эрх байхгүй байна.";
    echo json_encode($_records);
    exit;
}
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Ulaanbaatar');

function strConvert($string){
    return html_entity_decode($string);
}

$searchdata=isset($_POST['searchdata'])?$_POST['searchdata']:array();
if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');

require_once DRF.'/libraries/excel/PHPExcel.php';

$now=new \DateTime();
$__con=\Database::instance();

if(isset($searchdata['datestart']) && $searchdata['datestart']=="" || !isset($searchdata['datestart'])){
    $searchdata['humanres_datestart']=$now->format("Y")."-01-01";
}else {
    $searchdata['humanres_datestart']=$searchdata['datestart'];
}
if(isset($searchdata['dateend']) && $searchdata['dateend']=="" || !isset($searchdata['dateend'])){
    $searchdata['humanres_dateend']=$now->format("Y-m-d");
    $_date_main=$searchdata['humanres_dateend'];
}else {
    $searchdata['humanres_dateend']=$searchdata['dateend'];
    $_date_main=$searchdata['dateend'];
}

$objPHPExcel= PHPExcel_IOFactory::load(dirname(__FILE__)."/template/report_study_employee.xlsx");
$objPHPExcel->getProperties()->setCreator("Smart Office")
->setLastModifiedBy("Smart Office")
->setTitle("Smart Office XLSX Document")
->setSubject("Smart Office XLSX Document")
->setDescription("")
->setKeywords("Smart Office")
->setCategory("");

$activesheet=$objPHPExcel->getActiveSheet();

$font_size=8;
$font_name="Arial";

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
    ),
    'font' => array(
        'size' => $font_size,
        'name'  => $font_name
    ),
);
$_periodid="";
if(isset($_SESSION[SESSSYSINFO]->PeriodID)){
    $_periodid=$_SESSION[SESSSYSINFO]->PeriodID;
}

$refObj=\Humanres\ReferenceClass::getInstance();

$_directionList=$refObj->getRowList(["_mainindex"=>"RefDirectionID","orderby"=>"RefDirectionOrder"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION);
$_listSub=$refObj->getRowList(["_mainindex"=>"RefDirSubID","orderby"=>"RefDirSubOrder"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB);
$_list1Sub=$refObj->getRowList(["_mainindex"=>"RefDirSub1ID","orderby"=>"RefDirSub1Order"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB1);

$_dirids=[];
$_dirsubids=[];
$_dirsub1ids=[];
foreach ($_listSub as $r){
    if($r['RefDirSubDirectionID']>0){
        $_dir=$r['RefDirSubDirectionID'];
        if(isset($_directionList[$_dir]['subids'])){
            $_directionList[$_dir]['subids'][]=$r['RefDirSubID'];
        }else{
            $_directionList[$_dir]['subids']=[$r['RefDirSubID']];
        }
    }
}
foreach ($_list1Sub as $r){
    $_dirsub1ids[]=$r['RefDirSub1ID'];
    if($r['RefDirSub1DirectionID']>0){
        $_dir=$r['RefDirSub1DirectionID'];
        if(isset($_directionList[$_dir]['sub1ids'])){
            $_directionList[$_dir]['sub1ids'][]=$r['RefDirSub1ID'];
        }else{
            $_directionList[$_dir]['sub1ids']=[$r['RefDirSub1ID']];
        }
    }
    if($r['RefDirSub1DirSubID']>0){
        $_dir=$r['RefDirSub1DirSubID'];
        if(isset($_listSub[$_dir]['subids'])){
            $_listSub[$_dir]['subids'][]=$r['RefDirSub1ID'];
        }else{
            $_listSub[$_dir]['subids']=[$r['RefDirSub1ID']];
        }
    }
}

foreach ($_directionList as $r){
    if(!isset($r['subids']) && !isset($r['sub1ids'])){
        $_dirids[]=$r['RefDirectionID'];
    } 
}
foreach ($_listSub as $r){
    if(!isset($r['subids'])){
        $_dirsubids[]=$r['RefDirSubID'];
    }
}
$stat_listAll=\Humanres\HumanresStatisticClass::getInstance()->getStudyEmployee($searchdata,
    ["col_dirids"=>$_dirids,"col_dirsubids"=>$_dirsubids,"col_dirsub1ids"=>$_dirsub1ids],1);

$_count_row=1;
$_count_header=33;

require_once 'study_employee_excel_body.php';

$activesheet->setTitle('Сургалт');

$objPHPExcel->setActiveSheetIndex(0);

$file = "Сургалтад хамрагдсан албан хаагч ".$_date_main.'.xlsx';

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_start();
$objWriter->save("php://output");
$xlsData = ob_get_contents();
ob_end_clean();

$response =  array(
    '_state'=>true,
    'filename' => $file,
    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
);
die(json_encode($response));
exit;