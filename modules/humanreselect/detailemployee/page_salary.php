<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Цалин хөлсний бүртгэл</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/salary" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Үндсэн цалин </th> 
                                    	<th width="20%">Зэрэг, дэвийн нэмэгдэл</th> 
                                    	<th width="20%">Онцгой нөхцлийн нэмэгдэл</th> 
                                    	<th width="20%">Эрдмийн зэрэг, цолны нэмэгдэл</th> 
                                    	<th width="20%">Төрийн алба хаасан хугацааны нэмэгдэл</th> 
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonSalaryClass::getInstance()->getRowList(['salary_personid'=>$personObj->PersonID]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_SALARY_DEGREE);
                                            $_condList=$refObj->getRowList(["_mainindex"=>"RefConditionID"],\Humanres\ReferenceClass::TBL_SALARY_CONDITION);
                                            $_eduList=$refObj->getRowList(["_mainindex"=>"RefEduID"],\Humanres\ReferenceClass::TBL_SALARY_EDU);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonSalaryClass::getInstance($row);
                                                $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$tmpObj->SalaryDegreeID])?$_degreeList[$tmpObj->SalaryDegreeID]:[]);
                                                $_condObj=\Humanres\ReferenceClass::getInstance(isset($_condList[$tmpObj->SalaryConditionID])?$_condList[$tmpObj->SalaryConditionID]:[]);
                                                $_eduObj=\Humanres\ReferenceClass::getInstance(isset($_eduList[$tmpObj->SalaryEduID])?$_eduList[$tmpObj->SalaryEduID]:[]);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->SalaryID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/salary">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$tmpObj->SalaryValue?></td>
                                    	<td class="font-11"><?=$tmpObj->SalaryDegreeID>0?$_degreeObj->RefDegreeTitle:"-";?></td> 
                                    	<td class="font-11"><?=$tmpObj->SalaryConditionID>0?$_condObj->RefConditionTitle:"-";?></td> 
                                    	<td class="font-11"><?=$tmpObj->SalaryEduID>0?$_eduObj->RefEduTitle:"-";?></td> 
                                    	<td class="font-11">
                                    		<?php 
                                        		$_allmonth=$PersonWorkYearGov*12 + $PersonWorkMonthGov;
                                        		if($_allmonth>0){
                            				        foreach ($_salaryPercentList as $rs){
                            				            $refSalaryObj=\Humanres\ReferenceClass::getInstance($rs);
                            				            if($_allmonth>= $refSalaryObj->RefPercentStart && $_allmonth<= $refSalaryObj->RefPercentEnd){
                            				                echo $refSalaryObj->RefPercentTitle;
                            				            }
                            				        }
                            				    }else echo "-";
                                            ?>	
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