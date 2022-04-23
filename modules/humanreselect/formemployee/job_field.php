<?php
if(!isset($personObj)){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    $jobObj=\Humanres\PersonJobClass::getInstance()->getRow(['job_id'=>$_paramid]);
    $_typeid=isset($_POST['typeid'])?$_POST['typeid']:-1;
    if(isset($_POST['isnow'])){
        $is_now=$_POST['isnow'];
    }else{
        $is_now=$jobObj->isExist()?$jobObj->JobIsNow:0;
    }
    $is_notorgan=$jobObj->isExist() && $jobObj->JobOrganID==6 && $_typeid!=1;
}

if($_typeid==2){
    $_organList=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"RefOrganOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
    $_organSubList=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_type"=>[1,3],"orderby"=>"RefOrganSubOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Байгууллагын ангилал:</label>
	<div class="col-lg-6">
		<select class="form-control font-12" name="job[JobOrganID]" data-rule-required="true" data-msg-required="хоосон байна." id="joborganid">
            <?php \System\Combo::getCombo(["data"=>$_organList,"title"=>"RefOrganTitle","value"=>"RefOrganID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$jobObj->JobOrganID])?>
		</select>
	</div>
</div>
<div class="form-group row <?=$jobObj->JobOrganID!=1?"d-none":"";?>" id="type1">
	<label class="col-lg-3 col-form-label font-12">Байгууллагын дэд ангилал:</label>
	<div class="col-lg-6">
		<select class="form-control font-12" name="job[JobOrganSubID]" <?=$jobObj->JobOrganID!=1?"disabled":"";?> data-rule-required="true" data-msg-required="хоосон байна.">
            <?php \System\Combo::getCombo(["data"=>$_organSubList,"title"=>"RefOrganSubTitle","value"=>"RefOrganSubID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$jobObj->JobOrganSubID])?>
		</select>
	</div>
</div>
<div class="<?=$jobObj->JobOrganID==1?"d-none":"";?>" id="typeOther">
	<input type="hidden" name="job[JobOrganSubID]" class="form-control" <?=$jobObj->JobOrganID==1?"disabled":"";?> value="0">
</div>
<?php 
}elseif($_typeid==1){
    $_organSubList=\Humanres\ReferenceClass::getInstance()->getRowList(["ref_type"=>[1,2],"orderby"=>"RefOrganSubOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
?>
<input type="hidden" name="job[JobOrganID]" value="1">
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Цэргийн байгууллагын ангилал:</label>
	<div class="col-lg-6">
		<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="job[JobOrganSubID]" 
			data-url="<?=RF;?>/m/ajax/select" 
			data-action="ref_jobposition"
			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
			data-target="#formjobposition" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            <?php \System\Combo::getCombo(["data"=>$_organSubList,"title"=>"RefOrganSubTitle","value"=>"RefOrganSubID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$jobObj->JobOrganSubID])?>
		</select>
	</div>
</div>
<?php }?>
<div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
	<label class="col-lg-3 col-form-label font-12">Байгууллагын нэр:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" <?=$is_notorgan?"disabled":""?> name="job[JobOrganTitle]" value="<?=$jobObj->JobOrganTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
	<label class="col-lg-3 col-form-label font-12">Газар, хэлтэс, алба:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" <?=$is_notorgan?"disabled":""?> name="job[JobDepartmentTitle]" value="<?=$jobObj->JobDepartmentTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<?php if($_typeid==1){?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12">Албан тушаал:</label>
	<div class="col-lg-6">
		<select class="form-control font-12" name="job[JobPositionID]" id="formjobposition" data-rule-required="true" data-msg-required="хоосон байна." data-selected="<?=$jobObj->JobPositionID?>">
		</select>
	</div>
</div>
<?php }else{?>
<div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
	<label class="col-lg-3 col-form-label font-12">Албан тушаал:</label>
	<div class="col-lg-6">
		<input type="text" class="form-control form-control-sm font-12" <?=$is_notorgan?"disabled":""?> name="job[JobPositionTitle]" value="<?=$jobObj->JobPositionTitle?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<?php }?>
<div class="form-group row">
	<label class="col-lg-3 col-form-label font-12" id="_startdate"><?=$is_notorgan?"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож эхэлсэн огноо:":"Ажилд орсон огноо:"?></label>
	<div class="col-lg-3">
		<div class="input-group date">
			<input type="text" class="form-control form-control-sm  datepicker"  name="job[JobDateStart]" placeholder="Өдөр сонгох" value="<?=$jobObj->JobDateStart?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
			<div class="input-group-append">
				<span class="input-group-text">
					<i class="la la-calendar-check-o"></i>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
	<label class="col-lg-3 col-form-label font-12">Ажилд орсон тушаалын дугаар:</label>
	<div class="col-lg-3">
		<input type="text" class="form-control form-control-sm font-12" <?=$is_notorgan?"disabled":""?> name="job[JobStartOrder]" value="<?=$jobObj->JobStartOrder?>"  data-rule-required="true" data-msg-required="хоосон байна.">
	</div>
</div>
<div class="<?=$is_now?"d-none":""?>" id="formIsNow">
    <div class="form-group row">
    	<label class="col-lg-3 col-form-label font-12" id="_enddate"><?=$is_notorgan?"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож дууссан огноо:":"Ажлаас гарсан огноо:"?></label>
    	<div class="col-lg-3">
    		<div class="input-group date">
    			<input type="text" class="form-control form-control-sm  datepicker"  <?=$is_now?"disabled":""?>  name="job[JobDateQuit]" placeholder="Өдөр сонгох" value="<?=$jobObj->JobDateQuit?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
    			<div class="input-group-append">
    				<span class="input-group-text">
    					<i class="la la-calendar-check-o"></i>
    				</span>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
    	<label class="col-lg-3 col-form-label font-12">Чөлөөлөгдсөн үндэслэл:</label>
    	<div class="col-lg-6">
    		<input type="text" class="form-control form-control-sm font-12"  <?=$is_now || $is_notorgan?"disabled":""?> name="job[JobQuitReason]" value="<?=$jobObj->JobQuitReason?>"  data-rule-required="true" data-msg-required="хоосон байна.">
    	</div>
    </div>
    <div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
    	<label class="col-lg-3 col-form-label font-12">Чөлөөлөгдсөн тушаалын дугаар:</label>
    	<div class="col-lg-6">
    		<input type="text" class="form-control form-control-sm font-12"  <?=$is_now || $is_notorgan?"disabled":""?> name="job[JobQuitOrder]" value="<?=$jobObj->JobQuitOrder?>"  data-rule-required="true" data-msg-required="хоосон байна.">
    	</div>
    </div>
    <div class="form-group row notorgan <?=$is_notorgan?"d-none":""?>">
    	<label class="col-lg-3 col-form-label font-12">Чөлөөлөгдсөн тушаалын огноо:</label>
    	<div class="col-lg-3">
    		<div class="input-group date">
    			<input type="text" class="form-control form-control-sm  datepicker"  name="job[JobQuitOrderDate]"  <?=$is_now || $is_notorgan?"disabled":""?> placeholder="Өдөр сонгох" value="<?=$jobObj->JobQuitOrderDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
    			<div class="input-group-append">
    				<span class="input-group-text">
    					<i class="la la-calendar-check-o"></i>
    				</span>
    			</div>
    		</div>
    	</div>
    </div>
</div>