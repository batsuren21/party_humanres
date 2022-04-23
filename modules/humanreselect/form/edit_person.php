<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_icon=$_id>0?"flaticon2-edit":"flaticon2-add-1";
    $_title=$_id>0?"Албан хаагчийн мэдээлэл засварлах":"Албан хаагчийн мэдээлэл бүртгэх";
    
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_id]);
    
    $_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
        
    if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){
        $refObj=\Humanres\ReferenceClass::getInstance();
         
        $_eduLevelList=$refObj->getRowList(["orderby"=>"RefLevelOrder"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
        $_ethnicList=$refObj->getRowList(["orderby"=>"RefEthnicOrder"],\Humanres\ReferenceClass::TBL_ETHNICITY);
        
        $_areaList=\Office\AreaClass::getInstance()->getRowList(['area_parentid'=>1,'orderby'=>'AreaGlobalID, AreaName']);
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
    	<div class=" row">
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Регистрийн дугаар *:</label>
    			<input type="text" class="form-control form-control-sm font-12" placeholder="" name="person[PersonRegisterNumber]" value="<?=$personObj->PersonRegisterNumber;?>" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    		</div>
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Ургийн овог *:</label>
    			<input type="text" class="form-control form-control-sm font-12 " placeholder="Ургийн овог" name="person[PersonMiddleName]" value="<?=$personObj->PersonMiddleName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
    		</div>
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Эцэг, эхийн нэр *:</label>
    			<input type="text" class="form-control form-control-sm font-12 " placeholder="Эцэг, эхийн нэр" name="person[PersonLastName]" value="<?=$personObj->PersonLastName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
    		</div>
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Өөрийн нэр *:</label>
    			<input type="text" class="form-control form-control-sm font-12 " placeholder="Өөрийн нэр" name="person[PersonFirstName]" value="<?=$personObj->PersonFirstName?>" data-rule-required="true" data-msg-required="Хоосон байна.">
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-lg-3 form-group">
        		<label class="font-12">Төрсөн огноо *:</label>
        		<div class="input-group date">
        			<input type="text" class="form-control form-control-sm  datepicker resfield"  name="person[PersonBirthDate]" placeholder="Өдөр сонгох" value="<?=$personObj->PersonBirthDate;?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
        			<div class="input-group-append">
        				<span class="input-group-text">
        					<i class="la la-calendar-check-o"></i>
        				</span>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-3 form-group">
        		<label class="font-12">Хүйс *:</label>
        		<select class="form-control form-control-sm resfield" name="person[PersonGender]" data-rule-required="true" data-msg-required="Хоосон байна.">
        			<?php \System\Combo::getCombo(["data"=>\Office\CitizenClass::$gender,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$personObj->PersonGender])?>
        		</select>
        	</div>
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Боловсрол *:</label>
    			<select class="form-control  form-control-sm resfield" data-col-index="2" name="person[PersonEducationLevelID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    				<?php \System\Combo::getCombo(["data"=>$_eduLevelList,"title"=>"RefLevelTitle","value"=>"RefLevelID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$personObj->PersonEducationLevelID])?>
    			</select>
    		</div>
    		<div class="col-lg-3 form-group">
    			<label class="font-12">Яс үндэс *:</label>
    			<select class="form-control  form-control-sm resfield" data-col-index="2" name="person[PersonEthnicID]"  data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    				<?php \System\Combo::getCombo(["data"=>$_ethnicList,"title"=>"RefEthnicTitle","value"=>"RefEthnicID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>$personObj->PersonEthnicID])?>
    			</select>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-lg-8">
            	<div class="kt-section kt-section--last">
        			<div class="kt-section__body">
        				<h4 class="kt-section__title kt-section__title-lg ">Төрсөн газар:</h4>
                        <div class="row">
                			<div class="col-lg-6 form-group">
                				<label class="font-12">Аймаг, нийслэл*:</label>
                				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="person[PersonBirthCityID]" 
                        			data-url="<?=RF;?>/m/ajax/select" 
                        			data-action="area"
                        			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
                        			data-target="#formareadistrictid" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                        			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->PersonBirthCityID])?>
                        		</select>
                			</div>
                		    <div class="form-group col-lg-6">
                		    	<label class="font-12"> Сум, дүүрэг:</label>
                				<select class="form-control form-control-sm" id="formareadistrictid" name="person[PersonBirthDistrictID]" data-selected="<?=$personObj->PersonBirthDistrictID;?>" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                    			</select>
                            </div>
                            
                		</div>
            		</div>
        		</div>
    			<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg mt-1 mb-1"></div>
                <div class="kt-section kt-section--last">
        			<div class="kt-section__body">
        				<h4 class="kt-section__title kt-section__title-lg ">Үндсэн захиргаа:</h4>
                        <div class="row">
                			<div class="col-lg-6 form-group">
                				<label class="font-12">Аймаг, нийслэл*:</label>
                				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="person[PersonBasicCityID]" 
                        			data-url="<?=RF;?>/m/ajax/select" 
                        			data-action="area"
                        			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
                        			data-target="#formareadistrictid2" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                        			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->PersonBasicCityID])?>
                        		</select>
                			</div>
                		    <div class="form-group col-lg-6">
                		    	<label class="font-12"> Сум, дүүрэг:</label>
                				<select class="form-control form-control-sm" id="formareadistrictid2" name="person[PersonBasicDistrictID]" data-selected="<?=$personObj->PersonBasicDistrictID;?>" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                    			</select>
                            </div>
                            
                		</div>
            		</div>
        		</div>
    		</div>
    		<div class="col-lg-4">
    			<div class="form-group row">
					<label class="col-xl-3 col-lg-3 col-form-label">Цээж зураг</label>
					<div class="col-lg-9 col-xl-6">
						<div class="kt-avatar kt-avatar--outline" id="kt_user_avatar">
							<div class="kt-avatar__holder" style="background-image: url(<?=$personObj->getImage()?>)"></div>
							<label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
								<i class="fa fa-pen"></i>
								<input type="file" name="filesource" accept="image/*">
							</label>
							<span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
								<i class="fa fa-times"></i>
							</span>
						</div>
						<?php if($personObj->PersonImageSource!=""){?>
                        <label class="kt-checkbox">
							<input type="checkbox" name="isdelimg" value="1"> Өмнөх файлыг устгах
							<span></span>
						</label>
                        <?php }?>
					</div>
				</div>
    		</div>
		</div>
		<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg mt-1 mb-1"></div>
        <div class="kt-section kt-section--last">
			<div class="kt-section__body">
				<h4 class="kt-section__title kt-section__title-lg ">Оршин суугаа хаяг:</h4>
                <div class="row">
        			<div class="col-lg-3 form-group">
        				<label class="font-12">Аймаг, нийслэл*:</label>
        				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="person[PersonAddressCityID]" 
                			data-url="<?=RF;?>/m/ajax/select" 
                			data-action="area"
                			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
                			data-target="#formareadistrictid3" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
                			<?php \System\Combo::getCombo(["data"=>$_areaList,"title"=>"AreaName","value"=>"AreaID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->PersonAddressCityID])?>
                		</select>
        			</div>
        		    <div class="form-group col-lg-3">
        		    	<label class="font-12"> Сум, дүүрэг:</label>
        				<select class="form-control kt-input form-control-sm ajax_select" id="formareadistrictid3" name="person[PersonAddressDistrictID]" data-selected="<?=$personObj->PersonAddressDistrictID;?>"
        					data-url="<?=RF;?>/m/ajax/select" 
                			data-action="area"
                			data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
                			data-target="#formareakhorooid3" data-rule-required="true" data-msg-required="Сонгоогүй байна."
        				>
            			</select>
                    </div>
                    <div class="form-group col-lg-3">
        		    	<label class="font-12">Баг, хороо:</label>
        				<select class="form-control form-control-sm " id="formareakhorooid3" name="person[PersonAddressHorooID]" data-selected="<?=$personObj->PersonAddressHorooID;?>" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
            			</select>
                    </div>
        		
        			<div class="col-lg-3 form-group">
        				<label class="font-12">Тоот:</label>
        				<input type="text" class="form-control form-control-sm font-12" placeholder="" name="person[PersonAddress]" value="<?=$personObj->PersonAddress?>" data-rule-required="true" data-msg-required="Хоосон байна.">
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