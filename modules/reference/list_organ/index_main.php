<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/datatables/datatables.bundle.css";
    Office\System::$custom_css[]=RF."/assets/css/pages/wizard/wizard-1.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/datatables/datatables.bundle.js";
    Office\System::$custom_js[]=RF."/assets/js/module/reference/organlist/index.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $_genList=\Office\OrganFinanceGeneralClass::getInstance()->getRowList(['orderby'=>"FinGenTitle"]);
    $_genList=array_merge($_genList,[["FinGenID"=>0,"FinGenTitle"=>"--- Хоосон ---"]]);
    $_cityList=\Office\OrganAddressClass::getInstance()->getRowList(["address_parentid"=>0,'orderby'=>"Address"]);
    
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
							data-url="<?=RF;?>/m/reference/form/add_organ">
							<i class="la la-plus"></i>
							Бүртгэх
						</button>
					<?php }?>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div class="accordion  accordion-toggle-arrow" id="accordionExample4">
				<div class="card">
					<div class="card-header" id="headingOne4">
						<div class="card-title" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
							<i class="flaticon2-search-1"></i> Дэлгэрэнгүй хайлт
						</div>
					</div>
					<div id="collapseOne4" class="collapse " aria-labelledby="headingOne" data-parent="#accordionExample4">
						<div class="card-body" id="search">
							<div class="row kt-margin-b-20">
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Төсвийн ерөнхийлөн захирагч:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[organ_genid]">
            							
            							<?php \System\Combo::getCombo(["data"=>$_genList,"title"=>"FinGenTitle","value"=>"FinGenID","flag"=>\System\Combo::SELECT_ALL,"selected"=>-1])?>
        							</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Аймаг, нийслэл:</label>
            						<select class="form-control kt-input form-control-sm ajax_select" data-col-index="6" name="searchdata[organ_cityid]"
                							data-url="<?=RF;?>/m/ajax/select" 
                							data-action="organlistdistrict"
                							data-target="#searchdistrict"
                						>
            							<?php \System\Combo::getCombo(["data"=>$_cityList,"title"=>"Address","value"=>"AddressID","flag"=>\System\Combo::SELECT_ALL,"selected"=>-1])?>
            						</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Сум, дүүрэг:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[organ_districtid]" id="searchdistrict">
            						</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Нэр:</label>
            						<input type="text" class="form-control kt-input form-control-sm font-12" placeholder="" name="searchdata[organ_name]" data-col-index="0">
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Идэвхитэй эсэх:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[organ_isactive]">
            							<option value="">-- Бүгд ---</option>
            							<option value="1">Тийм</option>
            							<option value="0">Үгүй</option>
            						</select>
            					</div>
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
			</div>
			<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>	
			<table class="table table-striped- table-bordered table-hover table-header-blue" id="mainlist">
			<thead>
				<tr>
					<th class="text-center" nowrap width="1%">Д/д</th>
					<th class="text-center" width="1%"></th>
					<th class="text-center" nowrap width="10%">ТЕЗ</th>
					<th class="text-center" nowrap width="10%">ТШЗ</th>
					<th class="text-center" nowrap width="10%">Аймаг, нийслэл </th>
					<th class="text-center" nowrap width="10%">Сум, дүүрэг</th>
					<th class="text-center" nowrap width="20%">Байгууллага</th>
					<th class="text-center" nowrap width="1%">Идэвхитэй эсэх</th>
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