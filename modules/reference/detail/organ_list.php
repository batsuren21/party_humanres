<?php
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_ACCESS);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_ORGANLIST);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_step=1;
    $organObj=\Office\OrganListClass::getInstance()->getRow(['organ_id'=>$_id]);
    $genObj=\Office\OrganFinanceGeneralClass::getInstance()->getRow(['fingen_id'=>$organObj->OrganFinGenID]);
    $strObj=\Office\OrganFinanceStraightClass::getInstance()->getRow(['finstr_id'=>$organObj->OrganFinStrID]);
    $addrObj=\Office\OrganAddressClass::getInstance()->getRow(['address_id'=>$organObj->OrganCityID]);
    $addr1Obj=\Office\OrganAddressClass::getInstance()->getRow(['address_id'=>$organObj->OrganSumID]);
    
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Байгууллага</h5>
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
        						<?=$_step++;?>. Үндсэн бүртгэл
        					</h3>
        				</div>
        				<?php if($_priv_reg){?>
                    	<div class="kt-portlet__head-toolbar">
        					<div class="kt-portlet__head-actions">
        						<div class="btn-group" role="group">
                                	<button id="btnGroupDrop1" type="button" class="btn btn-brand btn-elevate  btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                		Үйлдэл
                                	</button>
                                	<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/reference/form/add_organ"><i class="la la-edit"></i> Үндсэн мэдээлэл засах</a>
                                	</div>
                                </div>
							</div>
        				</div>
        				<?php }?>
        			</div>
        			<div class="kt-portlet__body">
        				<div class="kt-portlet__content">
        					<div class=" row">
                            	<div class="col-lg-12">
                            		<div class="row">
                            			<div class="col">
                            				<table class="table table-striped font-12">
                            					<tbody>
                            						<tr>
                            							<td class="color-gray" nowrap width="1%">Байгууллагын нэр: </td>
                            							<td><?=$organObj->OrganName?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>ТЕЗ: </td>
                            							<td><?=$genObj->FinGenTitle?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>ТШЗ:</td>
                            							<td><?=$strObj->FinStrTitle;?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Аймаг, нийслэл: </td>
                            							<td><?=$addrObj->Address;?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Сум, дүүрэг: </td>
                            							<td><?=$addr1Obj->Address;?></td>
                            						</tr>                   						
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
	</div>	
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>