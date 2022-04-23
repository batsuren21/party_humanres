<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
$_id=isset($_POST['id'])?$_POST['id']:0;
$_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;

$personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
if($_priv_reg){
    
    $billObj=\Humanres\PersonHolidayBillClass::getInstance()->getRow(['bill_id'=>$_paramid]);
    
    $_icon=$_paramid>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_paramid>0?"Ээлжийн амралтын тооцоо засварлах":"Ээлжийн амралтын тооцооны бүртгэх";
    
?>
<form class="kt-form kt-form--label-right" id="felonyFormAdmin" action="<?=RF;?>/process/humanreselect/<?=$_paramid>0?"updatebill":"addbill"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Ажиллаж дуусах огноо:</label>
        	<div class="col-lg-3">
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker"  name="bill[BillJobDate]" placeholder="Өдөр сонгох" value="<?=$billObj->BillJobDate?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Ажилласан сар:</label>
        	<div class="col-lg-6">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="bill[BillTime]" value="<?=$billObj->BillTime?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Ээлжийн амралтын хугацаа:</label>
        	<div class="col-lg-6">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="bill[BillHolidayDay]" value="<?=$billObj->BillHolidayDay?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Олговорт ногдох мөнгөн дүн:</label>
        	<div class="col-lg-6">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="bill[BillValue]" value="<?=$billObj->BillValue?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Биеэр эдэлсэн өдөр:</label>
        	<div class="col-lg-6">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="bill[BillHolidayDay1]" value="<?=$billObj->BillHolidayDay1?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
        <div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Биеэр эдлээгүй өдөр:</label>
        	<div class="col-lg-6">
        		<input type="number" class="form-control form-control-sm font-12" placeholder="" name="bill[BillHolidayDay2]" value="<?=$billObj->BillHolidayDay2?>"  data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0 && $_paramid>0){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanreselect/removebill">Устгах</button>
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
