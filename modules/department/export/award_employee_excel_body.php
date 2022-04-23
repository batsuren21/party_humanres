<?php
$_row_body_start=7;
$_row_body=7;
$_row_num=0;

$col__letter_start=PHPExcel_Cell::stringFromColumnIndex(0);
$col__letter_start_1=PHPExcel_Cell::stringFromColumnIndex(1);
$col__letter_start_2=PHPExcel_Cell::stringFromColumnIndex(2);
$col__letter_end=PHPExcel_Cell::stringFromColumnIndex($_count_header-1);

$_jj=1;
$_depid=0;
foreach ($stat_listAll as $row){
    $allStatObj=\Humanres\HumanresStatisticClass::getInstance($row);
    $personObj=\Humanres\PersonClass::getInstance($row);
    if($_depid!=$allStatObj->DepartmentID){
        $_depid=$allStatObj->DepartmentID;
        $activesheet->mergeCells($col__letter_start.$_row_body.":".$col__letter_end.$_row_body);
        $activesheet->setCellValueByColumnAndRow(0,$_row_body,$allStatObj->DepartmentFullName);
        $_row_body++;
    }
    
    require 'award_employee_excel_body_sub.php';
    $_jj++;
}
$_row_body_start--;
$activesheet->getStyle("{$col__letter_start}{$_row_body_start}:{$col__letter_start_1}{$_row_body}")->getAlignment()->setWrapText(true)->setShrinkToFit(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$activesheet->getStyle("{$col__letter_start}{$_row_body_start}:{$col__letter_end}{$_row_body}")->applyFromArray($styleArray);
$activesheet->getStyle("{$col__letter_start_2}{$_row_body_start}:{$col__letter_end}{$_row_body}")->getAlignment()->setWrapText(true)->setShrinkToFit(true)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);