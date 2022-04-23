<?php
$_srch=[
    "person_get_table"=>1,
    "position_classid"=>1,
];

$_positionList=[];
$__title="";

if($_departmentObj->isExist()){
    $__title="Анхан шатны үүр";
    $_positionList=\Humanres\PositionClass::getInstance()->getRowList([
        "position_departmentid"=>$_departmentObj->DepartmentID,
        "position_classid"=>\Humanres\DepartmentClass::CLASS_BASIC,
        'orderby'=>"PositionOrder"]);
    
    $_srch=array_merge($_srch,['department_id'=>$_departmentObj->DepartmentID]);
    $_srch=array_merge($_srch,[
        "_select"=>['T2.EmployeePositionID','COUNT(PersonID) as AllCountPosition'],
        '_mainindex'=>"EmployeePositionID",
        'department_id'=>$_departmentObj->DepartmentID,
        'groupby'=>["T2.EmployeePositionID"]]);
}else{
    $__title="Анхан шатны байгууллага";
    $_srch=array_merge($_srch,[
        "_select"=>['T2.EmployeeDepartmentID','COUNT(PersonID) as AllCountDepartment'],
        '_mainindex'=>"EmployeeDepartmentID",
        'groupby'=>["T2.EmployeeDepartmentID"]]);
}
$_resultCount=\Humanres\PersonClass::getInstance()->getRowList($_srch);
?>
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Нийт гишүүд :: Анхан шатны байгууллагаар
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body ">
		<div class="card-body search_body" id="search">
			<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
				<table class="table table-sm table-head-bg-brand">
					<thead class="thead-inverse">
						<tr>
							<th class="text-center"><strong><?=$__title?></strong></th>
							<th class="text-center" nowrap="nowrap"><strong>Гишүүдийн тоо</strong></th>
						</tr>
					</thead>
					<tbody>
                    <?php 
                        if($_departmentObj->isExist()){
                            foreach ($_positionList as $r){
                                $posObj=\Humanres\PositionClass::getInstance($r);
                                $_countRes=\Database::getParam($_resultCount,$posObj->PositionID);
                    ?>
                    	<tr>
							<td width="100%"><?=$posObj->PositionFullName?></td>
							<td  width="1%" class="text-center"><?=isset($_countRes['AllCountPosition'])?$_countRes['AllCountPosition']:0;?></td>
						</tr>
                	<?php 
                            }
                    ?>
                    <?php 
                        }else{
                            foreach ($_departmentList as $r){
                                $depObj=\Humanres\DepartmentClass::getInstance($r);
                                $_countRes=\Database::getParam($_resultCount,$depObj->DepartmentID);
                    ?>
                    	<tr>
							<td width="100%"><?=$depObj->DepartmentFullName?></td>
							<td  width="1%" class="text-center"><?=isset($_countRes['AllCountDepartment'])?$_countRes['AllCountDepartment']:0;?></td>
						</tr>
                	<?php }?>
                    <?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>