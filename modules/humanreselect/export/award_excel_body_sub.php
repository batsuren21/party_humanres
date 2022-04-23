<?php 
$j=3;
/****/
if(isset($_awardSubList)){
    $_parentid=0;
    foreach ($_awardSubList as $tmp){
        if($_parentid!=$tmp['RefAwardParentID']){
            $_parentid=$tmp['RefAwardParentID'];
            $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"SumAward".$tmp['RefAwardParentID']}!=""?$allStatObj->{"SumAward".$tmp['RefAwardParentID']}:0));
            $j++;
        }
        $activesheet->setCellValueByColumnAndRow($j,$_row_body,($allStatObj->{"SumAwardSub".$tmp['RefAwardID']}!=""?$allStatObj->{"SumAwardSub".$tmp['RefAwardID']}:0));
        $j++;
    }
}
$_row_body++;