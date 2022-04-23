<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $personObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
        $_icon="flaticon2-edit";
        $_title="Үүрийн бүртгэл засварлах";
        
        $refObj=\Humanres\ReferenceClass::getInstance();
        
        $_startList=$refObj->getRowList(["ref_type"=>\Humanres\DepartmentClass::CLASS_BASIC,"orderby"=>"RefStartOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
        
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/editemployee" enctype="multipart/form-data">
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
                <label class="col-3 col-form-label font-12">Үүр: </label>
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
        <h5 class="kt-section__title">2. Бүртгэсэн байдал:</h5>
        <div class="kt-section__body">
            <div class=" row">
        		<div class="col-lg-6 form-group">
        			<label class="font-12">Бүртгэсэн байдал *:</label>
        			<select class="form-control  form-control-sm resfield" data-col-index="2" name="employee[EmployeeStartID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
        				<?php \System\Combo::getCombo(["data"=>$_startList,"title"=>"RefStartTitle","value"=>"RefStartID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$personObj->EmployeeStartID])?>
        			</select>
        		</div>
        		<div class="col-lg-6 form-group">
            		<label class="font-12">Шилжиж ирсэн, бүртгэсэн огноо*:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker"  name="employee[EmployeeStartDate]" placeholder="Өдөр сонгох" value="<?=$personObj->EmployeeStartDate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
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
<div class="modal-footer">
	<?php if($personObj->EmployeeIsActive){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removeemployee">Томилолт устгах</button>
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