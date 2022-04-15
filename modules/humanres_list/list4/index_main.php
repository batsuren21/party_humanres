<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/datatables/datatables.bundle.css";
    Office\System::$custom_css[]=RF."/assets/css/pages/wizard/wizard-3.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/datatables/datatables.bundle.js";
    Office\System::$custom_js[]=RF."/assets/js/module/humanres_list/main/index.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_isactive"=>1,'orderby'=>"DepartmentOrder"]);
    
    $_langList=$refObj->getRowList(["orderby"=>"RefLanguageOrder"],\Humanres\ReferenceClass::TBL_LANGUAGE);
    $_langLevelList=$refObj->getRowList(['orderby'=>"RefLevelOrder"],\Humanres\ReferenceClass::TBL_LANGUAGE_LEVEL);
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
    						data-url="<?=RF?>/m/humanres_list/export/excel4" 
    						data-title="<?=$selectedModuleSubObj->SystemName;?>" 
						>
							<i class="la la-cloud-download"></i> Excel татах
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
            								<span>Хайх</span>
            							</span>
            						</button>
            						&nbsp;&nbsp;
            						<button class="btn btn-secondary btn-secondary--icon btn-sm" id="m_reset">
            							<span>
            								<i class="la la-close"></i>
            								<span>Цэвэрлэх</span>
            							</span>
            						</button>
            					</div>
            				</div>
            		</div>
				</div>
			</div>
			<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>	
			<div class="dataTables_scroll">
			<table class="table  table-bordered table-hover " id="mainlist" data-url="<?=RF;?>/m/humanres_list/list/list4">
			<thead>
				<tr>
					<th class="text-center font-12" nowrap width="10">Д/д</th>
					<th class="text-center font-12" nowrap width="10"></th>
					<th class="text-center font-12" nowrap width="30"></th>
					<th class="text-center font-12" nowrap width="70">Системд бүртгэсэн огноо</th>
					<th class="text-center font-12" nowrap width="70">Регистрийн №</th>
					<th class="text-center font-12" nowrap width="60">Зохион байгуулалтын нэгж</th>
					<th class="text-center font-12" nowrap width="60">Албан тушаал</th>
					<th class="text-center font-12" nowrap width="60">Эцэг, эхийн нэр</th>
					<th class="text-center font-12" nowrap width="100">Өөрийн нэр</th>
					<th class="text-center font-12" nowrap width="100">Төрсөн огноо</th>
					<th class="text-center font-12" nowrap width="100">Хүйс</th>
					<th class="text-center font-12" nowrap width="100">Гадаад хэл</th>
					<th class="text-center font-12" nowrap width="100">Мэдлэгийн түвшин</th>
					<th class="text-center font-12" nowrap width="100">Судалсан жил</th>
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
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[person_birthdatestart]"  placeholder="Эхлэх огноо" data-col-index="5" />
						<input type="text" class="form-control kt-input  form-control-sm font-12 datepicker" style="width: 75px;" name="searchdata[person_birthdateend]" placeholder="Дуусах огноо" data-col-index="5" />
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " name="searchdata[person_gender]" >
                			<?php \System\Combo::getCombo(["data"=>\Office\CitizenClass::$gender,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>-1])?>
                		</select>
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[language_refid]"  >
            				<?php \System\Combo::getCombo(["data"=>$_langList,"title"=>"RefLanguageTitle","value"=>"RefLanguageID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
            			</select>
					</th>
					<th class="text-center" >
						<select class="form-control kt-input form-control-sm " data-col-index="2" name="searchdata[language_levelid]"  >
            				<?php \System\Combo::getCombo(["data"=>$_langLevelList,"title"=>"RefLevelTitle","value"=>"RefLevelID","flag"=>\System\Combo::SELECT_SINGLE,"selected"=>""])?>
            			</select>
					</th>
					<th class="text-center" ></th>
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