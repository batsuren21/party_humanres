<?php
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_HOLIDAY);

    $_id=isset($_POST['id'])?$_POST['id']:0;
    
    $holidayObj=\Humanres\RefHolidayClass::getInstance()->getRow(['refholiday_id'=>$_id]);
    $_str=isset(\Humanres\RefHolidayClass::$_type[$holidayObj->RefHolidayType]['title'])?\Humanres\RefHolidayClass::$_type[$holidayObj->RefHolidayType]['title']:"";
    $_date=$holidayObj->RefHolidayDateStart!=$holidayObj->RefHolidayDateEnd?$holidayObj->RefHolidayDateStart." -с ".$holidayObj->RefHolidayDateEnd:$holidayObj->RefHolidayDateStart;
    $_step=1;
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Амралтын өдөр</h5>
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
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/humanreselect/form_holiday/add"><i class="la la-edit"></i> Үндсэн мэдээлэл засах</a>
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
                            							<td><?=$holidayObj->RefHolidayTitle?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap width="1%">Хамрах хугацаа: </td>
                            							<td><?=$_date?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Төрөл: </td>
                            							<td><?=$_str?></td>
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