<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_js[]=RF."/assets/js/module/department/report/index.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $now=new \DateTime();
    $_default_startdate=$now->format("Y")."-01-01";
    $_default_enddate=$now->format("Y-m-d");
?>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="row">
		<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<span class="kt-portlet__head-icon">
        					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
        				</span>
        				<h3 class="kt-portlet__head-title">
        					Ажилласан байдал
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
						<div class="row kt-margin-b-20">
        					<div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
        						<label class="font-12">Тооцоо хийх  огноо:</label>
        						<div class="input-daterange input-group " id="kt_datepicker">
        							<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" name="searchdata[dateend]" placeholder="Огноо" data-col-index="5" value="<?=$_default_enddate;?>"/>
        						</div>
        					</div>
        				</div>
        				<div class="row ">
        					<div class="col-lg-12 text-center">
        						<div class="dropdown dropdown-inline">
        							<button type="button" class="btn btn btn-success btn-elevate-hover  btn-sm  " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        								<i class="la la-cloud-download"></i> Тайлан татах 
        							</button>
        							<div class="dropdown-menu dropdown-menu-right">
        								<a class="dropdown-item download_excel" href="javascript:;" data-url="<?=RF;?>/m/department/export/workdays_excel1">
        									1. Улсад ажилласан жил
    									</a>
        								<a class="dropdown-item download_excel" href="javascript:;" data-url="<?=RF;?>/m/department/export/workdays_excel2">
        									2. Төрд ажилласан жил
    									</a>
        								<a class="dropdown-item download_excel" href="javascript:;" data-url="<?=RF;?>/m/department/export/workdays_excel3">
        									3. Аж ахуйн нэгжид ажилласан жил
    									</a>
        								<a class="dropdown-item download_excel" href="javascript:;" data-url="<?=RF;?>/m/department/export/workdays_excel4">
        									4. АТГ-т ажилласан жил
    									</a>
        							</div>
        						</div>
    						</div>
        				</div>
            		</div>
        		</div>
        	</div>
    	</div>
    	<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<span class="kt-portlet__head-icon">
        					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
        				</span>
        				<h3 class="kt-portlet__head-title">
        					Ажилласан жил албан хаагчаар
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
						<div class="row kt-margin-b-20">
        					<div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
        						<label class="font-12">Тооцоо хийх  огноо:</label>
        						<div class="input-daterange input-group " id="kt_datepicker">
        							<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" name="searchdata[dateend]" placeholder="Огноо" data-col-index="5" value="<?=$_default_enddate;?>"/>
        						</div>
        					</div>
        				</div>
        				<div class="row ">
        					<div class="col-lg-12 text-center">
								<button type="button" class="btn btn-brand btn-elevate btn-icon-sm download_excel" data-url="<?=RF;?>/m/department/export/workdays_employee_excel">
        							<i class="la la-download"></i> Тайлан татах 
        						</button>
    						</div>
        				</div>
            		</div>
        		</div>
        	</div>
    	</div>
    	<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<span class="kt-portlet__head-icon">
        					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
        				</span>
        				<h3 class="kt-portlet__head-title">
        					Шагналын тоо
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
						
        				<div class="row ">
        					<div class="col-lg-12 text-center">
								<button type="button" class="btn btn-brand btn-elevate btn-icon-sm download_excel" data-url="<?=RF;?>/m/department/export/award_excel">
        							<i class="la la-download"></i> Тайлан татах 
        						</button>
    						</div>
        				</div>
            		</div>
        		</div>
        	</div>
    	</div>
    	<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<span class="kt-portlet__head-icon">
        					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
        				</span>
        				<h3 class="kt-portlet__head-title">
        					Шагнал урамшуулал авсан албан хаагчид
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
						<div class="row kt-margin-b-20">
        					<div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
        						<label class="font-12">Тооцоо хийх  огноо:</label>
        						<div class="input-daterange input-group " id="kt_datepicker">
        							<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" name="searchdata[dateend]" placeholder="Огноо" data-col-index="5" value="<?=$_default_enddate;?>"/>
        						</div>
        					</div>
        				</div>
        				<div class="row ">
        					<div class="col-lg-12 text-center">
								<button type="button" class="btn btn-brand btn-elevate btn-icon-sm download_excel" data-url="<?=RF;?>/m/department/export/award_employee_excel">
        							<i class="la la-download"></i> Тайлан татах 
        						</button>
    						</div>
        				</div>
            		</div>
        		</div>
        	</div>
    	</div>
    	<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
        	<div class="kt-portlet kt-portlet--mobile">
        		<div class="kt-portlet__head kt-portlet__head--lg">
        			<div class="kt-portlet__head-label">
        				<span class="kt-portlet__head-icon">
        					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
        				</span>
        				<h3 class="kt-portlet__head-title">
        					 Сургалтад хамрагдсан албан хаагч
        				</h3>
        			</div>
        		</div>
        		<div class="kt-portlet__body ">
        			<div class="card-body search_body" id="search">
						<div class="row kt-margin-b-20">
        					<div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
        						<label class="font-12">Сургалтын огноо:</label>
        						<div class="input-daterange input-group " id="kt_datepicker">
        							<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" name="searchdata[datestart]"  placeholder="Эхлэх огноо" data-col-index="5" value="<?=$_default_startdate;?>"/>
        							<div class="input-group-append">
        								<span class="input-group-text "><i class="la la-ellipsis-h"></i></span>
        							</div>
        							<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" name="searchdata[dateend]" placeholder="Дуусах огноо" data-col-index="5" value="<?=$_default_enddate;?>"/>
        						</div>
        					</div>
        				</div>
        				<div class="row ">
        					<div class="col-lg-12 text-center">
								<button type="button" class="btn btn-brand btn-elevate btn-icon-sm download_excel" data-url="<?=RF;?>/m/department/export/study_employee_excel">
        							<i class="la la-download"></i> Тайлан татах 
        						</button>
    						</div>
        				</div>
            		</div>
        		</div>
        	</div>
    	</div>
	</div>
</div>
