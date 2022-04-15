<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){

    $jobObj=\Humanres\PersonJobClass::getInstance()->getRow(['job_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Хөдөлмөр эрхлэлтийн бүртгэл засварлах":"Хөдөлмөр эрхлэлтийн бүртгэл бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_organType=$refObj->getRowList(["orderby"=>"RefOrganTypeOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGANTYPE);
    
    $is_now=$jobObj->isExist()?$jobObj->JobIsNow:0;
    
    $is_notorgan=$jobObj->isExist() && $jobObj->JobOrganID==6;
    
    $_typeid=$jobObj->JobOrganTypeID>0?$jobObj->JobOrganTypeID:-1;
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanres/<?=$_paramid>0?"updatejob":"addjob"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Одоо ажиллаж байгаа эсэх :</label>
        	<div class="col-lg-3">
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="job[JobIsNow]" <?=!$is_now?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="job[JobIsNow]" <?=$is_now?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
				</div>
			</div>
		</div>
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Байгууллагын төрөл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="job[JobOrganTypeID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_organType,"title"=>"RefOrganTypeTitle","value"=>"RefOrganTypeID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$jobObj->JobOrganTypeID])?>
				</select>
			</div>
		</div>
		<div id="jobfield"><?php require_once 'job_field.php';?></div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removejob">Устгах</button>
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
