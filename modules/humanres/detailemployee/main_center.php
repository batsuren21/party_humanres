<?php 
    $now=new \DateTime(); 
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_eduLevelObj=$refObj->getRowRef(["ref_id"=>$personObj->PersonEducationLevelID],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
    $_ethicObj=$refObj->getRowRef(["ref_id"=>$personObj->PersonEthnicID],\Humanres\ReferenceClass::TBL_ETHNICITY);
    $_startObj=$refObj->getRowRef(["ref_id"=>$personObj->EmployeeStartID],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
    
    $_postypeObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionTypeID],\Humanres\ReferenceClass::TBL_POSITION_TYPE);
    $_posclassObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionClassID],\Humanres\ReferenceClass::TBL_POSITION_CLASS);
    $_posdegreeObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionDegreeID],\Humanres\ReferenceClass::TBL_POSITION_DEGREE);
    $_posrankObj=$refObj->getRowRef(["ref_id"=>$personObj->PositionRankID],\Humanres\ReferenceClass::TBL_POSITION_RANK);
?>
<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						1. Үндсэн бүртгэл
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				
					<div class="kt-portlet__head-actions">
						
						<div class="btn-group" role="group">
                        	<button id="btnGroupDrop1" type="button" class="btn btn-brand btn-elevate  btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        		Үйлдэл
                        	</button>
                        	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$personObj->PersonID?>" data-url="<?=RF;?>/m/humanres/form/edit_person"><i class="la la-edit"></i> Хувь хүний мэдээлэл засах</a>
                        		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-url="<?=RF;?>/m/humanres/form/edit_person_contact" data-id="<?=$personObj->PersonID?>"><i class="fa fa-address-book"></i> Холбоо барих мэдээлэл засах</a>
                    			<?php if($_priv_reg){?>
                    			<?php if($personObj->EmployeeIsActive){?>
                    			<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/employee_start_edit" data-id="<?=$personObj->PersonID?>" data-paramid="<?=$personObj->PersonEmployeeID?>"><i class="la la-edit"></i> Үүрийн бүртгэл засах</a>
                    			<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/employee_quit" data-id="<?=$personObj->PersonID?>" data-paramid="<?=$personObj->PersonEmployeeID?>"><i class="la la-edit"></i> Үүрээс хасах</a>
                    		
                    			<?php }else{?>
                    			<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/employee_quit" data-id="<?=$personObj->PersonID?>" data-paramid="<?=$personObj->PersonEmployeeID?>"><i class="la la-edit"></i> Үүрээс хассан мэдээлэл засах</a>
                    			<?php }?>
                    			<?php }?>
                    		</div>
                        </div>
					</div>
				<?php }?>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="kt-portlet__content">
					<div class=" row">
                    	<div class="col-lg-6">
            				<table class="table table-striped font-12">
            					<tbody>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Регистрийн дугаар :</td>
            							<td><?=$personObj->PersonRegisterNumber?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Ургийн овог: </td>
            							<td><?=$personObj->PersonMiddleName?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Эцэг, эхийн нэр: </td>
            							<td><?=$personObj->PersonLastName?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Өөрийн нэр: </td>
            							<td><?=$personObj->PersonFirstName?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Төрсөн огноо: </td>
            							<td><?=$personObj->PersonBirthDate?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Хүйс: </td>
            							<td><?=$personObj->PersonGender?"Эрэгтэй":"Эмэгтэй"?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Төрсөн газар: </td>
            							<td><?=$personObj->PersonBirthPlace?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Гэрийн хаяг: </td>
            							<td><?=$personObj->PersonAddressFull?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Боловсрол: </td>
            							<td><?=$_eduLevelObj->RefLevelTitle;?></td>
            						</tr>
            						<tr>
            							<td colspan="2"><center><strong>Холбоо барих мэдээлэл</strong></center></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Гар утас: </td>
            							<td><?=$personObj->PersonContactMobilePhone;?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Ажлын утас: </td>
            							<td><?=$personObj->PersonContactWorkPhone;?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>И-мэйл хаяг: </td>
            							<td><?=$personObj->PersonContactEmail;?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" >Яаралтай үед холбоо барих хүний нэр, утас: </td>
            							<td><?=$personObj->PersonContactEmergencyName;?>, <?=$personObj->PersonContactEmergencyPhone;?></td>
            						</tr>
            						
            					</tbody>
            				</table>
            			</div>
            			<div class="col-lg-6">
            				<table class="table table-striped font-12">
            					<tbody>
            						<tr>
            							<td colspan="2"><center><strong>Анхан шатны намын бүртгэл</strong></center></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Нэгж :</td>
            							<td><?=$personObj->DepartmentFullName?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Үүр: </td>
            							<td><?=$personObj->PositionFullName?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Бүртгэсэн байдал: </td>
            							<td><?=$_startObj->RefStartTitle?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Шилжиж ирсэн, бүртгэсэн огноо: </td>
            							<td><?=$personObj->EmployeeStartDate?></td>
            						</tr>
            						
            						<?php 
            						      if($personObj->EmployeeQuitID>0){
            						          $_quitObj=$refObj->getRowRef(["ref_id"=>$personObj->EmployeeQuitID],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT);
            						          $_quitSubObj=$refObj->getRowRef(["ref_id"=>$personObj->EmployeeQuitSubID],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT_SUB);
        						    ?>
            						<tr>
            							<td colspan="2"><center><strong>Үүрээс хассан байдал</strong></center></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" >Хассан байдал: </td>
            							<td><?=$_quitObj->RefQuitTitle?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Хассан огноо: </td>
            							<td><?=$personObj->EmployeeQuitDate?></td>
            						</tr>
            						
            						<?php }?>
            						
    
            					</tbody>
            				</table>
            			</div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
