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

if(isset($searchdata['dateend']) && $searchdata['dateend']=="" || !isset($searchdata['dateend'])){
    $searchdata['humanres_dateend']=$now->format("Y")."-01-01";
    $_date_main=$searchdata['humanres_dateend'];
}else {
    $searchdata['humanres_dateend']=$searchdata['dateend'];
    $_date_main=$searchdata['dateend'];
}

$objPHPExcel= PHPExcel_IOFactory::load(dirname(__FILE__)."/template/report_award_employee.xlsx");
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

$qry=" select T1.*";
$qry.=" from ".DB_DATABASE_HUMANRES.".".\Humanres\ReferenceClass::TBL_AWARD." T";
$qry.=" left join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_AWARD." T1 on T.RefAwardID=T1.RefAwardParentID";
$qry.=" WHERE T1.`RefAwardID` IS NOT NULL";
$qry.=" order by T.RefAwardOrder, T1.RefAwardOrder";
$result = $__con->select($qry);
$_awardSubList=\Database::getList($result);
$_awardList=$refObj->getRowList(["ref_parentid"=>0,"orderby"=>"RefAwardOrder"],\Humanres\ReferenceClass::TBL_AWARD);

$stat_listAll=\Humanres\HumanresStatisticClass::getInstance()->getAwardEmployee($searchdata,
    ["col_awardlist"=>$_awardList,"col_awardsublist"=>$_awardSubList],1);

$_count_row=1;
$_count_header=50;

require_once 'award_employee_excel_body.php';

$activesheet->setTitle('Шагнал');

$objPHPExcel->setActiveSheetIndex(0);

$file = "Шагнал, урамшуулал ".$_date_main.'.xlsx';

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