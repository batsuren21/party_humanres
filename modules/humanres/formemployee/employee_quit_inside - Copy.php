<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);

if($_priv_reg){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $personObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
    $_icon="flaticon2-edit";
    $_title="Алба хооронд шилжих";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_quitList=$refObj->getRowList(["ref_id"=>[1,2],"orderby"=>"RefQuitOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT);
    
    $_count_letter=\Office\LetterClass::getInstance()->getRowCount(['letter_lastshiftpersonid'=>$personObj->PersonID]);
    $_count_petition=\Office\PetitionClass::getInstance()->getRowCount(['petition_lastshiftpersonid'=>$personObj->PersonID]);
    $_count_felony=\Office\FelonyClass::getInstance()->getRowCount(['felony_mainpersonid'=>$personObj->PersonID]);
    
    $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList([
        "department_isactive"=>1,
        "department_typeid"=>$personObj->DepartmentTypeID,
        'orderby'=>"DepartmentOrder"
    ]);
    $_startList=$refObj->getRowList(["ref_id"=>[1,2],"orderby"=>"RefStartOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/transferemployee" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
        <h5 class="kt-section__title">1. Хувийн мэдээлэл:</h5>
        <div class="kt-section__body">
        	<div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Нэгж: </label>
                <div class="col-4 pt-2">
                    <strong><?=$personObj->DepartmentFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Албан тушаал: </label>
                <div class="col-4 pt-2">
                    <strong><?=$personObj->PositionFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Эцэг эхийн нэр, өөрийн нэр: </label>
                <div class="col-4 pt-2">
                    <strong><?=$personObj->PersonLFName?></strong>
                </div>
            </div>
        </div>
        <h5 class="kt-section__title">2. Чөлөөлсөн байдал:</h5>
        <div class="kt-section__body">
        	<div class=" row">
        		<div class="col-lg-6 form-group">
    				<label class="font-12">Хөдөлгөөний төрөл *:</label>
    				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="employee[EmployeeQuitID]" 
    					data-url="<?=RF;?>/m/ajax/select" 
    					data-action="ref_quit"
    					data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
    					data-target="#formquitsub" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    					<?php \System\Combo::getCombo(["data"=>$_quitList,"title"=>"RefQuitTitle","value"=>"RefQuitID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->EmployeeQuitID])?>
    				</select>
    			</div>
    			<div class="col-lg-6 form-group">
    				<label class="font-12">Чөлөөлсөн байдал *:</label>
    				<select class="form-control form-control-sm" id="formquitsub" name="employee[EmployeeQuitSubID]" data-rule-required="true" data-msg-required="Сонгоогүй байна." data-selected="<?=$personObj->EmployeeQuitSubID?>">
    				</select>
    			</div>
        		
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Чөлөөлсөн огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker"  name="employee[EmployeeQuitDate]" placeholder="Өдөр сонгох" value="<?=$personObj->EmployeeQuitDate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Тушаалын огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker"  name="employee[EmployeeQuitOrderDate]" placeholder="Өдөр сонгох" value="<?=$personObj->EmployeeQuitOrderDate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            	<div class="col-lg-4 form-group">
        			<label class="font-12">Тушаалын дугаар *:</label>
        			<input type="text" class="form-control form-control-sm resfield" placeholder="Тушаалын дугаар" name="employee[EmployeeQuitOrderNo]" value="<?=$personObj->EmployeeQuitOrderNo?>" data-rule-required="true" data-msg-required="Хоосон байна.">
        		</div>
        	</div>
        </div>
        <h5 class="kt-section__title">3. Томилогдсон байдал:</h5>
        <div class="kt-section__body">
            <div class="row">
				<div class="col-lg-6 form-group">
    				<label class="font-12">Нэгж *:</label>
    				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="employee_start[EmployeeDepartmentID]" 
    					data-url="<?=RF;?>/m/ajax/select?type=4,7&notid=<?=$personObj->EmployeePositionID?>" 
    					data-action="position"
    					data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
    					data-target="#formpositionlist" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    					<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>""])?>
    				</select>
    			</div>
    			<div class="col-lg-6 form-group">
    				<label class="font-12">Албан тушаал *:</label>
    				<select class="form-control form-control-sm" id="formpositionlist" name="employee_start[EmployeePositionID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    				</select>
    			</div>
			</div>
			<div class=" row">
        		<div class="col-lg-4 form-group">
        			<label class="font-12">Албан тушаалд томилсон байдал *:</label>
        			<select class="form-control  form-control-sm resfield" data-col-index="2" name="employee_start[EmployeeStartID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
        				<?php \System\Combo::getCombo(["data"=>$_startList,"title"=>"RefStartTitle","value"=>"RefStartID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
        			</select>
        		</div>
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Томилогдсон огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="employee_start[EmployeeStartDate]" placeholder="Өдөр сонгох" value="" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Тушаалын огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="employee_start[EmployeeStartOrderDate]" placeholder="Өдөр сонгох" value="" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            	<div class="col-lg-4 form-group">
        			<label class="font-12">Тушаалын дугаар *:</label>
        			<input type="text" class="form-control form-control-sm resfield" placeholder="Тушаалын дугаар" name="employee_start[EmployeeStartOrderNo]" value="" data-rule-required="true" data-msg-required="Хоосон байна.">
        		</div>
        		
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
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