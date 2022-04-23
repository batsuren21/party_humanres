<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Хөдөлмөр эрхлэлтийн бүртгэл</h3>
				</div>
				<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
				<div class="kt-portlet__head-toolbar ">
					<div class="kt-portlet__head-group">
						<a href="javascript:;" data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/job" data-id="<?=$_id?>" data-paramid='0' class="btn btn-brand btn-sm btn-icon-md">
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
                                    	<th width="1%">#</th> 
                                    	<th width="15%">Байгууллагын ангилал</th>
                                    	<th width="20%">Байгууллагын нэр</th> 
                                    	<th width="20%">Газар, хэлтэс, алба</th> 
                                    	<th width="20%">Албан тушаал</th> 
                            		</tr>
                            		
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\PersonJobClass::getInstance()->getRowList(['job_personid'=>$personObj->PersonID,"orderby"=>"JobDateStart"]);
                                        
                                        $now = new DateTime();
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                            $_organList=$refObj->getRowList(["_mainindex"=>"RefOrganID"],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
                                            $_organSubList=$refObj->getRowList(["_mainindex"=>"RefOrganSubID"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
                                            $_positionList=$refObj->getRowList(["_mainindex"=>"RefPositionID"],\Humanres\ReferenceClass::TBL_JOB_POSITION);
                                            
                                            $j=0;
                                            $_isnotorgan=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\PersonJobClass::getInstance($row);
                                                $_organObj=\Humanres\ReferenceClass::getInstance(isset($_organList[$tmpObj->JobOrganID])?$_organList[$tmpObj->JobOrganID]:[]);
                                                $_organSubObj=\Humanres\ReferenceClass::getInstance(isset($_organSubList[$tmpObj->JobOrganSubID])?$_organSubList[$tmpObj->JobOrganSubID]:[]);
                                                $_positionObj=\Humanres\ReferenceClass::getInstance(isset($_positionList[$tmpObj->JobPositionID])?$_positionList[$tmpObj->JobPositionID]:[]);
                                                $_isnotorgan=$_organObj->RefOrganID==6;
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap" rowspan="2">
                                    		<?=$j?>.
                                    		<?php if($_priv_reg || $personObj->PersonIsEditable && $_is_personself){?>
                                    		<br><a href="javascript:;" class="btn btn-primary btn-elevate btn-icon btn-sm" title="засах" data-id="<?=$personObj->PersonID?>" data-paramid='<?=$tmpObj->JobID?>' data-toggle="modal" data-target="#detailSubModal" data-url="<?=RF;?>/m/humanreselect/formemployee/job">
                                                <i class="la la-edit"></i>
                                            </a>
                                    		<?php }?> 
                                    	</td>
                                    	<td class="font-11"> <?=$_organObj->RefOrganTitle;?> <?=$tmpObj->JobOrganSubID>0?", ".$_organSubObj->RefOrganSubTitle:""?></td>
                                    	<td class="font-11"><?=$tmpObj->JobOrganTitle?></td> 
                                    	<td class="font-11"><?=$tmpObj->JobDepartmentTitle?></td> 
                                    	<td class="font-11"><?=$tmpObj->JobPositionID>0?$_positionObj->RefPositionTitle:"";?> <?=$tmpObj->JobPositionTitle?></td> 
                                    </tr>
                                    <tr>
                                    	<td class="font-11" nowrap="nowrap" colspan="2"><?=$_isnotorgan?"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож эхэлсэн:":"Ажилд орсон:"?> <?=$tmpObj->JobDateStart?> <?php if(!$_isnotorgan){?>, Тушаал: <?=$tmpObj->JobStartOrder;?><?php }?></td> 
                                    	<td class="font-11"colspan="2">
                                    	<?php if(!$tmpObj->JobIsNow){?>
                                			<?=$_isnotorgan?"Нийгмийн даатгалын шимтгэл төлсөн хугацааг нөхөн тооцож дууссан:":"Ажлаас гарсан:"?> <?=$tmpObj->JobDateQuit?><?php if(!$_isnotorgan){?>, Тушаал: <?=$tmpObj->JobQuitOrder;?>, Тушаалын огноо: <?=$tmpObj->JobQuitOrderDate;?>
                                    		<div>Шалтгаан: <?=$tmpObj->JobQuitReason;?></div>
                                    		<div>Ажилласан хугацаа: <?=\System\Util::formatDaysMonth($tmpObj->JobWorkedYear,$tmpObj->JobWorkedMonth,$tmpObj->JobWorkedDay);?></div>
                                    		<?php }?>
                                		<?php 
                                            }else{
                                                $_time=\System\Util::getDaysDiff($tmpObj->JobDateStart,$now->format("Y-m-d"));
                            		    ?>
                                			<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill mr-1">Одоо ажиллаж байгаа</span> 
                                    		<div>Ажилласан хугацаа: <?=\System\Util::formatDaysMonth($_time['year'],$_time['month'],$_time['day']);?></div>
                                			
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