<?php 
$j=0;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$personObj->PersonLFName);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$allStatObj->PositionFullName);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$_jj);
$j++;
/****/
$activesheet->setCellValueByColumnAndRow($j,$_row_body,$allStatObj->AllCount);
$j++;
/****/
if(isset($_directionList)){
    $_parentid=0;
    foreach ($_directionList as $tmp){
        if(isset($tmp['subids'])){
            foreach ($tmp['subids'] as $id1){
                $_tmp1=$_listSub[$id1];
                if(isset($_tmp1['subids'])){
                    foreach ($_tmp1['subids'] as $id2){
                        $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"Dir1Sub".$id2}!=""?$allStatObj->{"Dir1Sub".$id2}:0));
                        $j++;
                    }
                }else{
                    $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"DirSub".$id1}!=""?$allStatObj->{"DirSub".$id1}:0));
                    $j++;
                }
            }
        }elseif(isset($tmp['sub1ids'])){
            foreach ($tmp['sub1ids'] as $id2){
                $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"Dir1Sub".$id2}!=""?$allStatObj->{"Dir1Sub".$id2}:0));
                $j++;
            }
        }else{
            $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"Direction".$tmp['RefDirectionID']}!=""?$allStatObj->{"Direction".$tmp['RefDirectionID']}:0));
            $j++;
        }
    }
}
$_row_body++;