<?php
if(!isset($personObj)){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    $awardObj=\Humanres\PersonAwardClass::getInstance()->getRow(['award_id'=>$_paramid]);
    $_parentid=isset($_POST['parentid'])?$_POST['parentid']:-1;
}
$_awardSubList=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_parentid"=>$_parentid,"orderby"=>"RefAwardOrder"],\Humanres\ReferenceClass::TBL_AWARD);
if(in_array($_parentid, [1,2,3,4,5,26,27,28])){
    $_title=in_array($_parentid, [1,3])?"Шагналын нэр":"Шагналын дэд төрөл";
?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12"><?=$_title;?>:</label>
	<div class="col-lg-6">
		<select class="form-control font-12 " name="award[AwardRefSubID]" data-rule-required="true" data-msg-required="хоосон байна.">
            <?php \System\Combo::getCombo(["data"=>$_awardSubList,"title"=>"RefAwardTitle","value"=>"RefAwardID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$awardObj->AwardRefSubID])?>
		</select>
	</div>
</div>
<?php }?>
<?php if(in_array($_parentid, [4,5])){?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Байгууллагын нэр:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="award[AwardOrganTitle]" value="<?=$awardObj->AwardOrganTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<?php }?>
<?php if(in_array($_parentid, [2,4,5])){?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Шагналын нэр:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="award[AwardTitle]" value="<?=$awardObj->AwardTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<?php }?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Шийдвэрийн огноо:</label>
	<div class="col-lg-3">
		<div class="input-group date">
			<input type="text" class="form-control form-control-sm  datepicker"  name="award[AwardDate]" placeholder="Өдөр сонгох" value="<?=$awardObj->AwardDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
			<div class="input-group-append">
				<span class="input-group-text">
					<i class="la la-calendar-check-o"></i>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Шийдвэрийн дугаар:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="award[AwardLicence]" value="<?=$awardObj->AwardLicence?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
