<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Боловсролын байдал</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/education" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Сургуулийн төрөл </th>
                                    	<th width="20%">Сургуулийн нэр</th> 
                                    	<th width="10%">Элссэн огноо</th> 
                                    	<th width="10%">Төгссөн огноо</th> 
                                    	<th width="20%">Боловсролын түвшин</th>
                                    	<th width="20%">Мэргэжил</th>
                                    	<th width="20%">Боловсролын зэрэг</th>
                                    	<th width="10%">Гэрчилгээ, дипломны дугаар</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonEducationClass::getInstance()->getRowList(['education_personid'=>$personObj->PersonID,"orderby"=>"EducationDateStart"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_levelList=$refObj->getRowList(["_mainindex"=>"RefLevelID"],\Humanres\ReferenceClass::TBL_EDUCATION_LEVEL);
                                            $_degreeList=$refObj->getRowList(["_mainindex"=>"RefDegreeID"],\Humanres\ReferenceClass::TBL_EDUCATION_DEGREE);
                                            $_schoolList=$refObj->getRowList(["_mainindex"=>"RefSchoolID"],\Humanres\ReferenceClass::TBL_EDUCATION_SCHOOL);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonEducationClass::getInstance($row);
                                                
                                                $_levelObj=\Humanres\ReferenceClass::getInstance(isset($_levelList[$tmpObj->EducationLevelID])?$_levelList[$tmpObj->EducationLevelID]:[]);
                                                $_degreeObj=\Humanres\ReferenceClass::getInstance(isset($_degreeList[$tmpObj->EducationDegreeID])?$_degreeList[$tmpObj->EducationDegreeID]:[]);
                                                $_schoolObj=\Humanres\ReferenceClass::getInstance(isset($_schoolList[$tmpObj->EducationSchoolID])?$_schoolList[$tmpObj->EducationSchoolID]:[]);
                                                
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->EducationID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/education">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_schoolObj->RefSchoolTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->EducationSchoolTitle?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->EducationDateStart?></td> 
                                    	<td class="font-11"><?=!$tmpObj->EducationIsNow?$tmpObj->EducationDateEnd:"Одоо сурч байгаа";?></td> 
                                    	<td class="font-11"><?=$_levelObj->RefLevelTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->EducationProfession?></td> 
                                    	<td class="font-11"><?=$_degreeObj->RefDegreeTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->EducationLicence;?></td>
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