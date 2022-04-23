<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Албан хаагчийн төрөл садангийн байдалын бүртгэл</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/relation" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Гэр бүлийн гишүүн</th>
                                    	<th width="20%">Эцэг, эхийн нэр</th> 
                                    	<th width="10%">Өөрийн нэр</th> 
                                    	<th width="20%">Хөдөлмөр эрхлэлтийн байдал </th>
                                    	<th width="20%">Байгууллагын нэр</th>
                                    	<th width="10%">Албан тушаал</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonRelationClass::getInstance()->getRowList(['relation_personid'=>$personObj->PersonID]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_relationList=$refObj->getRowList(["_mainindex"=>"RefRelationID"],\Humanres\ReferenceClass::TBL_RELATION);
                                            $_jobList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonRelationClass::getInstance($row);
                                                
                                                $_relationObj=\Humanres\ReferenceClass::getInstance(isset($_relationList[$tmpObj->RelationRelationID])?$_relationList[$tmpObj->RelationRelationID]:[]);
                                                $_jobObj=\Humanres\ReferenceClass::getInstance(isset($_jobList[$tmpObj->RelationJobTypeID])?$_jobList[$tmpObj->RelationJobTypeID]:[]);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->RelationID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/relation">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_relationObj->RefRelationTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->RelationLastName?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->RelationFirstName?></td>
                                    	<td class="font-11"><?=$_jobObj->RefTypeTitle?></td> 
                                    	<td class="font-11"><?=$tmpObj->RelationJobOrgan;?></td>
                                    	<td class="font-11"><?=$tmpObj->RelationJobPosition;?></td>
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