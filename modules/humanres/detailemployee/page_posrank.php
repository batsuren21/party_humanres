<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Албан тушаалын зэрэг, дэв</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/posrank" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="30%">Зэрэг, дэв</th>
                                    	<th width="10%">Шийдвэрийн огноо</th> 
                                    	<th width="30%">Шийдвэрийн дугаар</th> 
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonPosRankClass::getInstance()->getRowList(['posrank_personid'=>$personObj->PersonID,"orderby"=>"PosDate"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_posrankList=$refObj->getRowList(["_mainindex"=>"RefRankID"],\Humanres\ReferenceClass::TBL_POS_RANK);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonPosRankClass::getInstance($row);
                                                $_posrankObj=\Humanres\ReferenceClass::getInstance(isset($_posrankList[$tmpObj->PosRankID])?$_posrankList[$tmpObj->PosRankID]:[]);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->PosID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/posrank">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_posrankObj->RefRankTitle?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->PosDate?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->PosNumber?></td> 
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