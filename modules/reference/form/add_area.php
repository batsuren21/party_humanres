<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_AREA);
    $_parentid=isset($_POST['parentid'])?$_POST['parentid']:0;
    $_id=isset($_POST['id'])?$_POST['id']:0;
    
    if($_priv_reg && ($_parentid>0 || $_id>0)){
        
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Нутаг дэвсгэр засварлах":"Нутаг дэвсгэр бүртгэх";
        
        $_isdelete=1;
        
        $areaObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_id]);
        if($_parentid>0){
            $areaParentObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$_parentid]);
        }else{
            $_childCount=\Office\AreaClass::getInstance()->getRowCount(['area_parentid'=>$areaObj->AreaID]);
            if($_childCount>0) $_isdelete=0;
            $areaParentObj=\Office\AreaClass::getInstance()->getRow(['area_id'=>$areaObj->AreaParentID]);
        }
?>
<form class="kt-form kt-form--label-right" id="areaForm" action="<?=RF;?>/process/reference/<?=$_id>0?"editarea":"addarea"?>" enctype="multipart/form-data">
<input type="hidden" name="area[AreaParentID]" value="<?=$_parentid;?>" >
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
		<div class=" row">
			<div class="col-lg-12 form-group">
				<label class="font-12">Сонгогдсон нутаг дэвсгэр *:</label>
				<input type="text" class="form-control form-control-sm" value="<?=$areaParentObj->AreaName?>" disabled="disabled" >
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-12 form-group">
				<label class="font-12">Нутаг дэвсгэрийн нэр *:</label>
				<input type="text" class="form-control form-control-sm" placeholder="Нутаг дэвсгэрийн нэр" name="area[AreaName]" value="<?=$areaObj->AreaName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>	
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_isdelete){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete">Устгах</button>
 	<?php }?>
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
<?php }?>
