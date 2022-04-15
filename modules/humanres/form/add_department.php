<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Зохион байгуулалтын нэгж засварлах":"Зохион байгуулалтын нэгж бүртгэх";
        
        $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_id]);
        $refObj=\Humanres\ReferenceClass::getInstance();
        
        $_typeList=$refObj->getRowList(["orderby"=>"RefTypeOrder"],\Humanres\ReferenceClass::TBL_DEPARTMENT_TYPE);
        $_classList=$refObj->getRowList(["orderby"=>"RefClassOrder"],\Humanres\ReferenceClass::TBL_DEPARTMENT_CLASS);
        
        if($_id<1){
            $lastObj=\Humanres\DepartmentClass::getInstance()->getRow(["orderby"=>"DepartmentOrder desc","rowstart"=>0,"rowlength"=>1]);
            if($lastObj->isExist()){
                $_order=$lastObj->DepartmentOrder+1;
            }else{
                $_order=1;
            }
        }else{
            $_order=$departmentObj->DepartmentOrder;
        }
        $_parentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_classid"=>[2,3,4],"department_notid"=>$_id,'orderby'=>"DepartmentOrder"]);
        $_areaList=\Office\AreaClass::getInstance()->getRowList(['area_parentid'=>1,'orderby'=>'AreaGlobalID, AreaName']);
        
        $is_has_parent=$departmentObj->isExist() && $departmentObj->DepartmentParentID>0?1:0;
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/<?=$_id>0?"editdepartment":"adddepartment"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
       <div class=" row">
   			<div class="col-lg-4 form-group">
				<label class="font-12">Шалгах гомдлын төрөл *:</label>
				<select class="form-control  form-control-sm" data-col-index="2" name="department[DepartmentTypeID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
					<?php \System\Combo::getCombo(["data"=>$_typeList,"title"=>"RefTypeTitle","value"=>"RefTypeID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$departmentObj->DepartmentTypeID])?>
				</select>
			</div>
			<div class="col-lg-4 form-group">
				<label class="font-12">Нэгжийн ангилал *:</label>
				<select class="form-control  form-control-sm" data-col-index="2" name="department[DepartmentClassID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
					<?php \System\Combo::getCombo(["data"=>$_classList,"title"=>"RefClassTitle","value"=>"RefClassID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$departmentObj->DepartmentClassID])?>
				</select>
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-8 form-group">
				<label class="font-12">Товч нэр *:</label>
				<input type="text" class="form-control form-control-sm" placeholder="Товч нэр" name="department[DepartmentName]" value="<?=$departmentObj->DepartmentName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
			<div class="col-lg-4 form-group">
				<label class="font-12">Орон тоо *:</label>
				<input type="number" class="form-control form-control-sm" placeholder="Орон тоо"  name="department[DepartmentCountJob]" value="<?=$departmentObj->DepartmentCountJob?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-8 form-group">
				<label class="font-12">Бүтэн нэр *:</label>
				<input type="text" class="form-control form-control-sm" placeholder="Товч нэр" name="department[DepartmentFullName]" value="<?=$departmentObj->DepartmentFullName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
			<div class="col-lg-4 form-group">
				<label class="font-12">Эрэмбэ *:</label>
				<input type="number" class="form-control form-control-sm" placeholder="Эрэмбэ"  name="department[DepartmentOrder]" value="<?=$_order;?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-4 form-group">
				<label class="font-12">Дээд шатны нэгжтэй эсэх *:</label>
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="department[DepartmentHasParent]" <?=!$is_has_parent?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="department[DepartmentHasParent]" <?=$is_has_parent?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
				</div>
			</div>
			<div class="col-lg-8 form-group <?=!$is_has_parent?"d-none":""?>" id="formIsControl">
				<label class="font-12">Дээд шатны нэгж *:</label>
				<select class="form-control  form-control-sm" <?=!$is_has_parent?"disabled":""?> name="department[DepartmentParentID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
					<?php \System\Combo::getCombo(["data"=>$_parentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$departmentObj->DepartmentParentID])?>
				</select>
			</div>
		</div>
		<div class="form-group row ">
        	<div class="col-lg-12">
        		<label class="font-12">Нутаг дэвсгэр:</label>
        		<select class="form-control kt-select2 kt-select2-custom" id="kt_select2_3_modal1" name="areaids[]" multiple="multiple" data-placeholder="--- Сонгох ---">
        			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$departmentObj->DepartmentAreaIDs])?>
        		</select>
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $departmentObj->DepartmentCountPosition<1){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete">Устгах</button>
 	<?php }?>
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
    <button type="submit" class="btn btn-success ">Хадгалах</button>
</div>
</form>
<?php }else{?>
<div class="modal-header">
    <h5 class="modal-title">Бүртгэл</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
       <?=\Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд бүртгэх эрх байхгүй байна"]);?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>
<?php }?>
