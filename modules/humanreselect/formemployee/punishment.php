<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
if($_priv_reg){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    $punishmentObj=\Humanres\PersonPunishmentClass::getInstance()->getRow(['punishment_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Сахилгын шийтгэл оногдуулсан бүртгэл":"Сахилгын шийтгэл оногдуулсан бүртгэл";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_punishmentList=$refObj->getRowList(["orderby"=>"RefPunishmentOrder"],\Humanres\ReferenceClass::TBL_PUNISHMENT);
   
    $is_now=$punishmentObj->isExist()?($punishmentObj->PunishmentRefID==2?1:0):0;
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanreselect/<?=$_paramid>0?"updatepunishment":"addpunishment"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Сахилгын шийтгэлийн төрөл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="punishment[PunishmentRefID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_punishmentList,"title"=>"RefPunishmentTitle","value"=>"RefPunishmentID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$punishmentObj->PunishmentRefID])?>
				</select>
			</div>
		</div>
		<div class="<?=$is_now?"":"d-none"?>"  id="formIsNow">
			<div class="form-group row">
				<label class="col-lg-3 col-form-label font-12">Хугацаа: </label>
            	<div class="col-lg-2">
            		<input type="text" class="form-control form-control-sm font-12" <?=$is_now?"":"disabled"?> name="punishment[PunishmentTime]" value="<?=$punishmentObj->PunishmentTime;?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
        	</div>
    		<div class="form-group row">
        		<label class="col-lg-3 col-form-label font-12">Хувь: </label>
            	<div class="col-lg-2">
            		<input type="text" class="form-control form-control-sm font-12" <?=$is_now?"":"disabled"?> name="punishment[PunishmentPercent]" value="<?=$punishmentObj->PunishmentPercent;?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
        	</div>
        </div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Тушаал шийдвэрийн дугаар:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="punishment[PunishmentOrder]" value="<?=$punishmentObj->PunishmentOrder?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Тушаал шийдвэрийн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="punishment[PunishmentOrderDate]" placeholder="Өдөр сонгох" value="<?=$punishmentObj->PunishmentOrderDate;?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Үндэслэл: </label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="punishment[PunishmentReason]" value="<?=$punishmentObj->PunishmentReason;?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanreselect/removepunishment">Устгах</button>
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
