<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){
    
    $familyObj=\Humanres\PersonFamilyClass::getInstance()->getRow(['family_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Албан хаагчийн гэр бүлийн байдалын бүртгэл засварлах":"Албан хаагчийн гэр бүлийн байдалын бүртгэл бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_relationList=$refObj->getRowList(["orderby"=>"RefRelationOrder"],\Humanres\ReferenceClass::TBL_RELATION);
    $_jobList=$refObj->getRowList(["orderby"=>"RefTypeOrder"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
    
    $_countryList=\Office\RefCountryClass::getInstance()->getRowList(['orderby'=>["CountryID"]]);
    $_areaList=\Office\AreaClass::getInstance()->getRowList(['area_parentid'=>1,'orderby'=>'AreaGlobalID, AreaName']);
    $_isbornabroad=$familyObj->FamilyBirthIsAbroad?1:0;
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanres/<?=$_paramid>0?"updatefamily":"addfamily"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Гэр бүлийн гишүүн :</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="family[FamilyRelationID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_relationList,"title"=>"RefRelationTitle","value"=>"RefRelationID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$familyObj->FamilyRelationID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Эцэг, эхийн нэр:</label>
        	<div class="col-lg-4">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="family[FamilyLastName]" value="<?=$familyObj->FamilyLastName?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Өөрийн нэр:</label>
        	<div class="col-lg-4">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="family[FamilyFirstName]" value="<?=$familyObj->FamilyFirstName?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Төрсөн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="family[FamilyBirthDate]" placeholder="Өдөр сонгох" value="<?=$familyObj->FamilyBirthDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Гадаадад төрсөн эсэх :</label>
			<div class="col-lg-3 ">
				<select class="form-control font-12" name="family[FamilyBirthIsAbroad]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>\Humanres\PersonClass::$is_true,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$familyObj->FamilyBirthIsAbroad])?>
				</select>
			</div>
        </div>
        <div class="form-group row <?=!$_isbornabroad?"d-none":""?>" id="isbornabroad">
			<label class="col-lg-3 col-form-label font-12">Төрсөн улс :</label>
			<div class="col-lg-4">
        		<select class="form-control form-control-sm font-12" data-col-index="2" name="family[FamilyBirthCountryID]" <?=!$_isbornabroad?"disabled":""?> data-rule-required="true" data-msg-required="Сонгоогүй байна.">
        			<?php \System\Combo::getCombo(["data"=>$_countryList,"title"=>"CountryName","value"=>"CountryID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$familyObj->FamilyBirthCountryID])?>
        		</select>
			</div>
		</div>
     	<div class="row <?=$_isbornabroad?"d-none":""?>" id="isbornabroad1">
			<label class="col-lg-3 col-form-label font-12 text-right">Төрсөн газар:</label>
			<div class="col-lg-4">
				<div class="col-lg-12 form-group">
    				<label class="font-12">Аймаг, нийслэл*:</label>
    				<select class="form-control kt-input form-control-sm ajax_select" <?=$_isbornabroad?"disabled":""?> data-col-index="6" name="family[FamilyBirthCityID]" 
            			data-url="<?=RF;?>/m/ajax/select" 
            			data-action="area"
            			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
            			data-target="#formareadistrictid" data-rule-required="true" data-msg-required="хоосон байна.">
            			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$familyObj->FamilyBirthCityID])?>
            		</select>
    			</div>
    			 <div class="form-group col-lg-12">
    		    	<label class="font-12">Сум, дүүрэг:</label>
    				<select class="form-control form-control-sm" id="formareadistrictid" <?=$_isbornabroad?"disabled":""?> name="family[FamilyBirthDistrictID]" data-selected="<?=$familyObj->FamilyBirthDistrictID;?>" data-rule-required="true" data-msg-required="хоосон байна.">
        			</select>
                </div>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Хөдөлмөр эрхлэлтийн байдал :</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="family[FamilyJobTypeID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_jobList,"title"=>"RefTypeTitle","value"=>"RefTypeID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$familyObj->FamilyJobTypeID])?>
				</select>
			</div>
		</div>
		<div class="<?=in_array($familyObj->FamilyJobTypeID,[6,7,8,9,10,11,12])?"d-none":""?>" id="formJobType">
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Байгууллагын нэр:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=in_array($familyObj->FamilyJobTypeID,[6,7,8,9,10,11,12])?"disabled":""?> name="family[FamilyJobOrgan]" value="<?=$familyObj->FamilyJobOrgan?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Албан тушаал:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=in_array($familyObj->FamilyJobTypeID,[6,7,8,9,10,11,12])?"disabled":""?> name="family[FamilyJobPosition]" value="<?=$familyObj->FamilyJobPosition?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removefamily">Устгах</button>
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
