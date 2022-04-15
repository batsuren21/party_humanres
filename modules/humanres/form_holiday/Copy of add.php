<?php 
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_HOLIDAY);
    
if($_priv_reg){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_id>0?"Амралтын өдөр засварлах":"Амралтын өдөр нэмэх";
    
    $holidayObj=\Humanres\RefHolidayClass::getInstance()->getRow(['refholiday_id'=>$_id]);
    
    $_type=$_id>0?$holidayObj->RefHolidayType:0;
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/<?=$_id>0?"editrefholiday":"addrefholiday"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
    	<?php if($_id<1){?>
       <div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Төрөл:</label>
			<div class="col-lg-6">
				<select class="form-control font-12" name="holiday[RefHolidayType]" data-rule-required="true" data-msg-required="хоосон байна.">
                    <?php \System\Combo::getCombo(["data"=>\Humanres\RefHolidayClass::$_type,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$holidayObj->RefHolidayType])?>
				</select>
			</div>
		</div>
		<?php 
            }else{
                $_str=isset(\Humanres\RefHolidayClass::$_type[$holidayObj->RefHolidayType]['title'])?\Humanres\RefHolidayClass::$_type[$holidayObj->RefHolidayType]['title']:"";
        ?>
		<div class="form-group row">
			<label class="col-lg-3 col-form-label font-12">Төрөл:</label>
			<div class="col-lg-6">
				<?=$_str;?>
			</div>
		</div>
		<?php }?>
		<div class="form-group row">
        	<label class="col-lg-3 col-form-label font-12">Амралтын өдрийн нэр:</label>
        	<div class="col-lg-6">
        		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="holiday[RefHolidayTitle]" value="<?=$holidayObj->RefHolidayTitle?>" data-rule-required="true" data-msg-required="хоосон байна.">
        	</div>
        </div>
		<div id="type1" class="<?=$_type==1?"":"d-none"?>">
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Эхлэх огноо:</label>
            	<div class="col-lg-3">
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker" name="holiday[RefHolidayDateStart]" <?=$_type==1?"":"disabled='disabled'"?> placeholder="Өдөр сонгох" value="<?=$holidayObj->RefHolidayDateStart?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Дуусах огноо:</label>
            	<div class="col-lg-3">
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker" name="holiday[RefHolidayDateEnd]" <?=$_type==1?"":"disabled='disabled'"?> placeholder="Өдөр сонгох" value="<?=$holidayObj->RefHolidayDateEnd?>"  data-rule-required="true" data-msg-required="хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            </div>
		</div>
		<div id="type2" class="<?=$_type==2?"":"d-none"?>">
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Эхлэх сар, өдөр:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="holiday[RefHolidayDateStart]" <?=$_type==2?"":"disabled='disabled'"?> value="<?=$holidayObj->RefHolidayDateStart?>" data-rule-required="true" data-msg-required="хоосон байна." maxlength="5">
            		<span class="m-form__help">
						Формат: MM-DD
					</span>
        		</div>
            </div>
            <div class="form-group row">
            	<label class="col-lg-3 col-form-label font-12">Дуусах сар, өдөр:</label>
            	<div class="col-lg-6">
            		<input type="text" class="form-control form-control-sm font-12" placeholder="" name="holiday[RefHolidayDateEnd]" <?=$_type==2?"":"disabled='disabled'"?> value="<?=$holidayObj->RefHolidayDateEnd?>" data-rule-required="true" data-msg-required="хоосон байна." maxlength="5">
            		<span class="m-form__help">
						Формат: MM-DD
					</span>
            	</div>
            </div>
		</div>
    </div>
</div>
<div class="modal-footer">
	<?php if($_id>0){?>
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
