<?php 
    $now=new \DateTime(); 
    $refObj=\Humanres\ReferenceClass::getInstance();
    
    $_soldierObj=$refObj->getRowRef(["ref_id"=>$personObj->PersonSoldierID],\Humanres\ReferenceClass::TBL_SOLDIER);
?>
<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Цэргийн жинхэнэ алба хаасан эсэх 
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/form/edit_person_soldier" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
							<?php if($personObj->PersonSoldierUpdateDate==""){?>
							<i class="flaticon2-add-1"></i> Бүртгэх
							<?php }else{?>
							<i class="flaticon2-add-1"></i> Засах
							<?php }?>
						</a>
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
            							<td class="color-gray" width="1%" nowrap>Цэргийн жинхэнэ алба хаасан эсэх :</td>
            							<td><?=$personObj->PersonSoldierUpdateDate==""?"-":($personObj->PersonIsSoldiering?"Тийм":"Үгүй");?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Үнэмлэхний дугаар: </td>
            							<td><?=$personObj->PersonSoldierUpdateDate==""?"-":$personObj->PersonSoldierPassNo;?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Цэргийн жинхэнэ алба хаасан он: </td>
            							<td><?=$personObj->PersonSoldierUpdateDate==""?"-":$personObj->PersonSoldierYear;?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Цэргийн жинхэнэ алба хаасан байдал: </td>
            							<td><?=$personObj->PersonSoldierUpdateDate==""?"-":$_soldierObj->RefSoldierTitle?></td>
            						</tr>
            						<tr>
            							<td class="color-gray" width="1%" nowrap>Тайлбар: </td>
            							<td><?=$personObj->PersonSoldierUpdateDate==""?"-":$personObj->PersonSoldierDescr;?></td>
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
