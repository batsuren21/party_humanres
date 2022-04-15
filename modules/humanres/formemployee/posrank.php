<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){

    $posrankObj=\Humanres\PersonPosRankClass::getInstance()->getRow(['posrank_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Албан тушаалын зэрэг, дэв засварлах":"Албан тушаалын зэрэг, дэв бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_posrankList=$refObj->getRowList(["orderby"=>"RefRankOrder"],\Humanres\ReferenceClass::TBL_POS_RANK);    
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanres/<?=$_paramid>0?"updateposrank":"addposrank"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Зэрэг, дэв:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="pos[PosRankID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_posrankList,"title"=>"RefRankTitle","value"=>"RefRankID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$posrankObj->PosRankID])?>
				</select>
			</div>
		</div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Шийдвэрийн огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="pos[PosDate]" placeholder="Өдөр сонгох" value="<?=$posrankObj->PosDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
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
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="pos[PosNumber]" value="<?=$posrankObj->PosNumber?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removeposrank">Устгах</button>
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
