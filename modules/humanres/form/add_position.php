<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Албан тушаал засварлах":"Албан тушаал бүртгэх";
        
        $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_isactive"=>1,'orderby'=>"DepartmentOrder"]);        
?>
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="kt-grid  kt-wizard-v1 kt-wizard-v1--white" id="kt_wizard_v1" data-ktwizard-state="step-first">
	<div class="kt-grid__item">
		<!--begin: Form Wizard Nav -->
		<div class="kt-wizard-v1__nav">
			<div class="kt-wizard-v1__nav-items">
				<!--doc: Replace A tag with SPAN tag to disable the step link click -->
				<div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
					<div class="kt-wizard-v1__nav-body">
						<div class="kt-wizard-v1__nav-icon">
							<i class="flaticon2-layers-2"></i>
						</div>
						<div class="kt-wizard-v1__nav-label">
							1. Нэгж сонгох
						</div>
					</div>
				</div>
				<div class="kt-wizard-v1__nav-item" data-ktwizard-type="step">
					<div class="kt-wizard-v1__nav-body">
						<div class="kt-wizard-v1__nav-icon">
							<i class="flaticon-list"></i>
						</div>
						<div class="kt-wizard-v1__nav-label">
							2. Бүртгэл хийх
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<!--end: Form Wizard Nav -->
	</div>
	<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper">
		<!--begin: Form Wizard Form-->
		<form class="kt-form kt-form--label-right mt-0 pt-2" id="letterForm" action="<?=RF;?>/process/humanres/addposition" enctype="multipart/form-data">
			<!--begin: Form Wizard Step 1-->
			<div class="kt-wizard-v1__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
				<div class="kt-form__section kt-form__section--first">
					<div class="kt-wizard-v1__form mt-0">
						<div class="row">
							<div class="col-lg-12 form-group">
                				<label class="font-12">Нэгж *:</label>
                				<select class="form-control form-control-sm" data-changeid="position_more" name="position[PositionDepartmentID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                					<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
                				</select>
                			</div>
						</div>
					</div>
				</div>
			</div>
			<div class="kt-wizard-v1__content" data-ktwizard-type="step-content" id="position_more">
			</div>
			<div class="kt-form__actions">
				<button class="btn btn-secondary  btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
					Өмнөх
				</button>
				<button class="btn btn-success  btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
					Хадгалах
				</button>
				<button class="btn btn-brand  btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-next">
					Дараах
				</button>
			</div>
		</form>
	</div>
</div>
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
