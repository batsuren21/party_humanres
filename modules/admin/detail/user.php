<?php
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_ACCESS);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_USER_PRIV);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $personObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
    $_sysList=\Office\PackageSystemListClass::getInstance()->getRowList(['orderby'=>"SystemOrder"]);
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Хэрэглэгчийн эрх</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body ">
    	<div class="row">
			<div class="col">
        		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
        			<div class="kt-portlet__head">
        				<div class="kt-portlet__head-label">
        					<h3 class="kt-portlet__head-title">
        						1. Үндсэн бүртгэл
        					</h3>
        				</div>
        				<?php if($_priv_access && $_priv_reg){?>
                    	<div class="kt-portlet__head-toolbar">
        					<div class="kt-portlet__head-actions">
        						<div class="btn-group" role="group">
                                	<button id="btnGroupDrop1" type="button" class="btn btn-brand btn-elevate  btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                		Үйлдэл
                                	</button>
                                	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/admin/form/user"><i class="la la-edit"></i> Нэвтрэх нэр засах</a>
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/admin/form/user_password"><i class="la la-edit"></i> Нууц үг шинэчлэх</a>
                                	</div>
                                </div>
							</div>
        				</div>
        				<?php }?>
        			</div>
        			<div class="kt-portlet__body">
        				<div class="kt-portlet__content">
        					<div class=" row">
                            	<div class="col-lg-6">
                    				<table class="table table-striped font-12">
                    					<tbody>
                    						<tr>
                    							<td class="color-gray" width="1%" nowrap>Нэвтрэх нэр: </td>
                    							<td><?=$personObj->PersonUserName?></td>
                    						</tr>
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
                    							<td colspan="2"><center><strong>Албан тушаал</strong></center></td>
                    						</tr>
                    						<tr>
                    							<td class="color-gray" width="1%" nowrap>Нэгж :</td>
                    							<td><?=$personObj->DepartmentFullName?></td>
                    						</tr>
                    						<tr>
                    							<td class="color-gray" width="1%" nowrap>Албан тушаал: </td>
                    							<td><?=$personObj->PositionFullName?></td>
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
                    					</tbody>
                    				</table>
                    			</div>
                    			<div class="col-lg-6">
                    				<table class="table table-striped font-12">
                    					<tbody>
                    						<tr>
                    							<td colspan="2"><center><strong>Эрх тавих системийн жагсаалт</strong></center></td>
                    						</tr>
                    						<?php 
                    						  $j=1;
                                                foreach ($_sysList as $row){
                                                    $sysObj=\Office\PackageSystemListClass::getInstance($row);
                    						?>
                    						<tr>
                    							<td class="color-gray" width="1%" nowrap><?=$j;?>.</td>
                    							<td>
                    								<a href="javascript:;" class="systemdetail" data-id="<?=$_id;?>" data-paramid='<?=$sysObj->SystemID;?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/admin/form/userpriv">
                    								<?=$sysObj->SystemName?></a>
                								</td>
                    						</tr>
                    						<?php  $j++; }?>
                    					</tbody>
                    				</table>
                    			</div>
                            </div>
        				</div>
        			</div>
        		</div>
    		</div>
		</div>
	</div>	
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>