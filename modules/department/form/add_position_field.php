<?php 
    $_depid=isset($_POST['paramid'])?$_POST['paramid']:0;
    $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_depid]);
    
    $_depClassObj=\Humanres\ReferenceClass::getInstance()->getRowRef(["ref_id"=>$departmentObj->DepartmentClassID],\Humanres\ReferenceClass::TBL_DEPARTMENT_CLASS);
    
    $_arr=[];
    if($_depClassObj->RefClassType==\Humanres\DepartmentClass::CLASS_ELECT){
        $_arr=['ref_type'=>$_depClassObj->RefClassType];
    }
    
    $refObj=\Humanres\ReferenceClass::getInstance();
     
    $_classList=$refObj->getRowList(array_merge($_arr,["orderby"=>"RefClassOrder"]),\Humanres\ReferenceClass::TBL_POSITION_CLASS);
    $_typeList=$refObj->getRowList(array_merge($_arr,["orderby"=>"RefTypeOrder"]),\Humanres\ReferenceClass::TBL_POSITION_TYPE);
    
    $lastObj=\Humanres\PositionClass::getInstance()->getRow(["position_departmentid"=>$_depid,"orderby"=>"PositionOrder desc","rowstart"=>0,"rowlength"=>1]);
    if($lastObj->isExist()){
        $_order=$lastObj->PositionOrder+1;
    }else{
        $_order=1;
    }
?>
<div class="kt-form__section kt-form__section--first">
<div class="kt-wizard-v1__review-item mt-1">															
	<div class="kt-wizard-v1__review-content">
		<strong>Удирдлага, зохион байгуулалт:</strong> <?=$departmentObj->DepartmentFullName;?>
	</div>
</div>
<div class="kt-wizard-v1__form  mt-2">
	<div class=" row">
		<div class="col-lg-4 form-group">
			<label class="font-12">Ангилал *:</label>
			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionClassID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
				<?php \System\Combo::getCombo(["data"=>$_classList,"title"=>"RefClassTitle","value"=>"RefClassID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
			</select>
		</div>
		<div class="col-lg-4 form-group">
			<label class="font-12">Төрөл *:</label>
			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionTypeID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
				<?php \System\Combo::getCombo(["data"=>$_typeList,"title"=>"RefTypeTitle","value"=>"RefTypeID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
			</select>
		</div>
	</div>
	<div class=" row">
		<div class="col-lg-8 form-group">
			<label class="font-12">Товч нэр *:</label>
			<input type="text" class="form-control form-control-sm resfield" placeholder="Товч нэр" name="position[PositionName]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
		</div>
		<div class="col-lg-4 form-group">
			<label class="font-12">Эрэмбэ *:</label>
			<input type="number" class="form-control form-control-sm resfield" placeholder="Эрэмбэ"  name="position[PositionOrder]" value="<?=$_order;?>" data-rule-required="true" data-msg-required="Хоосон байна.">
		</div>
	</div>
	<div class=" row">
		<div class="col-lg-12 form-group">
			<label class="font-12">Бүтэн нэр *:</label>
			<input type="text" class="form-control form-control-sm resfield" placeholder="Бүтэн нэр" name="position[PositionFullName]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
		</div>
	</div>
</div>
</div>
