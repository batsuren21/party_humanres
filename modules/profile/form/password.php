<?php
// $_SESSION[SESSSYSINFO]->PersonID
    $userObj=\Office\Permission::getLoggedUser();
   
?>
<form class="kt-form kt-form--label-right" id="petitionFormAdmin" action="<?=RF;?>/process/profile/changepassword" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title">Нууц үг солих</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
       <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Хуучин нууц үг:</label>
        	<div class="col-lg-3">
        		<input type="password" class="form-control form-control-sm font-12" placeholder="" name="password[PasswordOld]" value="" data-rule-required="true" data-msg-required="Хоосон байна">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Шинэ нууц үг:</label>
        	<div class="col-lg-3">
        		<input type="password" class="form-control form-control-sm font-12" placeholder="" name="password[PasswordNew]" value="" data-rule-required="true" data-msg-required="Хоосон байна">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Шинэ нууц үгийн давталт:</label>
        	<div class="col-lg-3">
        		<input type="password" class="form-control form-control-sm font-12" placeholder="" name="password[PasswordNewRepeat]" value="" data-rule-required="true" data-msg-required="Хоосон байна">
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
    <button type="submit" class="btn btn-success ">Нууц үг солих</button>
</div>
</form>