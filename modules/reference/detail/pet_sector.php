<?php
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_ACCESS);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_REFERENCE_PETSECTORLIST);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_step=1;
    $mainObj=\Office\RefSectorClass::getInstance()->getRow(['sector_id'=>$_id]);
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Үйл ажиллагааны чиглэл</h5>
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
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/reference/form/add_pet_sector"><i class="la la-edit"></i> Үндсэн мэдээлэл засах</a>
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
                            							<td class="color-gray" nowrap width="1%">Нэр: </td>
                            							<td><?=$mainObj->SectorTitle?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Эрэмбэ: </td>
                            							<td><?=$mainObj->SectorOrder?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Идэвхитэй эсэх:</td>
                            							<td><?=$mainObj->SectorIsShow?"Тийм":"Үгүй";?></td>
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