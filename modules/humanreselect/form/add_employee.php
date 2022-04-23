<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Гишүүн засварлах":"Гишүүн бүртгэх";
        $refObj=\Humanres\ReferenceClass::getInstance();
        
        $_classIDs=$refObj->getRowList(["_column"=>"RefClassID"],\Humanres\ReferenceClass::TBL_DEPARTMENT_CLASS);
        
        $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_classid"=>$_classIDs,"department_isactive"=>1,'orderby'=>"DepartmentOrder"]);     
        $_startList=$refObj->getRowList(["ref_type"=>\Humanres\DepartmentClass::CLASS_ELECT,"orderby"=>"RefStartOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);

        $val_selected="";
        if(count($_departmentList)==1){
            $_tmp=reset($_departmentList);
            if(isset($_tmp['DepartmentID'])){
                $val_selected=$_tmp['DepartmentID'];
            }
        }
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
				<div class="kt-wizard-v1__nav-item" data-ktwizard-type="step" data-ktwizard-state="current">
					<div class="kt-wizard-v1__nav-body">
						<div class="kt-wizard-v1__nav-icon">
							<i class="flaticon-list"></i>
						</div>
						<div class="kt-wizard-v1__nav-label">
							1. Хувь хүний мэдээлэл шалгах, бүртгэх
						</div>
					</div>
				</div>
				<div class="kt-wizard-v1__nav-item" data-ktwizard-type="step">
					<div class="kt-wizard-v1__nav-body">
						<div class="kt-wizard-v1__nav-icon">
							<i class="flaticon2-layers-2"></i>
						</div>
						<div class="kt-wizard-v1__nav-label">
							2. Нэгж, харьяалал сонгох
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="kt-grid__item kt-grid__item--fluid kt-wizard-v1__wrapper fullwidth">
		<!--begin: Form Wizard Form-->
		<form class="kt-form kt-form--label-right mt-0 pt-2" id="letterForm" action="<?=RF;?>/process/humanreselect/addemployee" enctype="multipart/form-data">
			<!--begin: Form Wizard Step 1-->
			<div class="kt-wizard-v1__content" data-ktwizard-type="step-content" data-ktwizard-state="current">
				<div class="kt-form__section kt-form__section--first">
					<div class="kt-wizard-v1__form mt-0">
						<div class="form-group row">
                        	<label class="col-lg-3 col-form-label font-12">Регистрийн дугаар *:</label>
                        	<div class="col-lg-3">
                        		<input type="text" class="form-control form-control-sm resfield" placeholder="Регистрийн дугаар" name="person[PersonRegisterNumber]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
                        	</div>
                        	<div class="col-lg-3">
                        		<button type="button" class="btn btn-outline-brand btn-sm " id="checkregister">Шалгах</button>
                        	</div>
                        </div>
    					<div id="mainfield"></div>
					</div>
				</div>
			</div>
			<div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
				<div class="kt-form__section kt-form__section--first">
					<div class="kt-wizard-v1__form mt-0">
						<div class="row">
							<div class="col-lg-6 form-group">
                				<label class="font-12">Нэгж *:</label>
                				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="employee[EmployeeDepartmentID]" 
                					data-url="<?=RF;?>/m/ajax/select?classid=<?=\Humanres\PositionClass::CLASS_ELECT?>" 
                					data-action="position"
                					data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
                					data-target="#formpositionlist" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                					<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$val_selected])?>
                				</select>
                			</div>
                			<div class="col-lg-6 form-group">
                				<label class="font-12">Албан тушаал*:</label>
                				<select class="form-control form-control-sm" id="formpositionlist" name="employee[EmployeePositionID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                				</select>
                			</div>
						</div>
						<div class=" row">
                    		<div class="col-lg-4 form-group">
                    			<label class="font-12">Томилогдсон байдал *:</label>
                    			<select class="form-control  form-control-sm resfield" data-col-index="2" name="employee[EmployeeStartID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                    				<?php \System\Combo::getCombo(["data"=>$_startList,"title"=>"RefStartTitle","value"=>"RefStartID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
                    			</select>
                    		</div>
                    		<div class="col-lg-4 form-group">
                        		<label class="font-12">Томилогдсон огноо *:</label>
                        		<div class="input-group date">
                        			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="employee[EmployeeStartDate]" placeholder="Өдөр сонгох" value="" data-rule-required="true" data-msg-required="Хоосон байна."/>
                        			<div class="input-group-append">
                        				<span class="input-group-text">
                        					<i class="la la-calendar-check-o"></i>
                        				</span>
                        			</div>
                        		</div>
                        	</div>
                    	</div>
					</div>
				</div>
			</div>
			<div class="kt-form__actions">
				<button class="btn btn-secondary  btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-prev">
					Өмнөх
				</button>
				<button class="btn btn-success  btn-tall btn-wide kt-font-bold kt-font-transform-u" data-ktwizard-type="action-submit">
					Хадгалах
				</button>
				<button class="btn btn-brand  btn-tall btn-wide kt-font-bold kt-font-transform-u d-none" data-ktwizard-type="action-next">
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
