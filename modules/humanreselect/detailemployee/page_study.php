<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Сургалтад хамрагдсан байдал</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/study" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="20%">Сургалтын байгууллага, сэдэв</th> 
                                    	<th width="20%">Улс</th>
                                    	<th width="10%">Элссэн огноо</th> 
                                    	<th width="10%">Дууссан огноо</th> 
                                    	<th width="20%">Хугацаа</th>
                                    	<th width="20%">Сургалтын ангилал</th>
                                    	<th width="20%">Үнэмлэх, гэрчилгээний дугаар</th>
                                    	<th width="10%">Олгосон огноо</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonStudyClass::getInstance()->getRowList(['study_personid'=>$personObj->PersonID,"orderby"=>"StudyDateStart"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_directionList=$refObj->getRowList(["_mainindex"=>"RefDirectionID"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION);
                                            $_countryList=\Office\RefCountryClass::getInstance()->getRowList(['_mainindex'=>"CountryID"]);
                                            $_listSub=$refObj->getRowList(["_mainindex"=>"RefDirSubID"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB);
                                            $_list1Sub=$refObj->getRowList(["_mainindex"=>"RefDirSub1ID"],\Humanres\ReferenceClass::TBL_STUDY_DIRECTION_SUB1);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonStudyClass::getInstance($row);
                                                
                                                $_directionObj=\Humanres\ReferenceClass::getInstance(isset($_directionList[$tmpObj->StudyDirectionID])?$_directionList[$tmpObj->StudyDirectionID]:[]);
                                                $_dirSubObj=\Humanres\ReferenceClass::getInstance(isset($_listSub[$tmpObj->StudyDirSubID])?$_listSub[$tmpObj->StudyDirSubID]:[]);
                                                $_dirSub1Obj=\Humanres\ReferenceClass::getInstance(isset($_list1Sub[$tmpObj->StudyDirSub1ID])?$_list1Sub[$tmpObj->StudyDirSub1ID]:[]);
                                                $_countryObj=\Office\RefCountryClass::getInstance(isset($_countryList[$tmpObj->StudyCountryID])?$_countryList[$tmpObj->StudyCountryID]:[]);
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap" rowspan="2">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->StudyID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/study">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$tmpObj->StudySchoolTitle;?>, <?=$tmpObj->StudyTitle;?></td>
                                    	<td class="font-11"><?=$_countryObj->CountryName?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->StudyDateStart?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->StudyDateEnd?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->StudyDay?> хоног</td> 
                                    	<td class="font-11"><?=$_directionObj->RefDirectionTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->StudyLicence?></td> 
                                    	<td class="font-11"><?=$tmpObj->StudyLicenceDate;?></td>
                                    </tr>
                                    <tr>
                                    	<td colspan="8">
                                    		<?php if($tmpObj->StudyDirSubID>0){?>
                                    		Төрөл: <?=$_dirSubObj->RefDirSubTitle;?>
                                    		<?php }?>
                                    		<?php if($tmpObj->StudyDirSub1ID>0){?>
                                    		<div>Дэд төрөл: <?=$_dirSub1Obj->RefDirSub1Title;?></div>
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