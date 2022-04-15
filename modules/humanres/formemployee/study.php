<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){

    $studyObj=\Humanres\PersonStudyClass::getInstance()->getRow(['study_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Сургалтад хамрагдсан байдал засварлах":"Сургалтад хамрагдсан байдал бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_directionList=$refObj->getRowList(["orderby"=>"RefDirectionOrder"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION);
    $_countryList=\Office\RefCountryClass::getInstance()->getRowList(['orderby'=>"CountryOrder desc, CountryID"]);
    $_directionid=$studyObj->StudyDirectionID>0?$studyObj->StudyDirectionID:-1;
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanres/<?=$_paramid>0?"updatestudy":"addstudy"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Сургалтын ангилал:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="study[StudyDirectionID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_directionList,"title"=>"RefDirectionTitle","value"=>"RefDirectionID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$studyObj->StudyDirectionID])?>
				</select>
			</div>
		</div>
		<div id="studyfield"><?php require_once 'study_field.php';?></div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Улс:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="study[StudyCountryID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_countryList,"title"=>"CountryName","value"=>"CountryID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$studyObj->StudyCountryID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Сургалтын байгууллагын нэр:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="study[StudySchoolTitle]" value="<?=$studyObj->StudySchoolTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Сургалтын нэр, сэдэв:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="study[StudyTitle]" value="<?=$studyObj->StudyTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Элссэн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="study[StudyDateStart]" placeholder="Өдөр сонгох" value="<?=$studyObj->StudyDateStart?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form-group row " >
        	<label class="col-lg-3 col-form-label font-12">Төгссөн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker" name="study[StudyDateEnd]" placeholder="Өдөр сонгох" value="<?=$studyObj->StudyDateEnd?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Тайлбар:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="study[StudyDescr]" value="<?=$studyObj->StudyDescr?>">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Үнэмлэх, гэрчилгээний дугаар:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="study[StudyLicence]" value="<?=$studyObj->StudyLicence?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row " >
        	<label class="col-lg-3 col-form-label font-12">Олгосон огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker" name="study[StudyLicenceDate]" placeholder="Өдөр сонгох" value="<?=$studyObj->StudyLicenceDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
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
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removestudy">Устгах</button>
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
