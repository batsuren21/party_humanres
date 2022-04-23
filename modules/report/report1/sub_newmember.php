<?php
$now=new \DateTime();
$_year=$now->format("Y");

$param_monthlist=[];
for($j=1; $j<13; $j++){
    $param_monthlist[$j]=['id'=>$j,'title'=>$j.'-р сар','month'=>$_year."-".str_pad($j, 2, "0", STR_PAD_LEFT)];
}

$qry_sub=" SELECT SUBSTR(EmployeeStartDate,1,7) as YearMonth, COUNT(TF.PersonID) as AllCountPerson";
$qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TEmp on TF.PersonEmployeeID=TEmp.EmployeeID";
$qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TPos on  TEmp.EmployeePositionID=TPos.PositionID";
$qry_sub.=" and TPos.PositionClassID=1";
if($_departmentObj->isExist()){
    $qry_sub.=" and TPos.PositionDepartmentID=".$_departmentObj->DepartmentID;
}
$qry_sub.=" GROUP BY SUBSTR(EmployeeStartDate,1,7)";

$result = $__con->select($qry_sub);
$_newmemberCount=\Database::getList($result,["_mainindex"=>"YearMonth"]);

$_newmemberCountSub=[];
$_positionList=[];
if($_departmentObj->isExist()){
    $_positionList=\Humanres\PositionClass::getInstance()->getRowList([
        "position_departmentid"=>$_departmentObj->DepartmentID,
        "position_classid"=>\Humanres\DepartmentClass::CLASS_BASIC,
        'orderby'=>"PositionOrder"]);
    
    $qry_sub=" SELECT SUBSTR(EmployeeStartDate,1,7) as YearMonth, TEmp.EmployeePositionID, COUNT(TF.PersonID) as AllCountPerson";
    $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
    $qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TEmp on TF.PersonEmployeeID=TEmp.EmployeeID";
    $qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TPos on  TEmp.EmployeePositionID=TPos.PositionID";
    $qry_sub.=" and TPos.PositionClassID=1";
    $qry_sub.=" and TPos.PositionDepartmentID=".$_departmentObj->DepartmentID;
    $qry_sub.=" GROUP BY TEmp.EmployeePositionID, SUBSTR(EmployeeStartDate,1,7)";
    $result = $__con->select($qry_sub);
    $_newmemberCountSub=\Database::getList($result,["_mainindexs"=>"EmployeePositionID","_mainindex"=>"YearMonth"]);
}else{
    $qry_sub=" SELECT SUBSTR(EmployeeStartDate,1,7) as YearMonth, TEmp.EmployeeDepartmentID, COUNT(TF.PersonID) as AllCountPerson";
    $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
    $qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TEmp on TF.PersonEmployeeID=TEmp.EmployeeID";
    $qry_sub.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TPos on  TEmp.EmployeePositionID=TPos.PositionID";
    $qry_sub.=" and TPos.PositionClassID=1";
    $qry_sub.=" GROUP BY TEmp.EmployeeDepartmentID, SUBSTR(EmployeeStartDate,1,7)";
    $result = $__con->select($qry_sub);
    $_newmemberCountSub=\Database::getList($result,["_mainindexs"=>"EmployeeDepartmentID","_mainindex"=>"YearMonth"]);
    
}
?>
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Гишүүд :: Шинээр системд бүртгэсэн
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body ">
		<div class="card-body search_body" id="search">
			<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
				<table class="table table-sm table-head-bg-brand">
					<tbody>
						<tr>
							<td><strong>Сар</strong></td>
							<?php foreach ($param_monthlist as $r){?>
							<td width="3%" nowrap="nowrap"><strong><?=$r['title']?></strong></td>
							<?php }?>
						</tr>
						<tr>
							<td><strong>Гишүүний тоо</strong></td>
							<?php 
							     foreach ($param_monthlist as $r){
							         $_stat=\Database::getParam($_newmemberCount,$r['month'])
						     ?>
							<td class="text-center"><?=isset($_stat['AllCountPerson'])?$_stat['AllCountPerson']:0?></td>
							<?php }?>
						</tr>
						
						<?php 
                            if($_departmentObj->isExist()){
                                foreach ($_positionList as $r){
                                    $posObj=\Humanres\PositionClass::getInstance($r);
                                    $_countRes=\Database::getParam($_newmemberCountSub,$posObj->PositionID);
                                    
                        ?>
                        	<tr>
    							<td width="100%"><?=$posObj->PositionFullName?></td>
    							<?php 
    							     foreach ($param_monthlist as $r){
    							         $_stat=\Database::getParam($_countRes,$r['month'])
    						     ?>
    							<td class="text-center"><?=isset($_stat['AllCountPerson'])?$_stat['AllCountPerson']:0?></td>
    							<?php }?>
    						</tr>
                    	<?php 
                                }
                        ?>
                        <?php 
                            }else{
                                foreach ($_departmentList as $r){
                                    $depObj=\Humanres\DepartmentClass::getInstance($r);
                                    $_countRes=\Database::getParam($_newmemberCountSub,$depObj->DepartmentID);
                        ?>
                        	<tr>
    							<td width="100%"><?=$depObj->DepartmentFullName?></td>
    							<?php 
    							     foreach ($param_monthlist as $r){
    							         $_stat=\Database::getParam($_countRes,$r['month'])
    						     ?>
    							<td class="text-center"><?=isset($_stat['AllCountPerson'])?$_stat['AllCountPerson']:0?></td>
    							<?php }?>
    						</tr>
                    	<?php }?>
                        <?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>