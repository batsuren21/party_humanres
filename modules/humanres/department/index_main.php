<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/datatables/datatables.bundle.css";
    Office\System::$custom_css[]=RF."/assets/css/pages/wizard/wizard-3.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/datatables/datatables.bundle.js";
    Office\System::$custom_js[]=RF."/assets/js/module/humanres/department/index.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
?>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="kt-portlet kt-portlet--mobile">
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
					<?php 
					   if($_priv_reg){?>
						<button type="button" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#regModal" 
							data-id="0"
							data-url="<?=RF;?>/m/humanres/form/add_department">
							<i class="la la-plus"></i>
							Зохион байгуулалтын нэгж бүртгэх
						</button>
					<?php }?>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<table class="table table-striped- table-bordered table-hover table-header-blue" id="mainlist">
			<thead>
				<tr>
					<th class="text-center" nowrap width="1%">Д/д</th>
					<th class="text-center" width="1%"></th>
					<th class="text-center" nowrap width="20%">Товч нэр</th>
					<th class="text-center" nowrap width="40%">Нэр</th>
					<th class="text-center" width="1%">Орон тоо</th>
					<th class="text-center">Бүртгэгдсэн <br> албан тушаал</th>
					<th class="text-center">Төрөл</th>
					<th class="text-center" nowrap> Ангилал</th>
					<th class="text-center"> Эрэмбэ</th>
				</tr>
			</thead>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="detailModal" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade modal-top" tabindex="-1" role="dialog" id="detailSubModal" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade modal-top" tabindex="-1" role="dialog" id="regModal" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade modal-top1" tabindex="-1" role="dialog" id="detailSubModal1" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>
<div id="modal-backdrop" class="d-none"></div>