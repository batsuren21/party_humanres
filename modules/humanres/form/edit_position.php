<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Албан тушаал засварлах":"Албан тушаал бүртгэх";
        
        $positionObj=\Humanres\PositionClass::getInstance()->getRow(['position_id'=>$_id]);
        $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$positionObj->PositionDepartmentID]);
        
        $refObj=\Humanres\ReferenceClass::getInstance();
         
        $_typeList=$refObj->getRowList(["orderby"=>"RefTypeOrder"],\Humanres\ReferenceClass::TBL_POSITION_TYPE);
        $_classList=$refObj->getRowList(["orderby"=>"RefClassOrder"],\Humanres\ReferenceClass::TBL_POSITION_CLASS);
        $_degreeList=$refObj->getRowList(["orderby"=>"RefDegreeOrder"],\Humanres\ReferenceClass::TBL_POSITION_DEGREE);
        $_rankList=$refObj->getRowList(["orderby"=>"RefRankOrder"],\Humanres\ReferenceClass::TBL_POSITION_RANK);
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/editposition" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="kt-grid  kt-wizard-v1 kt-wizard-v1--white" id="kt_wizard_v1" data-ktwizard-state="step-first">
	<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">
        <div class="kt-form__section kt-form__section--first">
            <div class="kt-wizard-v1__review-item mt-1">															
            	<div class="kt-wizard-v1__review-content">
            		<strong>Нэгж:</strong> <?=$departmentObj->DepartmentFullName;?>
            	</div>
            </div>
            <div class="kt-wizard-v1__form  mt-2">
            	<div class=" row">
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Албан тушаалын ангилал *:</label>
            			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionClassID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            				<?php \System\Combo::getCombo(["data"=>$_classList,"title"=>"RefClassTitle","value"=>"RefClassID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$positionObj->PositionClassID])?>
            			</select>
            		</div>
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Албан тушаалын зэрэглэл *:</label>
            			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionRankID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            				<?php \System\Combo::getCombo(["data"=>$_rankList,"title"=>"RefRankTitle","value"=>"RefRankID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$positionObj->PositionRankID])?>
            			</select>
            		</div>
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Албан тушаалын төрөл *:</label>
            			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionTypeID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            				<?php \System\Combo::getCombo(["data"=>$_typeList,"title"=>"RefTypeTitle","value"=>"RefTypeID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$positionObj->PositionTypeID])?>
            			</select>
            		</div>
            		
            	</div>
            	<div class=" row">
            		<div class="col-lg-8 form-group">
            			<label class="font-12">Товч нэр *:</label>
            			<input type="text" class="form-control form-control-sm resfield" placeholder="Товч нэр" name="position[PositionName]" value="<?=$positionObj->PositionName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
            		</div>
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Албан тушаалын зэрэг дэв *:</label>
            			<select class="form-control  form-control-sm resfield" data-col-index="2" name="position[PositionDegreeID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            				<?php \System\Combo::getCombo(["data"=>$_degreeList,"title"=>"RefDegreeTitle","value"=>"RefDegreeID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$positionObj->PositionDegreeID])?>
            			</select>
            		</div>
            	</div>
            	<div class=" row">
            		<div class="col-lg-12 form-group">
            			<label class="font-12">Бүтэн нэр *:</label>
            			<input type="text" class="form-control form-control-sm resfield" placeholder="Бүтэн нэр" name="position[PositionFullName]" value="<?=$positionObj->PositionFullName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
            		</div>
            	</div>
            	<div class=" row">
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Батлагдсан орон тоо *:</label>
            			<input type="number" class="form-control form-control-sm resfield" placeholder="Орон тоо"  name="position[PositionCountPerson]" value="<?=$positionObj->PositionCountPerson?>" data-rule-required="true" data-msg-required="Хоосон байна.">
            		</div>
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Эрэмбэ *:</label>
            			<input type="number" class="form-control form-control-sm resfield" placeholder="Эрэмбэ"  name="position[PositionOrder]" value="<?=$positionObj->PositionOrder;?>" data-rule-required="true" data-msg-required="Хоосон байна.">
            		</div>
            	</div>
            </div>
        </div>
	</div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $positionObj->PositionCountEmployee<1){?>
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