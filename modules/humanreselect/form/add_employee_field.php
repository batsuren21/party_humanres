<?php
    $_register=isset($_POST['register'])?$_POST['register']:"";
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_registernumber'=>$_register]);
    if($personObj->isExist()){
?>
<div class="kt-heading kt-heading--md">Хувийн мэдээлэл</div>
<input type="hidden" value="<?=$personObj->PersonID;?>" name="person[PersonID]">
<table class="table table-striped font-12">
	<tbody>
		<tr>
			<td class="color-gray" width="1%" nowrap>Регистрийн дугаар:</td>
			<td><?=$personObj->PersonRegisterNumber?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Ургийн овог:</td>
			<td><?=$personObj->PersonMiddleName?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Эцэг, эхийн нэр:</td>
			<td><?=$personObj->PersonLastName?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Өөрийн нэр:</td>
			<td><?=$personObj->PersonFirstName?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Төрсөн огноо:</td>
			<td><?=$personObj->PersonBirthDate?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Хүйс:</td>
			<td><?=!$personObj->PersonGender?"Эмэгтэй":"Эрэгтэй"?></td>
		</tr>
	</tbody>
</table>
<?php
        $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(["employee_get_table"=>6,"employee_id"=>$personObj->PersonEmployeeID]);
  
    }else{
?>
<input type="hidden" value="-1" name="employee[EmployeeID]">
<div class="alert alert-outline-danger alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	</button>
	<strong>Анхааруулга!</strong>&nbsp;Анхан шатны үүрийн гишүүнээр бүртгэгдээгүй тул томилох боломжгүй байна.
</div>
<?php }?>