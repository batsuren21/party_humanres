<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_ORGANLIST);
    
    if($_priv_reg){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
        $_title=$_id>0?"Байгууллага засварлах":"Байгууллага бүртгэх";
        
        $organObj=\Office\OrganListClass::getInstance()->getRow(['organ_id'=>$_id]);
        
        $_genList=\Office\OrganFinanceGeneralClass::getInstance()->getRowList(['orderby'=>"FinGenTitle"]);
        $_strList=\Office\OrganFinanceStraightClass::getInstance()->getRowList(['orderby'=>"FinStrTitle"]);
        $_cityList=\Office\OrganAddressClass::getInstance()->getRowList(["address_parentid"=>0,'orderby'=>"Address"]);
        
        $count_accused=0;
        $count_letter=0;
        $count_sentletter=0;
        if($organObj->isExist()){
            $count_accused=\Office\AccusedClass::getInstance()->getRowCount(["accused_organid"=>$organObj->OrganID]);
            $count_letter=\Office\LetterClass::getInstance()->getRowCount(["letter_organid"=>$organObj->OrganID]);
            $count_sentletter=\Office\SentLetterRowsClass::getInstance()->getRowCount(["rows_organid"=>$organObj->OrganID]);
        }
        
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/reference/<?=$_id>0?"editorgan":"addorgan"?>" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
       <div class=" row">
   			<div class="col-lg-6 form-group">
				<label class="font-12">ТЕЗ *:</label>
				<select class="form-control  form-control-sm" data-col-index="2" name="organ[OrganFinGenID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
					<?php \System\Combo::getCombo(["data"=>$_genList,"title"=>"FinGenTitle","value"=>"FinGenID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$organObj->OrganFinGenID])?>
				</select>
			</div>
			<div class="col-lg-6 form-group">
				<label class="font-12">ТШЗ *:</label>
				<select class="form-control  form-control-sm" data-col-index="2" name="organ[OrganFinStrID]" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
					<?php \System\Combo::getCombo(["data"=>$_strList,"title"=>"FinStrTitle","value"=>"FinStrID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$organObj->OrganFinStrID])?>
				</select>
			</div>
			
		</div>
		<div class=" row">
			<div class="col-lg-12 form-group">
				<label class="font-12">Товч нэр *:</label>
				<input type="text" class="form-control form-control-sm" placeholder="Товч нэр" name="organ[OrganName]" value="<?=$organObj->OrganName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
			</div>
		</div>
		<div class=" row">
			<div class="col-lg-4 form-group">
				<label class="font-12">Идэвхитэй эсэх *:</label>
				<div class="kt-radio-inline">
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="organ[OrganIsActive]" <?=$organObj->isExist() && $organObj->OrganIsActive?"checked":""?> value="1"> Тийм
						<span></span>
					</label>
					<label class="kt-radio kt-radio--solid">
						<input type="radio" name="organ[OrganIsActive]" <?=!$organObj->isExist() || !$organObj->OrganIsActive?"checked":""?> value="0"> Үгүй
						<span></span>
					</label>
				</div>
			</div>
			<div class="col-lg-4 form-group">
				<label class="font-12">Аймаг, нийслэл *:</label>
				<select class="form-control  form-control-sm ajax_select" data-col-index="2" name="organ[OrganCityID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна."
					data-url="<?=RF;?>/m/ajax/select" 
					data-action="organlistdistrict"
					data-target="#district"
				>
					<?php \System\Combo::getCombo(["data"=>$_cityList,"title"=>"Address","value"=>"AddressID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$organObj->OrganCityID])?>
				</select>
			</div>
			<div class="col-lg-4 form-group">
				<label class="font-12">Сум, дүүрэг *:</label>
				<select class="form-control kt-input form-control-sm" data-col-index="2" name="organ[OrganSumID]" id="district" data-selected="<?=$organObj->OrganSumID?>"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
				</select>
			</div>
		</div>		
    </div>
</div>

<div class="modal-footer">
	<?php if($_id>0 && $count_accused<1 && $count_letter<1 && $count_sentletter<1){?>
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
