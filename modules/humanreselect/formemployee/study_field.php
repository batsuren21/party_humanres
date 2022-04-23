<?php
if(!isset($personObj)){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    $_directionid=isset($_POST['directionid'])?$_POST['directionid']:-1;
    $studyObj=\Humanres\PersonStudyClass::getInstance()->getRow(['study_id'=>$_paramid]);
    $refObj=\Humanres\ReferenceClass::getInstance();
}
$_listSub=$refObj->getRowList(["ref_dirsub_directionid"=>$_directionid,"orderby"=>"RefDirSubOrder"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB);
$_list1Sub=$refObj->getRowList(["ref_dirsub1_directionid"=>$_directionid,"orderby"=>"RefDirSub1Order"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB1);

if(count($_listSub)>0){
?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Сургалтын төрөл:</label>
	<div class="col-lg-6">
		<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="study[StudyDirSubID]" 
			data-url="<?=RF;?>/m/ajax/select" 
			data-action="ref_studydirsub"
			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
			data-target="#formstudydirsubid" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            <?php \System\Combo::getCombo(["data"=>$_listSub,"title"=>"RefDirSubTitle","value"=>"RefDirSubID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$studyObj->StudyDirSubID])?>
		</select>
	</div>
</div>
<?php if(count($_list1Sub)>0){?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Сургалтын дэд төрөл:</label>
	<div class="col-lg-6">
		<select class="form-control font-12" name="study[StudyDirSub1ID]" data-rule-required="true" data-msg-required="хоосон байна." id="formstudydirsubid" data-selected="<?=$studyObj->StudyDirSub1ID;?>">
		</select>
	</div>
</div>
<?php 
}
}elseif(count($_list1Sub)>0){
?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Сургалтын дэд төрөл:</label>
	<div class="col-lg-6">
		<select class="form-control font-12" name="study[StudyDirSub1ID]" data-rule-required="true" data-msg-required="хоосон байна.">
            <?php \System\Combo::getCombo(["data"=>$_list1Sub,"title"=>"RefDirSub1Title","value"=>"RefDirSub1ID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$studyObj->StudyDirSub1ID])?>
		</select>
	</div>
</div>
<?php }?>
