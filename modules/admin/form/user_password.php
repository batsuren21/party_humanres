<?php 
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_ACCESS);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_USER_PRIV);
    
    if($_priv_access && $_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon="flaticon2-edit";
        $_title="Нууц үг шинэчлэх";
        
        $userObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/admin/edituser" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
        <h5 class="kt-section__title">1. Хувийн мэдээлэл:</h5>
        <div class="kt-section__body">
        	<div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Нэгж: </label>
                <div class="col-4 pt-2">
                    <strong><?=$userObj->DepartmentFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Албан тушаал: </label>
                <div class="col-4 pt-2">
                    <strong><?=$userObj->PositionFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Эцэг эхийн нэр, өөрийн нэр: </label>
                <div class="col-4 pt-2">
                    <strong><?=$userObj->PersonLFName?></strong>
                </div>
            </div>
        </div>
        <h5 class="kt-section__title">2. Нууц үг:</h5>
        <div class="kt-section__body">
            <div class="form-group row">
                <label for="password" class="col-3 col-form-label font-12">Нэвтрэх нууц үг: </label>
                <div class="col-4">
                    <input class="form-control form-control-sm" type="password" value="" id="password" name="person[PersonUserPassword]" data-rule-required="true" data-msg-required="Хоосон байна.">
                </div>
            </div>
            <div class="form-group row">
                <label for="password_confirm" class="col-3 col-form-label font-12">Нууц үгийн давталт: </label>
                <div class="col-4">
                    <input class="form-control form-control-sm" type="password" value="" id="password_confirm" name="person[PersonUserPasswordConfirm]" data-rule-required="true" data-msg-required="Хоосон байна." data-rule-equalTo="#password" data-rule-equalTo="Нууц үгүүд өөр байна.">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
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
