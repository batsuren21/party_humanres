<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Шагналын байдал бүртгэл</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/award" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Шагналын төрөл</th> 
                                    	<th width="20%">Шагналын нэр</th>
                                    	<th width="10%">Шийдвэрийн огноо</th> 
                                    	<th width="10%">Шийдвэрийн дугаар</th> 
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonAwardClass::getInstance()->getRowList(['award_personid'=>$personObj->PersonID,"orderby"=>"AwardDate"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_awardList=$refObj->getRowList(["_mainindex"=>"RefAwardID"],\Humanres\ReferenceClass::TBL_AWARD);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonAwardClass::getInstance($row);
                                                $_awardObj=\Humanres\ReferenceClass::getInstance(isset($_awardList[$tmpObj->AwardRefID])?$_awardList[$tmpObj->AwardRefID]:[]);
                                                $_awardSubObj=\Humanres\ReferenceClass::getInstance(isset($_awardList[$tmpObj->AwardRefSubID])?$_awardList[$tmpObj->AwardRefSubID]:[]);
                                               
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->AwardID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/award">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_awardObj->RefAwardTitle;?></td>
                                    	<td class="font-11">
                                    		<?=$_awardSubObj->RefAwardID>0?$_awardSubObj->RefAwardTitle:"";?> <?=$tmpObj->AwardTitle?>
                                		</td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->AwardDate?></td> 
                                    	<td class="font-11"><?=$tmpObj->AwardLicence?></td> 
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