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
if(!isset($__class) || !isset($_type_list[$__class])){
    $_records["responseText"] = "Тайлан татах боломжгүй байна";
    echo json_encode($_records);
    exit;
}

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

$objPHPExcel= PHPExcel_IOFactory::load(dirname(__FILE__)."/template/report_workyears".$__class.".xlsx");
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
// $_resList=\Humanres\DepartmentClass::getInstance()->getRowList([
//     "_getparams"=>['DepartmentID'],
//     'department_periodid'=>$_periodid,
//     "department_typeid"=>1,
//     'orderby'=>"DepartmentOrder"]);
// $departmentList=isset($_resList['_list'])?$_resList['_list']:[];
// $_depids=isset($_resList['DepartmentID'])?$_resList['DepartmentID']:[];

// $_listResult=Humanres\EmployeeClass::getInstance()->getRowList(array(
//     "_getparams"=>['EmployeeID'],
//     "_select"=>array("T3.DepartmentID, T3.DepartmentFullName, T.EmployeeID, T2.PersonLastName,T2.PersonFirstName, T1.PositionName"),
//     "employee_get_table"=>6,
//     'department_periodid'=>$_periodid,
//     'department_typeid'=>1,
//     'department_classid'=>4,
//     "employee_isactive"=>1,
//     "orderby"=>array("T3.DepartmentOrder, T1.PositionOrder, T2.PersonFirstName")));

// $_listEmployee=isset($_listResult['_list'])?$_listResult['_list']:[];
// $_employeeids=isset($_listResult['EmployeeID'])?$_listResult['EmployeeID']:[];


$_time_list=\Humanres\HumanresStatisticClass::$_time_list;

$_positionClassList=\Humanres\ReferenceClass::getInstance()->getRowList(['orderby'=>['RefClassOrder']],\Humanres\ReferenceClass::TBL_POSITION_CLASS);
$_positionDegreeList=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_id"=>[1,2,3,4,5],'orderby'=>['RefDegreeOrder']],\Humanres\ReferenceClass::TBL_POSITION_DEGREE);
$_eduRankList=\Humanres\ReferenceClass::getInstance()->getRowList(['ref_id'=>[4,5],'orderby'=>['RefRankOrder desc']],\Humanres\ReferenceClass::TBL_EDUCATION_RANK);
$_eduLevelList=\Humanres\ReferenceClass::getInstance()->getRowList(['orderby'=>['RefLevelOrder desc']],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
$_ageList=\Humanres\ReferenceClass::getInstance()->getRowList(['orderby'=>['AgeOrder']],\Humanres\ReferenceClass::TBL_AGE);

$stat_listAll1=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefTypeClass","par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],1,$__class);
$stat_listAll2=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefTypeClass","par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],2,$__class);
$stat_listAll3=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefDegree","position_degreeid"=>[1,2,3,4,5],"par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],3,$__class);
$stat_listAll4=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefEduRank","par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],4,$__class);
$stat_listAll5=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefEduLevel","par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],5,$__class);
$stat_listAll6=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefAge","par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,"param_agelist"=>$_ageList],6,$__class);
$stat_listAll7=\Humanres\HumanresStatisticClass::getInstance()->getWorkDays(array_merge($searchdata,["_mainindex"=>"RefSoldier","person_issoldering"=>1,"par_period"=>$_periodid]),
    ["col_timelist"=>$_time_list,],7,$__class);

$_count_row=1;
$_count_header=30;

require_once 'workdays_excel_body.php';

$activesheet->setTitle('Ажилласан жил');

$objPHPExcel->setActiveSheetIndex(0);

$file = $_type_list[$__class]['title'].$_date_main.'.xlsx';

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