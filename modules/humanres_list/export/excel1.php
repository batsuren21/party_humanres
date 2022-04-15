<?php
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Ulaanbaatar');

function strConvert($string){
    return html_entity_decode($string);
}

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

$_priv_list_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_LIST);
if(!$_priv_list_access){
    $_records["responseText"] = "Танд файлаар авах эрх байхгүй байна.";
    echo json_encode($_records);
    exit;
}
$searchdata=isset($_POST['searchdata'])?$_POST['searchdata']:array();
$_title=isset($_POST['title']) && $_POST['title']!=""?$_POST['title']:"Жагсаалт";

if (PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');
    
require_once DRF.'/libraries/excel/PHPExcel.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Smart Office")
->setLastModifiedBy("Smart Office")
->setTitle("Smart Office XLSX Document")
->setSubject("Smart Office XLSX Document")
->setDescription("")
->setKeywords("Smart Office")
->setCategory("");
    
 
$activesheet=$objPHPExcel->getActiveSheet();

$font_size=10;
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

$array_header=array(
    array('title'=>"Д/д","type"=>"Num",'width'=>5),
    array('title'=>"Бүртгэсэн огноо","value"=>"PersonCreateDate",'width'=>15),
    array('title'=>"Регистрийн №","value"=>"PersonRegisterNumber",'width'=>15),
    array('title'=>"Нэгж","value"=>"DepartmentName",'width'=>15),
    array('title'=>"Албан тушаал","value"=>"PositionName",'width'=>15),
    array('title'=>"Ургийн овог","value"=>"PersonMiddleName",'width'=>20),
    array('title'=>"Эцэг, эхийн нэр","value"=>"PersonLastName",'width'=>20),
    array('title'=>"Өөрийн нэр","value"=>"PersonFirstName",'width'=>20),
    array('title'=>"Төрсөн огноо","value"=>"PersonBirthDate",'width'=>10),
    array('title'=>"Хүйс","type"=>"Gender",'width'=>10),
    array('title'=>"Боловсрол","type"=>"EduLevel",'width'=>15),
    array('title'=>"Яс үндэс","type"=>"Ehtnic",'width'=>15),
    array('title'=>"Төрсөн аймаг, нийслэл","type"=>"BirthCity",'width'=>15),
    array('title'=>"Төрсөн сум, дүүрэг","type"=>"BirthDistrict",'width'=>15),
    array('title'=>"Оршин суугаа хаяг аймаг, нийслэл","type"=>"AddressCity",'width'=>15),
    array('title'=>"Сум, дүүрэг","type"=>"AddressDistrict",'width'=>15),
    array('title'=>"Цэргийн жинхэнэ алба хаасан эсэх","type"=>"IsSoldier",'width'=>10),
    array('title'=>"Цэргийн үүрэгтний үнэмлэх №","value"=>"PersonSoldierPassNo",'width'=>20),
    array('title'=>"Цэргийн жинхэнэ алба хаасан он","value"=>"PersonSoldierYear",'width'=>10),
    array('title'=>"Цэргийн жинхэнэ алба хаасан байдал","type"=>"SoldierType",'width'=>20),
    array('title'=>"Улсад ажилласан жил","type"=>"WorkYearAll",'width'=>20),
    array('title'=>"Төрд ажилласан жил","type"=>"WorkYearGov",'width'=>20),
    array('title'=>"Цэргийн албанд ажилласан жил","type"=>"WorkYearMilitary",'width'=>20),
    array('title'=>"Аж ахуй нэгжид ажилласан жил","type"=>"WorkYearCompany",'width'=>20),
);
$first_letter = PHPExcel_Cell::stringFromColumnIndex(0);
$last_letter = PHPExcel_Cell::stringFromColumnIndex(count($array_header)-1);
$header_range = "{$first_letter}1:{$last_letter}1";

$objPHPExcel->setActiveSheetIndex(0)->mergeCells($header_range);
$activesheet->setCellValueByColumnAndRow(0, 1, "");
for($j=0; $j<count($array_header); $j++){
    $col_letter = PHPExcel_Cell::stringFromColumnIndex($j);
    $activesheet->setCellValueByColumnAndRow($j, 2, $array_header[$j]['title']);
    $objPHPExcel->getActiveSheet()->getColumnDimension($col_letter)->setWidth($array_header[$j]['width']);
}

$objPHPExcel->getActiveSheet()->getStyle("{$first_letter}2:{$last_letter}2")->getAlignment()->setWrapText(true)->setShrinkToFit(true);
$objPHPExcel->getActiveSheet()->getStyle("{$first_letter}1:{$last_letter}1")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle("{$first_letter}2:{$last_letter}2")->applyFromArray($styleArray);


$now=new \DateTime();


$refObj=\Humanres\ReferenceClass::getInstance();
$_eduLevelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
$_ethnicList=$refObj->getRowList(["_mainindex"=>"RefEthnicID"],\Humanres\ReferenceClass::TBL_ETHNICITY);
$_areaList=\Office\AreaClass::getInstance()->getRowList(["_mainindex"=>"AreaID"]);
$_soldierList=$refObj->getRowList(["_mainindex"=>"RefSoldierID"],\Humanres\ReferenceClass::TBL_SOLDIER);

$mainObj=new \Humanres\PersonClass();
$search=$searchdata;

$_resultList=$mainObj->getRowList(array_merge($search,array(
    "_getparams"=>['PersonID'],
    "person_get_table"=>1,
    "orderby"=>array("T3.DepartmentOrder, T1.PositionOrder"))));

$_personids=isset($_resultList['PersonID'])?array_unique(array_filter($_resultList['PersonID'])):[];
$_mainList=isset($_resultList['_list'])?$_resultList['_list']:[];
$activeJobList=[];
if(count($_personids)>0){
    $activeJobList=\Humanres\PersonJobClass::getInstance()->getRowList([
        "_mainindex"=>"JobPersonID",
        "job_personid"=>$_personids,
        "job_isnow"=>1,
    ]);
}

$i=3;
foreach ($_mainList as $j => $row){
    $objPHPExcel->getActiveSheet()->getStyle("{$first_letter}{$i}:{$last_letter}{$i}")->applyFromArray($styleArray);
   
    $tmpObj= \Humanres\PersonClass::getInstance($row);
    $rownum= ($j + 1);
    
    $eduObj=\Humanres\ReferenceClass::getInstance(isset($_eduLevelList[$tmpObj->PersonEducationLevelID])?$_eduLevelList[$tmpObj->PersonEducationLevelID]:"");
    $ethnicObj=\Humanres\ReferenceClass::getInstance(isset($_ethnicList[$tmpObj->PersonEthnicID])?$_ethnicList[$tmpObj->PersonEthnicID]:"");
    $soldierObj=\Humanres\ReferenceClass::getInstance(isset($_soldierList[$tmpObj->PersonSoldierID])?$_soldierList[$tmpObj->PersonSoldierID]:"");
    $birthCityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonBirthCityID])?$_areaList[$tmpObj->PersonBirthCityID]:"");
    $birthSumObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonBirthDistrictID])?$_areaList[$tmpObj->PersonBirthDistrictID]:"");
    $addressCityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonAddressCityID])?$_areaList[$tmpObj->PersonAddressCityID]:"");
    $addressDistrictObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->PersonAddressDistrictID])?$_areaList[$tmpObj->PersonAddressDistrictID]:"");
    
    $activeJobObj=\Humanres\PersonJobClass::getInstance(isset($activeJobList[$tmpObj->PersonID])?$activeJobList[$tmpObj->PersonID]:[]);
    
    $PersonWorkYearAll=$tmpObj->PersonWorkYearAll;
    $PersonWorkMonthAll=$tmpObj->PersonWorkMonthAll;
    $PersonWorkDayAll=$tmpObj->PersonWorkDayAll;
    
    $PersonWorkYearGov=$tmpObj->PersonWorkYearGov;
    $PersonWorkMonthGov=$tmpObj->PersonWorkMonthGov;
    $PersonWorkDayGov=$tmpObj->PersonWorkDayGov;
    
    $PersonWorkYearMilitary=$tmpObj->PersonWorkYearMilitary;
    $PersonWorkMonthMilitary=$tmpObj->PersonWorkMonthMilitary;
    $PersonWorkDayMilitary=$tmpObj->PersonWorkDayMilitary;
    
    $PersonWorkYearCompany=$tmpObj->PersonWorkYearCompany;
    $PersonWorkMonthCompany=$tmpObj->PersonWorkMonthCompany;
    $PersonWorkDayCompany=$tmpObj->PersonWorkDayCompany;
    
    $PersonWorkYearOrgan=$tmpObj->PersonWorkYearOrgan;
    $PersonWorkMonthOrgan=$tmpObj->PersonWorkMonthOrgan;
    $PersonWorkDayOrgan=$tmpObj->PersonWorkDayOrgan;
    
    if($activeJobObj->isExist()){
        $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
        $tmpDay=$tmpObj->PersonWorkDayAll==""?0:($tmpObj->PersonWorkDayAll+$_time['day'])%30;
        
        $tmpMonth=$tmpObj->PersonWorkMonthAll==""?$_time['month']+floor(($tmpObj->PersonWorkDayAll+$_time['day'])/30):$tmpObj->PersonWorkMonthAll+$_time['month']+floor(($tmpObj->PersonWorkDayAll+$_time['day'])/30);
        
        $PersonWorkYearAll=$tmpObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
        $PersonWorkMonthAll=($tmpMonth%12);
        $PersonWorkDayAll=$tmpDay;
        if($activeJobObj->JobOrganID==1){
            $tmpDay=$tmpObj->PersonWorkDayGov==""?0:($tmpObj->PersonWorkDayGov+$_time['day'])%30;
            $tmpMonth=$tmpObj->PersonWorkMonthGov==""?$_time['month']+floor(($tmpObj->PersonWorkDayGov+$_time['day'])/30):$tmpObj->PersonWorkMonthGov+$_time['month']+floor(($tmpObj->PersonWorkDayGov+$_time['day'])/30);
            $PersonWorkYearGov=$tmpObj->PersonWorkYearGov==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearGov+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthGov=$tmpMonth%12;
            $PersonWorkDayGov=$tmpDay;
        }
        if($activeJobObj->JobOrganTypeID==1){
            $tmpDay=$tmpObj->PersonWorkDayMilitary==""?0:(($tmpObj->PersonWorkDayMilitary+$_time['day'])%30);
            $tmpMonth=$tmpObj->PersonWorkMonthMilitary==""?$_time['month']+floor(($tmpObj->PersonWorkDayMilitary+$_time['day'])/30):$tmpObj->PersonWorkMonthMilitary+$_time['month']+floor(($tmpObj->PersonWorkDayMilitary+$_time['day'])/30);
            $PersonWorkYearMilitary=$tmpObj->PersonWorkYearMilitary==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearMilitary+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthMilitary=$tmpMonth%12;
            $PersonWorkDayMilitary=$tmpDay;
        }
        if($activeJobObj->JobOrganID>1){
            $tmpDay=$tmpObj->PersonWorkDayCompany==""?0:(($tmpObj->PersonWorkDayCompany+$_time['day'])%30);
            $tmpMonth=$tmpObj->PersonWorkMonthCompany==""?$_time['month']+floor(($tmpObj->PersonWorkDayCompany+$_time['day'])/30):$tmpObj->PersonWorkMonthCompany+$_time['month']+floor(($tmpObj->PersonWorkDayCompany+$_time['day'])/30);
            $PersonWorkYearCompany=$tmpObj->PersonWorkYearCompany==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearCompany+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthCompany=$tmpMonth%12;
            $PersonWorkDayCompany=$tmpDay;
        }
        if($activeJobObj->JobOrganSubID==1){
            $tmpDay=$tmpObj->PersonWorkDayOrgan==""?0:(($tmpObj->PersonWorkDayOrgan+$_time['day'])%30);
            $tmpMonth=$tmpObj->PersonWorkMonthOrgan==""?$_time['month']+floor(($tmpObj->PersonWorkDayOrgan+$_time['day'])/30):$tmpObj->PersonWorkMonthOrgan+$_time['month']+floor(($tmpObj->PersonWorkDayOrgan+$_time['day'])/30);
            $PersonWorkYearOrgan=$tmpObj->PersonWorkYearOrgan==""?$_time['year']+floor($tmpMonth/12):$tmpObj->PersonWorkYearOrgan+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthOrgan=$tmpMonth%12;
            $PersonWorkDayOrgan=$tmpDay;
        }
    }
    
    for($jj=0; $jj<count($array_header); $jj++){
        if(isset($array_header[$jj]['value'])){
            if(isset($array_header[$jj]['is_str']) && $array_header[$jj]['is_str']){
                $activesheet->setCellValueByColumnAndRow($jj, $i,html_entity_decode($tmpObj->{$array_header[$jj]['value']}));
            }else{
                $activesheet->setCellValueByColumnAndRow($jj, $i,$tmpObj->{$array_header[$jj]['value']});
            }
        }else{
            if(isset($array_header[$jj]['type'])){
                if($array_header[$jj]['type']=='Num'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $rownum);
                }elseif($array_header[$jj]['type']=='Gender'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $tmpObj->PersonGender?"Эрэгтэй":"Эмэгтэй");
                }elseif($array_header[$jj]['type']=='EduLevel'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $eduObj->RefLevelTitle);
                }elseif($array_header[$jj]['type']=='Ehtnic'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $ethnicObj->RefEthnicTitle);
                }elseif($array_header[$jj]['type']=='BirthCity'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $birthCityObj->AreaTitle);
                }elseif($array_header[$jj]['type']=='BirthDistrict'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $birthSumObj->AreaTitle);
                }elseif($array_header[$jj]['type']=='AddressCity'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $addressCityObj->AreaTitle);
                }elseif($array_header[$jj]['type']=='AddressDistrict'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $addressDistrictObj->AreaTitle);
                }elseif($array_header[$jj]['type']=='IsSoldier'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $tmpObj->PersonIsSoldiering===""?"":$tmpObj->PersonIsSoldiering?"Тийм":"Үгүй");
                }elseif($array_header[$jj]['type']=='SoldierType'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $soldierObj->RefSoldierTitle);
                }elseif($array_header[$jj]['type']=='WorkYearAll'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, \System\Util::formatDaysMonth($PersonWorkYearAll,$PersonWorkMonthAll,$PersonWorkDayAll));
                }elseif($array_header[$jj]['type']=='WorkYearGov'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, \System\Util::formatDaysMonth($PersonWorkYearGov,$PersonWorkMonthGov,$PersonWorkDayGov));
                }elseif($array_header[$jj]['type']=='WorkYearMilitary'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, \System\Util::formatDaysMonth($PersonWorkYearMilitary,$PersonWorkMonthMilitary,$PersonWorkDayMilitary));
                }elseif($array_header[$jj]['type']=='WorkYearCompany'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, \System\Util::formatDaysMonth($PersonWorkYearCompany,$PersonWorkMonthCompany,$PersonWorkDayCompany));
                }
                
                
            }
        }
    }
    $i++;
}
    
$objPHPExcel->getActiveSheet()->setTitle($_title);
$objPHPExcel->setActiveSheetIndex(0);

$file = $_title." ".gmdate('Y-m-d').'.xlsx';

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
?>