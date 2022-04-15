<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Томилолтын бүртгэл</h3>
				</div>
				<?php if($_priv_reg){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/trip" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Томилолтын төрөл</th> 
                                    	<th width="10%">Эхлэх огноо</th> 
                                    	<th width="10%">Дуусах огноо</th> 
                                    	<th width="20%">Хугацаа</th>
                                    	<th width="20%">Тушаал, шийдвэрийн дугаар</th>
                                    	<th width="10%">Тушаал, шийдвэрийн огноо</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonTripClass::getInstance()->getRowList(['trip_personid'=>$personObj->PersonID,"orderby"=>"TripDateStart"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_tripList=$refObj->getRowList(["_mainindex"=>"RefTripID"],\Humanres\ReferenceClass::TBL_TRIP);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonTripClass::getInstance($row);
                                                
                                                $_tripObj=\Humanres\ReferenceClass::getInstance(isset($_tripList[$tmpObj->TripRefID])?$_tripList[$tmpObj->TripRefID]:[]);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->TripID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/trip">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_tripObj->RefTripTitle?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->TripDateStart?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->TripDateEnd?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->TripDay?> хоног</td> 
                                    	<td class="font-11"><?=$tmpObj->TripOrder?></td> 
                                    	<td class="font-11"><?=$tmpObj->TripOrderDate;?></td>
                                    </tr>
                                    <?php }}else{?>
                                    <tr>
                                    	<td colspan="9" class="text-center"><i>Бүртгэл хийгдээгүй</i></td>
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