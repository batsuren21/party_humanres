<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_PETSECTORLIST);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Бичлэг засварлах":"Бичлэг бүртгэх";
        
        $mainObj=\Office\RefSectorClass::getInstance()->getRow(['sector_id'=>$_id]);
        
        $count_petition=0;
        $count_felony=0;
        if($mainObj->isExist()){
            $count_petition=\Office\PetitionRefSectorClass::getInstance()->getRowCount(["refsector_sectorid"=>$mainObj->SectorID]);
            $count_felony=\Office\FelonyRefSectorClass::getInstance()->getRowCount(["refsector_sectorid"=>$mainObj->SectorID]);
        }
        
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/reference/<?=$_id>0?"editpetsector":"addpetsector"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
		<div class=" row">
			<div class="col-lg-12 form-group">
				<label class="font-12">Нэр *:</label>
				<input type="text" class="form-control form-control-sm" placeholder="Нэр" name="sector[SectorTitle]" value="<?=$mainObj->SectorTitle?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-4 form-group">
				<label class="font-12">Эрэмбэ *:</label>
				<input type="number" class="form-control form-control-sm" placeholder="Эрэмбэ" name="sector[SectorOrder]" value="<?=$mainObj->SectorOrder?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-4 form-group">
				<label class="font-12">Идэвхитэй эсэх *:</label>
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="sector[SectorIsShow]" <?=$mainObj->isExist() && $mainObj->SectorIsShow?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="sector[SectorIsShow]" <?=!$mainObj->isExist() || !$mainObj->SectorIsShow?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
				</div>
			</div>
		</div>		
    </div>
</div>

<div class="modal-footer">
	<?php if($_id>0 && $count_petition<1 && $count_felony<1){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete">Устгах</button>
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
