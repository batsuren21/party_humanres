<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_icon="flaticon2-edit";
    $_title="Албан хаагчийн холбоо барих мэдээлэл засварлах";
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    $_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
    
    if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/editperson" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<div class=" row">
    		<div class="col-lg-4 form-group">
    			<label class="font-12">Гар утас *:</label>
    			<input type="text" class="form-control form-control-sm font-12" placeholder="" name="person[PersonContactMobilePhone]" value="<?=$personObj->PersonContactMobilePhone;?>">
    		</div>
    		<div class="col-lg-4 form-group">
    			<label class="font-12">Ажлын утас *:</label>
    			<input type="text" class="form-control form-control-sm font-12 " placeholder="" name="person[PersonContactWorkPhone]" value="<?=$personObj->PersonContactWorkPhone?>">
    		</div>
    		<div class="col-lg-4 form-group">
    			<label class="font-12">И-мэйл хаяг *:</label>
    			<input type="text" class="form-control form-control-sm font-12 " placeholder="" name="person[PersonContactEmail]" value="<?=$personObj->PersonContactEmail?>">
    		</div>
    	</div>
    	<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg mt-1 mb-1"></div>
        <div class="kt-section kt-section--last">
			<div class="kt-section__body">
				<h4 class="kt-section__title kt-section__title-lg ">Шаардлагатай үед холбоо барих хүний мэдээлэл:</h4>
                <div class="row">
        			<div class="col-lg-4 form-group">
            			<label class="font-12">Холбоо барих хүний нэр *:</label>
            			<input type="text" class="form-control form-control-sm font-12" placeholder="" name="person[PersonContactEmergencyName]" value="<?=$personObj->PersonContactEmergencyName;?>">
            		</div>
            		<div class="col-lg-4 form-group">
            			<label class="font-12">Холбоо барих утас *:</label>
            			<input type="text" class="form-control form-control-sm font-12 " placeholder="" name="person[PersonContactEmergencyPhone]" value="<?=$personObj->PersonContactEmergencyPhone?>">
            		</div>
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