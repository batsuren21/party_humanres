<?php
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_ACCESS);
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_step=1;
    $departmentObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$_id]);
    $parentDepObj=\Humanres\DepartmentClass::getInstance()->getRow(['department_id'=>$departmentObj->DepartmentParentID]);
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_classObj=$refObj->getRowRef(["ref_id"=>$departmentObj->DepartmentClassID],\Humanres\ReferenceClass::TBL_DEPARTMENT_CLASS);
    
    $_areaList=\Office\AreaClass::getInstance()->getRowList(["_mainindex"=>"AreaID",'area_parentid'=>1,'orderby'=>'AreaGlobalID, AreaName']);
    
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Нэгж</h5>
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
                                		<a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#regModal" data-id="<?=$_id?>" data-url="<?=RF;?>/m/department/form/add_department"><i class="la la-edit"></i> Үндсэн мэдээлэл засах</a>
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
                            							<td class="color-gray" nowrap width="1%">Товч нэр: </td>
                            							<td><?=$departmentObj->DepartmentName?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Нэр: </td>
                            							<td><?=$departmentObj->DepartmentFullName?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray" nowrap>Ангилал: </td>
                            							<td><?=$_classObj->RefClassTitle;?></td>
                            						</tr>
                            						<tr>
                            							<td class="color-gray">Эрэмбэ:</td>
                            							<td><?=$departmentObj->DepartmentOrder;?></td>
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