<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_icon="flaticon2-edit";
    $_title="Албан хаагчийн цэргийн жинхэнэ алба хаасан мэдээлэл засварлах";
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    
    $_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
    
    if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){
        
        $refObj=\Humanres\ReferenceClass::getInstance();
        $_soldierList=$refObj->getRowList(["orderby"=>"RefSoldierOrder"],\Humanres\ReferenceClass::TBL_SOLDIER);
        
        $is_now=!$personObj->PersonIsSoldiering;
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanreselect/editperson" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Цэргийн жинхэнэ алба хаасан эсэх :</label>
        	<div class="col-lg-3">
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="person[PersonIsSoldiering]" <?=!$personObj->PersonIsSoldiering?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="person[PersonIsSoldiering]" <?=$personObj->PersonIsSoldiering?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
				</div>
			</div>
		</div>
		<div id="soldierfield" class="<?=$is_now?"d-none":""?>">
			<div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Цэргийн үүрэгтний үнэмлэхний дугаар:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=$is_now?"disabled":""?> name="person[PersonSoldierPassNo]" value="<?=$personObj->PersonSoldierPassNo?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Цэргийн жинхэнэ алба хаасан он:</label>
            	<div class="col-lg-3">
            		<input type="number" class="form-control form-control-sm font-12" <?=$is_now?"disabled":""?> name="person[PersonSoldierYear]" value="<?=$personObj->PersonSoldierYear?>"  data-rule-required="true" data-msg-required="хоосон байна.">
            	</div>
            </div>
            <div class="form-group row">
    			<label class="col-lg-3 col-form-label font-12">Цэргийн жинхэнэ алба хаасан байдал :</label>
    			<div class="col-lg-6">
    				<select class="form-control font-12" name="person[PersonSoldierID]" <?=$is_now?"disabled":""?> data-rule-required="true" data-msg-required="хоосон байна.">
                        <?php \System\Combo::getCombo(["data"=>$_soldierList,"title"=>"RefSoldierTitle","value"=>"RefSoldierID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->PersonSoldierID])?>
    				</select>
    			</div>
    		</div>
    		<div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Тайлбар:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" <?=$is_now?"disabled":""?> name="person[PersonSoldierDescr]" value="<?=$personObj->PersonSoldierDescr?>">
            	</div>
            </div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<?php if($_id>0){?>
<!--     <button type="button" class="btn btn-danger mr-auto" id="delete">Устгах</button> -->
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
       <?=\Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд бүртгэх эрх байхгүй байна"]);?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>
<?php }?>