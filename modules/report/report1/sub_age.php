<?php
$param_agelist=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"AgeOrder"],\Humanres\ReferenceClass::TBL_AGE);
$now=new \DateTime();
$_date=$now->format("Y-m-d");
$_sub_age="( CASE";
foreach ($param_agelist as $tmp){
    $_yearstart=$tmp['AgeStart'];
    $_yearend=$tmp['AgeEnd']+1;
    
    $start_date = date('Y-m-d', strtotime($_date. '-'.$_yearstart.' year'));
    $end_date = date('Y-m-d', strtotime($_date. '-'.$_yearend.' year'));
    if($_yearend>100){
        $_sub_age.=" WHEN TF.PersonBirthDate <= '".$start_date."' THEN ".$tmp['AgeID'];
    }else{
        $_sub_age.=" WHEN TF.PersonBirthDate <= '".$start_date."' AND TF.PersonBirthDate > '".$end_date."' THEN ".$tmp['AgeID'];
    }
}
$_sub_age.=" END ) as PersonAge";

$qry_sub=" SELECT TF.PersonID, $_sub_age";
$qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TEmp on TF.PersonEmployeeID=TEmp.EmployeeID";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TPos on  TEmp.EmployeePositionID=TPos.PositionID";
$qry_sub.=" and TPos.PositionClassID=1";

if($_departmentObj->isExist()){
    $qry_sub.=" and TPos.PositionDepartmentID=".$_departmentObj->DepartmentID;
}

$qry="
                select T.PersonAge, COUNT(T.PersonID) as AllCountAge
                from (".$qry_sub.") T
                group by T.PersonAge
            ";
$result = $__con->select($qry);
$_ageCount=\Database::getList($result,["_mainindex"=>"PersonAge"]);
?>
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<h3 class="kt-portlet__head-title">
        					Гишүүд :: Насны байдлаар
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
        				<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
    						<div id="chartdiv_age" style="height: 300px;"></div>
    						<table class="table table-sm table-head-bg-brand">
            					<tbody>
            						<?php 
        						          foreach ($param_agelist as $r){
        						              $_title=$r['AgeTitle'];
        						              $_val=isset($_ageCount[$r['AgeID']])?$_ageCount[$r['AgeID']]['AllCountAge']:0;
						            ?>
            						<tr>
        								<td width="50%" align="right"><?=$_title?>:</td><td  width="50%"><?=$_val?></td>
        							</tr>
        							<?php }?>
    							</tbody>
    						</table>
    					</div>
            		</div>
        		</div>
        	</div>