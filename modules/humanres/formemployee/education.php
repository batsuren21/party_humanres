<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);

$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){
    
    $educationObj=\Humanres\PersonEducationClass::getInstance()->getRow(['education_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Боловсролын байдал засварлах":"Боловсролын байдал бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_levelList=$refObj->getRowList(["orderby"=>"RefLevelOrder"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_degreeList=$refObj->getRowList(["orderby"=>"RefDegreeOrder"],\Humanres\ReferenceClass::TBL_EDUCATION_DEGREE);
    $_schoolList=$refObj->getRowList(["orderby"=>"RefSchoolOrder"],\Humanres\ReferenceClass::TBL_EDUCATION_SCHOOL);
    
    $is_now=$educationObj->isExist()?$educationObj->EducationIsNow:0;
    $is_edulevel=$educationObj->isExist() && $educationObj->EducationLevelID<6?1:0;
    
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanres/<?=$_paramid>0?"updateeducation":"addeducation"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Сургуулийн төрөл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="education[EducationSchoolID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_schoolList,"title"=>"RefSchoolTitle","value"=>"RefSchoolID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$educationObj->EducationSchoolID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Боловсролын түвшин:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="education[EducationLevelID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_levelList,"title"=>"RefLevelTitle","value"=>"RefLevelID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$educationObj->EducationLevelID])?>
				</select>
			</div>
		</div>
		<div class="form-group row <?=$is_edulevel?"d-none":""?>" id="edulevel">
			<label class="col-lg-3 col-form-label font-12">Боловсролын зэрэг:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="education[EducationDegreeID]" <?=$is_edulevel?"disabled":""?> data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_degreeList,"title"=>"RefDegreeTitle","value"=>"RefDegreeID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$educationObj->EducationDegreeID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Сургуулийн нэр:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="education[EducationSchoolTitle]" value="<?=$educationObj->EducationSchoolTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Элссэн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="education[EducationDateStart]" placeholder="Өдөр сонгох" value="<?=$educationObj->EducationDateStart?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Одоо сурч байгаа эсэх :</label>
        	<div class="col-lg-3">
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="education[EducationIsNow]" <?=!$is_now?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="education[EducationIsNow]" <?=$is_now?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
				</div>
			</div>
		</div>
		<div class="<?=$is_now?"d-none":""?>" id="formIsNow">
            <div class="form-group row ">
            	<label class="col-lg-3 col-form-label font-12">Төгссөн огноо:</label>
            	<div class="col-lg-3">
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker" name="education[EducationDateEnd]" placeholder="Өдөр сонгох" <?=$is_now?"disabled":""?> value="<?=$educationObj->EducationDateEnd?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Гэрчилгээ, дипломын дугаар:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="education[EducationLicence]"  <?=$is_now?"disabled":""?> value="<?=$educationObj->EducationLicence?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
        </div>
        <div class="form-group row <?=$is_edulevel?"d-none":""?>" id="eduprof">
        	<label class="col-lg-3 col-form-label font-12">Мэргэжил:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" <?=$is_edulevel?"disabled":""?> name="education[EducationProfession]" value="<?=$educationObj->EducationProfession?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removeeducation">Устгах</button>
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
