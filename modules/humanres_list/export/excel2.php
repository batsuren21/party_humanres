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
    array('title'=>"Эцэг, эхийн нэр","value"=>"PersonLastName",'width'=>20),
    array('title'=>"Өөрийн нэр","value"=>"PersonFirstName",'width'=>20),
    array('title'=>"Төрсөн огноо","value"=>"PersonBirthDate",'width'=>10),
    array('title'=>"Хүйс","type"=>"Gender",'width'=>10),
    array('title'=>"Сургуулийн төрөл","type"=>"School",'width'=>15),
    array('title'=>"Боловсролын түвшин","type"=>"Level",'width'=>15),
    array('title'=>"Боловсролын зэрэг","type"=>"Degree",'width'=>15),
    array('title'=>"Сургуулийн нэр","value"=>"EducationSchoolTitle",'width'=>20),
    array('title'=>"Одоо сурч байгаа эсэх","type"=>"IsNow",'width'=>10),
    array('title'=>"Элссэн огноо","value"=>"EducationDateStart",'width'=>10),
    array('title'=>"Төгссөн огноо","value"=>"EducationDateEnd",'width'=>10),
    array('title'=>"Гэрчилгээ, дипломын дугаар","value"=>"EducationLicence",'width'=>20),
    array('title'=>"Мэргэжил","value"=>"EducationProfession",'width'=>20),
    
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
$_levelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
$_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_EDUCATION_DEGREE);
$_schoolList=$refObj->getRowList(["_mainindex"=>"RefSchoolID"],\Humanres\ReferenceClass::TBL_EDUCATION_SCHOOL);

$mainObj=new \Humanres\PersonEducationClass();
$search=$searchdata;

$_resultList=$mainObj->getRowList(array_merge($search,array(
    "education_get_table"=>1,
    "person_get_table"=>1)));
$i=3;
foreach ($_resultList as $j => $row){
    $objPHPExcel->getActiveSheet()->getStyle("{$first_letter}{$i}:{$last_letter}{$i}")->applyFromArray($styleArray);
    
    $eduObj= \Humanres\PersonEducationClass::getInstance($row);
    $tmpObj= \Humanres\PersonClass::getInstance($row);
    
    $_levelObj=\Humanres\ReferenceClass::getInstance(isset($_levelList[$eduObj->EducationLevelID])?$_levelList[$eduObj->EducationLevelID]:"");
    $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$tmpObj->EducationDegreeID])?$_degreeList[$tmpObj->EducationDegreeID]:"");
    $_schoolObj=\Humanres\ReferenceClass::getInstance(isset($_schoolList[$tmpObj->EducationSchoolID])?$_schoolList[$tmpObj->EducationSchoolID]:"");
    
    $rownum= ($j + 1);
    
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
                }elseif($array_header[$jj]['type']=='School'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $_schoolObj->RefSchoolTitle);
                }elseif($array_header[$jj]['type']=='Level'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $_levelObj->RefLevelTitle);
                }elseif($array_header[$jj]['type']=='Degree'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $_degreeObj->RefDegreeTitle);
                }elseif($array_header[$jj]['type']=='IsNow'){
                    $activesheet->setCellValueByColumnAndRow($jj, $i, $tmpObj->EducationIsNow===""?"":$tmpObj->EducationIsNow?"Тийм":"Үгүй");
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