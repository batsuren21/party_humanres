<?php
    $_srch=[
        "person_get_table"=>1,
        "position_classid"=>1,
    ];
    if($_departmentObj->isExist()){
        $_srch=array_merge($_srch,['department_id'=>$_departmentObj->DepartmentID]);
    }
    $_srch=array_merge($_srch,["_select"=>['PersonGender','COUNT(PersonID) as AllCountGender'],'_mainindex'=>"PersonGender",'groupby'=>["PersonGender"]]);
    
    $_genderCount=\Humanres\PersonClass::getInstance()->getRowList($_srch);
?>
<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head kt-portlet__head--lg">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Гишүүд :: Хүйсийн байдлаар
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body ">
		<div class="card-body search_body" id="search">
			<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
				<div id="chartdiv_gender" style="height: 300px;"></div>
				<table class="table table-sm table-head-bg-brand">
					
					<tbody>
						<?php $_row=$_genderCount[1];?>
						<tr>
							<td width="50%" align="right">Эрэгтэй:</td><td  width="50%"><?=isset($_row['AllCountGender'])?$_row['AllCountGender']:0;?></td>
						</tr>
						<?php $_row=$_genderCount[0];?>
						<tr>
							<td width="50%" align="right">Эмэгтэй:</td><td  width="50%"><?=isset($_row['AllCountGender'])?$_row['AllCountGender']:0;?></td>
						</tr>
					</tbody>
				</table>
				
			</div>
		</div>
	</div>
</div>