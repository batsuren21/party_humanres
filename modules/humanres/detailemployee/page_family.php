<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Албан хаагчийн гэр бүлийн байдалын бүртгэл</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/family" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="10%">Төрсөн он</th> 
                                    	<th width="20%">Төрсөн газар</th>
                                    	<th width="20%">Хөдөлмөр эрхлэлтийн байдал </th>
                                    	<th width="20%">Байгууллагын нэр</th>
                                    	<th width="10%">Албан тушаал</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $__list=\Humanres\PersonFamilyClass::getInstance()->getRowList(['_getparams'=>['FamilyBirthCountryID','FamilyBirthCityID','FamilyBirthDistrictID'],'family_personid'=>$personObj->PersonID]);
                                        
                                        $_countryids=isset($__list['FamilyBirthCountryID'])?array_filter($__list['FamilyBirthCountryID']):[];
                                        $_cityids=isset($__list['FamilyBirthCityID'])?array_filter($__list['FamilyBirthCityID']):[];
                                        $_districtids=isset($__list['FamilyBirthDistrictID'])?array_filter($__list['FamilyBirthDistrictID']):[];
                                        $_mainlist=isset($__list['_list'])?$__list['_list']:[];
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_relationList=$refObj->getRowList(["_mainindex"=>"RefRelationID"],\Humanres\ReferenceClass::TBL_RELATION);
                                            $_jobList=$refObj->getRowList(["_mainindex"=>"RefTypeID"],\Humanres\ReferenceClass::TBL_JOB_TYPE);
                                            
                                            $_countryList=\Office\RefCountryClass::getInstance()->getRowList(['_mainindex'=>'CountryID','country_id'=>$_countryids]);
                                            $_areaList=\Office\AreaClass::getInstance()->getRowList(['_mainindex'=>'AreaID','area_id'=>array_unique(array_merge($_cityids,$_districtids)),'orderby'=>'AreaGlobalID, AreaName']);
                                           
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonFamilyClass::getInstance($row);
                                                
                                                $_relationObj=\Humanres\ReferenceClass::getInstance(isset($_relationList[$tmpObj->FamilyRelationID])?$_relationList[$tmpObj->FamilyRelationID]:[]);
                                                $_jobObj=\Humanres\ReferenceClass::getInstance(isset($_jobList[$tmpObj->FamilyJobTypeID])?$_jobList[$tmpObj->FamilyJobTypeID]:[]);
                                                $_countryObj=\Office\RefCountryClass::getInstance(isset($_countryList[$tmpObj->FamilyBirthCountryID])?$_countryList[$tmpObj->FamilyBirthCountryID]:[]);
                                                $_cityObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->FamilyBirthCityID])?$_areaList[$tmpObj->FamilyBirthCityID]:[]);
                                                $_districtObj=\Office\AreaClass::getInstance(isset($_areaList[$tmpObj->FamilyBirthDistrictID])?$_areaList[$tmpObj->FamilyBirthDistrictID]:[]); 
                                                
                                                $date = new DateTime($tmpObj->FamilyBirthDate);
                                                $now = new DateTime();
                                                $interval = $now->diff($date);
                                                $age=$interval->y;
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->FamilyID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanres/formemployee/family">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"><?=$_relationObj->RefRelationTitle;?></td>
                                    	<td class="font-11"><?=$tmpObj->FamilyLastName?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->FamilyFirstName?></td> 
                                    	<td class="font-11" nowrap="nowrap"><?=$tmpObj->FamilyBirthDate?> (<?=$age." нас"?>)</td> 
                                    	<td class="font-11">
                                    		<?php if(!$tmpObj->FamilyBirthIsAbroad){?>
                                    		<?=$_cityObj->AreaName.", ".$_districtObj->AreaName;?>
                                    		<?php }else{?>
                                    		<?=$_countryObj->CountryName;?>
                                    		<?php }?>
                                		</td>
                                    	<td class="font-11"><?=$_jobObj->RefTypeTitle?></td> 
                                    	<td class="font-11"><?=$tmpObj->FamilyJobOrgan;?></td>
                                    	<td class="font-11"><?=$tmpObj->FamilyJobPosition;?></td>
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