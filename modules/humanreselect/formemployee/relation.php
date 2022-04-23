<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
$_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){

    $relationObj=\Humanres\PersonRelationClass::getInstance()->getRow(['relation_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Албан хаагчийн төрөл садангийн байдалын бүртгэл засварлах":"Албан хаагчийн төрөл садангийн байдалын бүртгэл бүртгэх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_relationList=$refObj->getRowList(["orderby"=>"RefRelationOrder"],\Humanres\ReferenceClass::TBL_RELATION);
    $_jobList=$refObj->getRowList(["orderby"=>"RefTypeOrder"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
    
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanreselect/<?=$_paramid>0?"updaterelation":"addrelation"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Харилцааны төрөл :</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="relation[RelationRelationID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_relationList,"title"=>"RefRelationTitle","value"=>"RefRelationID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$relationObj->RelationRelationID])?>
				</select>
			</div>
		</div>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Эцэг, эхийн нэр:</label>
        	<div class="col-lg-4">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="relation[RelationLastName]" value="<?=$relationObj->RelationLastName?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Өөрийн нэр:</label>
        	<div class="col-lg-4">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="relation[RelationFirstName]" value="<?=$relationObj->RelationFirstName?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Хөдөлмөр эрхлэлтийн байдал :</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="relation[RelationJobTypeID]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>$_jobList,"title"=>"RefTypeTitle","value"=>"RefTypeID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$relationObj->RelationJobTypeID])?>
				</select>
			</div>
		</div>
        <div class="<?=in_array($relationObj->RelationJobTypeID,[6,7,8,9,10,11,12])?"d-none":""?>" id="formJobType">
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Байгууллагын нэр:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=in_array($relationObj->FamilyJobTypeID,[6,7,8,9,10,11,12])?"disabled":""?> name="relation[RelationJobOrgan]" value="<?=$relationObj->RelationJobOrgan?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Албан тушаал:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=in_array($relationObj->FamilyJobTypeID,[6,7,8,9,10,11,12])?"disabled":""?> name="relation[RelationJobPosition]" value="<?=$relationObj->RelationJobPosition?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanreselect/removerelation">Устгах</button>
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
