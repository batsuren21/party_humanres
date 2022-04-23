<?php
$_row_body_start=8;
$_row_body=8;
$_row_num=0;

$col__letter_start=PHPExcel_Cell::stringFromColumnIndex(0);
$col__letter_start_1=PHPExcel_Cell::stringFromColumnIndex(1);
$col__letter_start_2=PHPExcel_Cell::stringFromColumnIndex(2);
$col__letter_end=PHPExcel_Cell::stringFromColumnIndex($_count_header-1);

$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll1,"ALL"));

require 'workdays_excel_body_sub.php';
foreach (\Humanres\ReferenceClass::$_position_class as $row){
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll1,$row['id']));
    require 'workdays_excel_body_sub.php';
}
foreach ($_positionClassList as $row){
    $_posClassObj=\Humanres\ReferenceClass::getInstance($row);
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll2,"ALL_".$_posClassObj->RefClassID));
    require 'workdays_excel_body_sub.php';
    foreach (\Humanres\ReferenceClass::$_position_class as $row){
        $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll2,"ROW_".$row['id']."_".$_posClassObj->RefClassID));
        require 'workdays_excel_body_sub.php';
    }
    
}
$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll3,"ALL"));
require 'workdays_excel_body_sub.php';
foreach ($_positionDegreeList as $row){
    $_posDegreeObj=\Humanres\ReferenceClass::getInstance($row);
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll3,$_posDegreeObj->RefDegreeID));
    require 'workdays_excel_body_sub.php';
}
$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll4,"ALL"));
require 'workdays_excel_body_sub.php';
foreach ($_eduRankList as $row){
    $_eduRankObj=\Humanres\ReferenceClass::getInstance($row);
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll4,$_eduRankObj->RefRankID));
    require 'workdays_excel_body_sub.php';
}
$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll5,"ALL"));
require 'workdays_excel_body_sub.php';
foreach ($_eduLevelList as $row){
    $_eduLevelObj=\Humanres\ReferenceClass::getInstance($row);
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll5,$_eduLevelObj->RefLevelOrder));
    require 'workdays_excel_body_sub.php';
}
$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll6,"ALL"));
require 'workdays_excel_body_sub.php';
foreach ($_ageList as $row){
    $_ageObj=\Humanres\ReferenceClass::getInstance($row);
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll6,$_ageObj->AgeID));
    require 'workdays_excel_body_sub.php';
}
$allStatObj=\Humanres\HumanresStatisticClass::getInstance(\Database::getParam($stat_listAll7,"ALL"));
require 'workdays_excel_body_sub.php';
$_row_body_start--;
$activesheet->getStyle("{$col__letter_start}{$_row_body_start}:{$col__letter_start_1}{$_row_body}")->getAlignment()->setWrapText(true)->setShrinkToFit(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$activesheet->getStyle("{$col__letter_start}{$_row_body_start}:{$col__letter_end}{$_row_body}")->applyFromArray($styleArray);
$activesheet->getStyle("{$col__letter_start_2}{$_row_body_start}:{$col__letter_end}{$_row_body}")->getAlignment()->setWrapText(true)->setShrinkToFit(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
