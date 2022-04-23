<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){

    $salaryObj=\Humanres\PersonSalaryClass::getInstance()->getRow(['salary_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Цалин хөлсний бүртгэл засварлах":"Цалин хөлсний бүртгэл хийх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_degreeList=$refObj->getRowList(["orderby"=>"RefDegreeOrder"],\Humanres\ReferenceClass::TBL_SALARY_DEGREE);
    $_condList=$refObj->getRowList(["orderby"=>"RefConditionOrder"],\Humanres\ReferenceClass::TBL_SALARY_CONDITION);
    $_eduList=$refObj->getRowList(["orderby"=>"RefEduOrder"],\Humanres\ReferenceClass::TBL_SALARY_EDU);
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanreselect/<?=$_paramid>0?"updatesalary":"addsalary"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Үндсэн цалин:</label>
        	<div class="col-lg-3">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="salary[SalaryValue]" value="<?=$salaryObj->SalaryValue?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Зэрэг дэвийн нэмэгдэл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="salary[SalaryDegreeID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_degreeList,"title"=>"RefDegreeTitle","value"=>"RefDegreeID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$salaryObj->SalaryDegreeID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Онцгой нөхцлийн нэмэгдэл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="salary[SalaryConditionID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <option value="0" <?=$salaryObj->SalaryConditionID==0?"selected":""?>> --- Нэмэгдэл байхгүй ---</option>
                    <?php \System\Combo::getCombo(["data"=>$_condList,"title"=>"RefConditionTitle","value"=>"RefConditionID","flag"=>\System\Combo::SELECT_NONE,'selected'=>$salaryObj->SalaryConditionID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Эрдмийн зэрэг, цолны нэмэгдэл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="salary[SalaryEduID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <option value="0" <?=$salaryObj->SalaryEduID==0?"selected":""?>> --- Нэмэгдэл байхгүй ---</option>
                    <?php \System\Combo::getCombo(["data"=>$_eduList,"title"=>"RefEduTitle","value"=>"RefEduID","flag"=>\System\Combo::SELECT_NONE,'selected'=>$salaryObj->SalaryEduID])?>
				</select>
			</div>
		</div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanreselect/removesalary">Устгах</button>
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
   <?php 
    echo \Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд бүртгэх эрх байхгүй байна"]);
    ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>
<?php }?>
