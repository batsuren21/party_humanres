<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
?>
<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Ээлжийн амралтын тооцоо</h3>
				</div>
				<?php if($_priv_reg ){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/bill" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
							<i class="flaticon2-add-1"></i> Бүртгэх
						</a>
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
                    				<table class="table table-bordered table-striped table-hover font-12">
                                    <thead>
                                    <tr>
                                    	<th width="1%" >#</th> 
                                    	<th width="10%">Тооцооны дугаар</th>
                                    	<th width="10%">Ажиллаж дуусах огноо</th> 
                                    	<th width="10%">Ажилласан сар</th> 
                                    	<th width="10%">Ногдох ажлын өдөр</th> 
                                    	<th width="10%">Ногдох мөнгөн дүн</th> 
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonHolidayBillClass::getInstance()->getRowList(['bill_personid'=>$personObj->PersonID,"orderby"=>"BillID desc"]);
                                        
                                        if(count($_mainlist)>0){
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonHolidayBillClass::getInstance($row);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap" rowspan="2">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->BillID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/bill">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?>
                                    		<a href="javascript:;" data-target="#detailPrintModalHumanres" 
                                    			data-toggle="modal" 
                                    			data-url="<?=RF?>/m/humanres/list/json_bill?id=<?=$tmpObj->BillID?>" 
                                    			data-print="person_bill"
                                    			data-id="<?=$tmpObj->BillID?>" 
                                    			class="btn btn-outline-success btn-sm "><i class="flaticon2-print"></i></a>
                                    	</td>
                                    	<td class="font-11"><?=$tmpObj->BillRegisterNumberFull;?></td>
                                    	<td class="font-11"><?=$tmpObj->BillJobDate?></td> 
                                    	<td class="font-11"><?=$tmpObj->BillTime?></td> 
                                    	<td class="font-11"><?=$tmpObj->BillHolidayDay?></td> 
                                    	<td class="font-11"><?=$tmpObj->BillValue;?></td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2" class="font-11">Биеэр эдэлсэн хугацаа: <?=$tmpObj->BillHolidayDay1?></td>
                                    	<td colspan="2" class="font-11">Биеэр эдлээгүй хугацаа: <?=$tmpObj->BillHolidayDay2?></td>
                                    	<td class="font-11">Бүртгэсэн: <?=$tmpObj->BillRegisterDate?></td>
                                    </tr>
                                    <?php }}else{?>
                                    <tr>
                                    	<td colspan="6" class="text-center"><i>Бүртгэл хийгдээгүй</i></td>
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
	</div>
</div>