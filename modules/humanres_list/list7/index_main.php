<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/datatables/datatables.bundle.css";
    Office\System::$custom_css[]=RF."/assets/css/pages/wizard/wizard-3.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/datatables/datatables.bundle.js";
    Office\System::$custom_js[]=RF."/assets/js/module/humanres_list/main/index.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_organType=$refObj->getRowList(["orderby"=>"RefOrganTypeOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGANTYPE);
    $_organList=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"RefOrganOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGAN);
    $_organSubList=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"RefOrganSubOrder"],\Humanres\ReferenceClass::TBL_JOB_ORGANSUB);
    $_refPosList=\Humanres\ReferenceClass::getInstance()->getRowList(["orderby"=>"RefPositionOrder"],\Humanres\ReferenceClass::TBL_JOB_POSITION);
    $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_isactive"=>1,'orderby'=>"DepartmentOrder"]); 
?>
<style>
<!--
.dataTables_scroll
{
    overflow:auto;
}
td {
    white-space: nowrap;
    max-width: 100%;
}
-->
</style>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet ">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<i class="kt-font-brand <?=$selectedModuleObj->SystemIcon?>"></i>
				</span>
				<h3 class="kt-portlet__head-title">
					<?=$selectedModuleSubObj->SystemName;?>
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a href="javascript:;" class="btn btn-brand btn-elevate btn-icon-sm download_excel" 
    						data-url="<?=RF?>/m/humanres_list/export/excel7" 
    						data-title="<?=$selectedModuleSubObj->SystemName;?>" 
						>
							<i class="la la-cloud-download"></i> Excel ??????????
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body" id="search">
			<div class="accordion  accordion-toggle-arrow" id="accordionExample4">
				<div class="card">
					<div class="card-body">
							<div class="row kt-margin-b-20">
            				</div>
            				<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
            				<div class="row">
            					<div class="col-lg-12">
            						<button class="btn btn-primary btn-brand--icon btn-sm" id="m_search">
            							<span>
            								<i class="la la-search"></i>
            								<span>????????</span>
            							</span>
            						</button>
            						&nbsp;&nbsp;
            						<button class="btn btn-secondary btn-secondary--icon btn-sm" id="m_reset">
            							<span>
            								<i class="la la-close"></i>
            								<span>????????????????</span>
            							</span>
            						</button>
            					</div>
            				</div>
            		</div>
				</div>
			</div>
			<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>	
			<div class="dataTables_scroll">
			<table class="table  table-bordered table-hover " id="mainlist" data-url="<?=RF;?>/m/humanres_list/list/list7">
			<thead>
				<tr>
					<th class="text-center font-12" nowrap width="10">??/??</th>
					<th class="text-center font-12" nowrap width="10"></th>
					<th class="text-center font-12" nowrap width="30"></th>
					<th class="text-center font-12" nowrap width="70">?????????????? ?????????????????? ??????????</th>
					<th class="text-center font-12" nowrap width="70">???????????????????? ???</th>
					<th class="text-center font-12" nowrap width="60">???????????? ???????????????????????? ????????</th>
					<th class="text-center font-12" nowrap width="60">?????????? ????????????</th>
					<th class="text-center font-12" nowrap width="60">????????, ?????????? ??????</th>
					<th class="text-center font-12" nowrap width="100">???????????? ??????</th>
					<th class="text-center font-12" nowrap width="100">???????????? ??????????</th>
					<th class="text-center font-12" nowrap width="100">????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ??????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ??????????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ?????? ??????????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ??????</th>
					<th class="text-center font-12" nowrap width="100">??????????, ????????????, ????????</th>
					<th class="text-center font-12" nowrap width="100">?????????? ????????????</th>
					<th class="text-center font-12" nowrap width="100">???????? ?????????????? ???????????? ????????</th>
					<th class="text-center font-12" nowrap width="100">?????????? ?????????? ??????????</th>
					<th class="text-center font-12" nowrap width="100">???????????? ???????????? ??????????</th>
					<th class="text-center font-12" nowrap width="100">?????????? ?????????? ???????????????? ????????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ????????????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ???????????????? ????????????</th>
					<th class="text-center font-12" nowrap width="100">???????????????????????? ???????????????? ??????????</th>
				</tr>
				<tr>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center"></th>
					<th class="text-center" ></th>
					<th class="text-center"></th>
					<th class="text-center" ></th>
					<th class="text-center" ><input type="text" name="searchdata[person_registernumber]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[employee_departmentid]">
							<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_ALL,"selected"=>-1])?>
						</select>
					</th>
					<th class="text-center" ></th>
					<th class="text-center" ><input type="text" name="searchdata[person_lastname]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" ><input type="text" name="searchdata[person_firstname]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" nowrap>
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[person_birthdatestart]"  placeholder="?????????? ??????????" data-col-index="5" />
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[person_birthdateend]" placeholder="???????????? ??????????" data-col-index="5" />
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " name="searchdata[person_gender]" >
                			<?php \System\Combo::getCombo(["data"=>\Office\CitizenClass::$gender,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>-1])?>
                		</select>
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[job_organtypeid]"  >
            				<?php \System\Combo::getCombo(["data"=>$_organType,"title"=>"RefOrganTypeTitle","value"=>"RefOrganTypeID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
            			</select>
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[job_organid]"  >
            				<?php \System\Combo::getCombo(["data"=>$_organList,"title"=>"RefOrganTitle","value"=>"RefOrganID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
            			</select>
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[job_organsubid]"  >
            				<?php \System\Combo::getCombo(["data"=>$_organSubList,"title"=>"RefOrganSubTitle","value"=>"RefOrganSubID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
            			</select>
					</th>
					<th class="text-center" ><input type="text" name="searchdata[job_organtitle]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" ></th>
					<th class="text-center" ></th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[job_isnow]"  >
            				<?php \System\Combo::getCombo(["data"=>\Humanres\PersonClass::$is_true,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>-1])?>
						</select>
					</th>
            		<th class="text-center" >	
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_startdatestart]"  placeholder="?????????? ??????????" data-col-index="5" />
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_startdateend]" placeholder="???????????? ??????????" data-col-index="5" />
					</th>
					<th class="text-center" >	
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_quitdatestart]"  placeholder="?????????? ??????????" data-col-index="5" />
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_quitdateend]" placeholder="???????????? ??????????" data-col-index="5" />
					</th>
					<th class="text-center" ><input type="text" name="searchdata[job_startorder]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" ><input type="text" name="searchdata[job_quitreason]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" ><input type="text" name="searchdata[job_quitorder]" class="form-control kt-input form-control-sm"></th>
					<th class="text-center" >	
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_quitorderdatestart]"  placeholder="?????????? ??????????" data-col-index="5" />
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[job_quitorderdateend]" placeholder="???????????? ??????????" data-col-index="5" />
					</th>
				</tr>
			</tfoot>
			</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade " role="dialog" id="detailModalFelony" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog full_modal-dialog">
        <div class="modal-content full_modal-content">
        </div>
    </div>
</div>
<div class="modal fade modal-top" role="dialog" id="detailPrintModalFelony" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="modal-backdrop" class="d-none"></div>