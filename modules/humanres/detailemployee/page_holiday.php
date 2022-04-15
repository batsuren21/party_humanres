<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
?>
<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Ээлжийн амралт олгох тухай мэдэгдэл</h3>
				</div>
				<?php if($_priv_reg ){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/holiday" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="10%">Мэдэгдлийн дугаар</th>
                                    	<th width="10%">Эхлэх огноо</th> 
                                    	<th width="10%">Дуусах огноо</th> 
                                    	<th width="10%">Үргэлжлэх хоног</th> 
                                    	<th width="10%">Бүртгэсэн огноо</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonHolidayClass::getInstance()->getRowList(['holiday_personid'=>$personObj->PersonID,"orderby"=>"HolidayID desc"]);
                                        
                                        if(count($_mainlist)>0){
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonHolidayClass::getInstance($row);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap" rowspan="2">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->HolidayID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/holiday">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    		<a href="javascript:;" data-target="#detailPrintModalHumanres" 
                                    			data-toggle="modal" 
                                    			data-url="<?=RF?>/m/humanres/list/json_holiday?id=<?=$tmpObj->HolidayID?>" 
                                    			data-print="person_holiday"
                                    			data-id="<?=$tmpObj->HolidayID?>" 
                                    			class="btn btn-outline-success btn-sm "><i class="flaticon2-print"></i></a>
                                    	</td>
                                    	<td class="font-11"><?=$tmpObj->HolidayRegisterNumberFull;?></td>
                                    	<td class="font-11"><?=$tmpObj->HolidayDateStart?></td> 
                                    	<td class="font-11"><?=$tmpObj->HolidayDateEnd?></td> 
                                    	<td class="font-11"><?=$tmpObj->HolidayDays?></td> 
                                    	<td class="font-11"><?=$tmpObj->HolidayRegisterDate;?></td>
                                    </tr>
                                    <tr>
                                    	<td class="font-11" colspan="3">Ажилласан огноо: <?=$tmpObj->HolidayJobDateStart;?> - <?=$tmpObj->HolidayJobDateEnd;?></td>
                                    	<td class="font-11" colspan="2">Ажилд орсон жилдээ амарсан эсэх: <?=$tmpObj->HolidayIsFirstYear?"Тийм":"Үгүй";?></td>
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