<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Сахилгын шийтгэл оногдуулсан бүртгэл</h3>
				</div>
				<?php if($_priv_reg){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/punishment" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Сахилгын шийтгэлийн төрөл </th>
                                    	<th width="20%">Тушаал, шийдвэрийн дугаар</th> 
                                    	<th width="10%">Тушаал, шийдвэрийн огноо</th> 
                                    	<th width="10%">Үндэслэл</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonPunishmentClass::getInstance()->getRowList(['punishment_personid'=>$personObj->PersonID,"orderby"=>"PunishmentOrderDate"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_typeList=$refObj->getRowList(["_mainindex"=>"RefPunishmentID"],\Humanres\ReferenceClass::TBL_PUNISHMENT);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonPunishmentClass::getInstance($row);
                                                $_typeObj=\Humanres\ReferenceClass::getInstance(isset($_typeList[$tmpObj->PunishmentRefID])?$_typeList[$tmpObj->PunishmentRefID]:[]);      
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->PunishmentID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/punishment">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_typeObj->RefPunishmentTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->PunishmentOrder?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->PunishmentOrderDate?></td> 
                                    	<td class="font-11">
                                    		<?=$tmpObj->PunishmentReason?>
                                    		<?php if($_typeObj->RefPunishmentID==2){?>
                                    		<div>Хугацаа: <?=$tmpObj->PunishmentTime?> сар, Хувь: <?=$tmpObj->PunishmentPercent?>%</div>
                                    		<?php }?>
                                		</td> 
                                    	
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