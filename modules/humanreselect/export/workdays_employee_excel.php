<?php
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Ulaanbaatar');

function strConvert($string){
    return html_entity_decode($string);
}

$_type_list=[
    1=>['id'=>1,'title'=>"Улсад ажилласан жил"],
    2=>['id'=>2,'title'=>"Төрд ажилласан жил"],
    3=>['id'=>3,'title'=>"Аж ахуйн нэгжид ажилласан жил"],
    4=>['id'=>4,'title'=>"АТГ-т ажилласан жил"]
];

$searchdata=isset($_POST['searchdata'])?$_POST['searchdata']:array();
if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');

require_once DRF.'/libraries/excel/PHPExcel.php';

$now=new \DateTime();

if(isset($searchdata['dateend']) && $searchdata['dateend']=="" || !isset($searchdata['dateend'])){
    $searchdata['humanres_dateend']=$now->format("Y")."-01-01";
    $_date_main=$searchdata['humanres_dateend'];
}else {
    $searchdata['humanres_dateend']=$searchdata['dateend'];
    $_date_main=$searchdata['dateend'];
}

$objPHPExcel= PHPExcel_IOFactory::load(dirname(__FILE__)."/template/report_workyears_employee.xlsx");
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
$_jobOrganList=$refObj->getRowList(['orderby'=>['RefOrganOrder']],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
$_jobOrganSubList=$refObj->getRowList(["ref_type"=>[1,2],'orderby'=>['RefOrganSubOrder']],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);

$_salaryPercentList=$refObj->getRowList(["orderby"=>"RefPercentOrder"],\Humanres\ReferenceClass::TBL_SALARY_PERCENT);
$_refSalaryDegreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_SALARY_DEGREE);
$_refSalaryCondList=$refObj->getRowList(["_mainindex"=>"RefConditionID"],\Humanres\ReferenceClass::TBL_SALARY_CONDITION);
$_refSalaryEduList=$refObj->getRowList(["_mainindex"=>"RefEduID"],\Humanres\ReferenceClass::TBL_SALARY_EDU);

$stat_listAll=\Humanres\HumanresStatisticClass::getInstance()->getWorkDaysEmployee($searchdata,
    ["col_organlist"=>$_jobOrganList,"col_organsublist"=>$_jobOrganSubList],1);

$_count_row=1;
$_count_header=28;

require_once 'workdays_employee_excel_body.php';

$activesheet->setTitle('Ажилласан жил');

$objPHPExcel->setActiveSheetIndex(0);

$file = "Ажилласан жил албан хаагчаар ".$_date_main.'.xlsx';

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