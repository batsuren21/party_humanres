<div class="row">
	<div class="col">
		<div class="kt-portlet kt-portlet-noneshadow mt-3 portlets" data-ktportlet="true" id="kt_portlet_tools_1">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">Албан тушаалын бүртгэл</h3>
				</div>
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
                                    	<th width="20%">Албан тушаалын нэр</th>
                                    	<th width="20%">Томилогдсон байдал</th> 
                                    	<th width="20%">Томилогдсон огноо</th> 
                                    	<th width="20%">Томилогдсон тушаалын дугаар, огноо</th> 
                                    	<th width="30%">Чөлөөлсөн байдал</th>
                                    	<th width="20%">Чөлөөлсөн огноо</th>
                                    	<th width="20%">Чөлөөлсөн тушаалын дугаар, огноо</th>
                            		</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $_mainlist=\Humanres\EmployeeClass::getInstance()->getRowList(["employee_get_table"=>6,'employee_personid'=>$personObj->PersonID,"orderby"=>"T.EmployeeID"]);
                                        
                                        if(count($_mainlist)>0){
                                            $refObj=\Humanres\ReferenceClass::getInstance();
                                             $_startList=$refObj->getRowList(["_mainindex"=>"RefStartID"],\Humanres\ReferenceClass::TBL_EMPLOYEE_START);
                                            $_quitList=$refObj->getRowList(["_mainindex"=>"RefQuitID"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT);
                                            $_quitSubList=$refObj->getRowList(["_mainindex"=>"RefSubID"],\Humanres\ReferenceClass::TBL_EMPLOYEE_QUIT_SUB);
                                            
                                            $j=0;
                                            foreach ($_mainlist as $row){
                                                $j++;
                                                $tmpObj= \Humanres\EmployeeClass::getInstance($row);
                                                
                                                $is_edit_row=1;
                                                $_startObj=\Humanres\ReferenceClass::getInstance(isset($_startList[$tmpObj->EmployeeStartID])?$_startList[$tmpObj->EmployeeStartID]:[]);
                                                $_quitObj=\Humanres\ReferenceClass::getInstance(isset($_quitList[$tmpObj->EmployeeQuitID])?$_quitList[$tmpObj->EmployeeQuitID]:[]);
                                                $_quitSubObj=\Humanres\ReferenceClass::getInstance(isset($_quitSubList[$tmpObj->EmployeeQuitSubID])?$_quitSubList[$tmpObj->EmployeeQuitSubID]:[]);
                                                
                                    ?>
                                    <tr>
                                    	<td nowrap="nowrap">
                                    		<?=$j?>.
                                    	</td>
                                    	
                                    	<td><?=$tmpObj->PositionFullName?></td>
                                    	<td><?=$_startObj->RefStartTitle?></td> 
                                    	<td><?=$tmpObj->EmployeeStartDate?></td> 
                                    	<td><?=$tmpObj->EmployeeStartOrderNo.", ".$tmpObj->EmployeeStartOrderDate?></td> 
                                    	<td><?=$_quitObj->RefQuitTitle.($_quitSubObj->RefSubTitle!=""?", ".$_quitSubObj->RefSubTitle:"");?></td>
                                    	<td><?=$tmpObj->EmployeeQuitDate?></td>
                                    	<td><?=$tmpObj->EmployeeQuitOrderNo.($tmpObj->EmployeeQuitOrderDate!=""?", ".$tmpObj->EmployeeQuitOrderDate:"");?></td> 
                                    </tr>
                                    <?php }}else{?>
                                    <tr>
                                    	<td colspan="8" class="text-center"><i>Бүртгэл хийгдээгүй</i></td>
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