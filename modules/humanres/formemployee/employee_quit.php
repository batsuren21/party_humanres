<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);

if($_priv_reg){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $personObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
    $_icon="flaticon2-edit";
    $_title="Албан тушаалаас чөлөөлөх";
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_quitList=$refObj->getRowList(["orderby"=>"RefQuitOrder"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT);
    
    $_count_letter=\Office\LetterClass::getInstance()->getRowCount(['letter_statusid'=>\Office\LetterClass::LETTER_CONTROL,'letter_lastshiftpersonid'=>$personObj->PersonID]);
    $_count_petition=\Office\PetitionClass::getInstance()->getRowCount(['petition_statusid'=>\Office\PetitionClass::PETITION_CONTROL,'petition_lastshiftpersonid'=>$personObj->PersonID]);
    $_count_felony=\Office\FelonyClass::getInstance()->getRowCount(['felony_statusid'=>\Office\FelonyClass::FELONY_CONTROL,'felony_mainpersonid'=>$personObj->PersonID]);
    $_is_can=$_count_letter<1 && $_count_petition<1 && $_count_felony<1?1:0;
    
    $jobCount=\Humanres\PersonJobClass::getInstance()->getRowCount([
        "job_personid"=>$personObj->PersonID,
        "job_isnow"=>1,
    ]);
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/humanres/editemployee" enctype="multipart/form-data">
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
                    <strong><?=$personObj->DepartmentFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Албан тушаал: </label>
                <div class="col-4 pt-2">
                    <strong><?=$personObj->PositionFullName?></strong>
                </div>
            </div>
            <div class="form-group row mb-0">
                <label class="col-3 col-form-label font-12">Эцэг эхийн нэр, өөрийн нэр: </label>
                <div class="col-4 pt-2">
                    <strong><?=$personObj->PersonLFName?></strong>
                </div>
            </div>
        </div>
        <h5 class="kt-section__title">2. Чөлөөлсөн байдал:</h5>
        <div class="kt-section__body">
        	<?php if($_count_letter<1 && $_count_petition<1 && $_count_felony<1 && $jobCount<1){?>
            <div class=" row">
        		<div class="col-lg-6 form-group">
    				<label class="font-12">Хөдөлгөөний төрөл *:</label>
    				<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="employee[EmployeeQuitID]" 
    					data-url="<?=RF;?>/m/ajax/select" 
    					data-action="ref_quit"
    					data-val_default="<?=\System\Combo::SELECT_SINGLE;?>"
    					data-target="#formquitsub" data-rule-required="true" data-msg-required="Сонгоогүй байна.">
    					<?php \System\Combo::getCombo(["data"=>$_quitList,"title"=>"RefQuitTitle","value"=>"RefQuitID","flag"=>\System\Combo::SELECT_SINGLE,'selected'=>$personObj->EmployeeQuitID])?>
    				</select>
    			</div>
    			<div class="col-lg-6 form-group">
    				<label class="font-12">Чөлөөлсөн байдал *:</label>
    				<select class="form-control form-control-sm" id="formquitsub" name="employee[EmployeeQuitSubID]" data-rule-required="true" data-msg-required="Сонгоогүй байна." data-selected="<?=$personObj->EmployeeQuitSubID?>">
    				</select>
    			</div>
        		
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Чөлөөлсөн огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker"  name="employee[EmployeeQuitDate]" placeholder="Өдөр сонгох" value="<?=$personObj->EmployeeQuitDate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
        		<div class="col-lg-4 form-group">
            		<label class="font-12">Тушаалын огноо *:</label>
            		<div class="input-group date">
            			<input type="text" class="form-control form-control-sm  datepicker"  name="employee[EmployeeQuitOrderDate]" placeholder="Өдөр сонгох" value="<?=$personObj->EmployeeQuitOrderDate?>" data-rule-required="true" data-msg-required="Хоосон байна."/>
            			<div class="input-group-append">
            				<span class="input-group-text">
            					<i class="la la-calendar-check-o"></i>
            				</span>
            			</div>
            		</div>
            	</div>
            	<div class="col-lg-4 form-group">
        			<label class="font-12">Тушаалын дугаар *:</label>
        			<input type="text" class="form-control form-control-sm resfield" placeholder="Тушаалын дугаар" name="employee[EmployeeQuitOrderNo]" value="<?=$personObj->EmployeeQuitOrderNo?>" data-rule-required="true" data-msg-required="Хоосон байна.">
        		</div>
        	</div>
        	<?php }else{?>
        	<div class=" row">
        		<div class="col-lg-6 offset-lg-3">
        			<div class="alert alert-outline-danger" role="alert">
						<div class="alert-icon"><i class="flaticon-warning"></i></div>
						<div class="alert-text font-12">
							Албан хаагчид шийдвэрлээгүй үлдсэн <strong>ирсэн бичиг, өргөдөл гомдол болон бусад мэдээллүүд</strong> байгаа учир чөлөөлөх боломжгүй.
						</div>
					</div>
        			<table class="table table-striped font-12">
					<tbody>
						<tr>
							<td class="color-gray" width="1%" nowrap>Шийдвэрлээгүй ирсэн бичиг</td>
							<td><?=$_count_letter?></td>
						</tr>
						<tr>
							<td class="color-gray" width="1%" nowrap>Шийдвэрлээгүй өргөдөл, гомдол</td>
							<td><?=$_count_petition?></td>
						</tr>
						<tr>
							<td class="color-gray" width="1%" nowrap>Шийдвэрлээгүй хэрэг</td>
							<td><?=$_count_felony?></td>
						</tr>
						<?php if($jobCount>0){?>
						<tr>
							<td class="color-gray" width="1%" nowrap>Хөдөлмөр эрхлэлт</td>
							<td>Хөдөлмөр эрхлэлтэд одоо ажиллаж байгаа төлөвтэй бичлэг байгаа тул чөлөөлөх боломжгүй</td>
						</tr>
						<?php }?>
					</tbody>
					</table>
        		</div>
        	</div>
        	<?php }?>
        </div>
    </div>
</div>
<div class="modal-footer">
	<?php if($personObj->EmployeeQuitID!=""){?>
    <button type="button" class="btn btn-danger mr-auto" id="delete" data-url="<?=RF;?>/process/humanres/removeemployeequit">Чөлөөлсөн байдал устгах</button>
 	<?php }?>
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
    <?php if($_is_can){?>
    <button type="submit" class="btn btn-success ">Хадгалах</button>
    <?php }?>
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