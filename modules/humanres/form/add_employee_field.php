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
        if($employeeObj->EmployeeIsActive){
?>
<input type="hidden" value="<?=$employeeObj->EmployeeID;?>" name="employee[EmployeeID]">
<div class="alert alert-outline-danger alert-dismissible fade show" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	</button>
	<strong>Анхааруулга!</strong>&nbsp;Одоо идэвхитэй үүрэнд харьяалагдаж байгаа тул дахин үүрэнд бүртгэх боломжгүй. Өмнөх үүрээс хасалт хийлгэнэ үү.
</div>
<div class="kt-heading kt-heading--md">Одоогийн бүртгэлтэй үүр</div>
<table class="table table-striped font-12">
	<tbody>
		<tr>
			<td class="color-gray" width="1%" nowrap>Нэгж:</td>
			<td><?=$employeeObj->DepartmentFullName?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Үүр:</td>
			<td><?=$employeeObj->PositionFullName?></td>
		</tr>
		<tr>
			<td class="color-gray" width="1%" nowrap>Бүртгэсэн огноо:</td>
			<td><?=$employeeObj->EmployeeStartDate?></td>
		</tr>
	</tbody>
</table>
<?php
        }
    }else{
        $regnum_year =$_register!=""?mb_substr($_register, 2,2,"utf8"):"";
        $regnum_month=$_register!=""?mb_substr($_register, 4,2,"utf8"):"";
        $regnum_day=$_register!=""?mb_substr($_register, 6,2,"utf8"):"";
        $regnum_gender=$_register!=""?mb_substr($_register, 8,1,"utf8"):"";
        if($_register!=""){
            $regnum_year=$regnum_month>12?2000+$regnum_year:1900+$regnum_year;
            $regnum_month=$regnum_month>12?$regnum_month-20:$regnum_month;
        }
        $birthdate=$_register!=""?($regnum_year."-".$regnum_month."-".$regnum_day):"";
        if($_register!="")
            $regnum_gender = $regnum_gender % 2 == 0 ? 0 : 1;
        
        $_areaList=\Office\AreaClass::getInstance()->getRowList(['area_parentid'=>1,'orderby'=>'AreaGlobalID, AreaName']);
            
?>
<div class=" row">
	<div class="col-lg-4 form-group">
		<label class="font-12">Ургийн овог *:</label>
		<input type="text" class="form-control form-control-sm resfield" placeholder="Ургийн овог" name="person[PersonMiddleName]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
	</div>
	<div class="col-lg-4 form-group">
		<label class="font-12">Эцэг, эхийн нэр *:</label>
		<input type="text" class="form-control form-control-sm resfield" placeholder="Эцэг, эхийн нэр" name="person[PersonLastName]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
	</div>
	<div class="col-lg-4 form-group">
		<label class="font-12">Өөрийн нэр *:</label>
		<input type="text" class="form-control form-control-sm resfield" placeholder="Өөрийн нэр" name="person[PersonFirstName]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
	</div>
</div>
<div class=" row">
	<div class="col-lg-8">
    	<div class=" row">
        	<div class="col-lg-4 form-group">
        		<label class="font-12">Төрсөн огноо *:</label>
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="person[PersonBirthDate]" placeholder="Өдөр сонгох" value="<?=$birthdate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 form-group">
        		<label class="font-12">Хүйс *:</label>
        		<select class="form-control form-control-sm resfield" name="person[PersonGender]" data-rule-required="true" data-msg-required="Хоосон байна.">
        			<?php \System\Combo::getCombo(["data"=>\Humanres\PersonClass::$gender,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$regnum_gender])?>
        		</select>
        	</div>
    	</div>
    	<div class=" row">
    		<div class="col-lg-4 form-group">
        		<label class="font-12">Төрсөн газар /Аймаг, нийслэл/*:</label>
				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="person[PersonBirthCityID]" 
        			data-url="<?=RF;?>/m/ajax/select" 
        			data-action="area"
        			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
        			data-target="#formareadistrictid" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
        			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->PersonBirthCityID])?>
        		</select>
        	</div>
        	<div class="col-lg-4 form-group">
        		<label class="font-12"> Сум, дүүрэг:</label>
				<select class="form-control form-control-sm" id="formareadistrictid" name="person[PersonBirthDistrictID]" data-selected="<?=$personObj->PersonBirthDistrictID;?>" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    			</select>
        	</div>
    	</div>
    	<div class=" row">
        	<div class="col-lg-4 form-group">
        		<label class="font-12">Намд элссэн огноо *:</label>
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="person[PersonPartyEnterDate]" placeholder="Өдөр сонгох" value="" data-rule-required="true" data-msg-required="Хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 form-group">
        		<label class="font-12">Намын батлахын дугаар *:</label>
        		<input type="text" class="form-control form-control-sm resfield" placeholder="Дугаар" name="person[PersonPartyConfirmNumber]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
        	</div>
    	</div>
	</div>
	<div class="col-lg-4">
		<div class="form-group row">
			<label class="col-xl-3 col-lg-3 col-form-label">Цээж зураг</label>
			<div class="col-lg-9 col-xl-6">
				<div class="kt-avatar kt-avatar--outline" id="kt_user_avatar">
					<div class="kt-avatar__holder" style="background-image: url(<?=$personObj->getImage()?>)"></div>
					<label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
						<i class="fa fa-pen"></i>
						<input type="file" name="filesource" accept="image/*">
					</label>
					<span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
						<i class="fa fa-times"></i>
					</span>
				</div>
				
			</div>
		</div>
	</div>
</div>

<?php 
    }
?>