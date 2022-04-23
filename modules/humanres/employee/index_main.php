<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/datatables/datatables.bundle.css";
    Office\System::$custom_css[]=RF."/assets/css/pages/wizard/wizard-1.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/datatables/datatables.bundle.js";
    Office\System::$custom_js[]=RF."/assets/js/module/humanres/employee/index.js";
    Office\System::$custom_css[]=RF."/assets/js/module/print/stimul/css/stimulsoft.viewer.office2013.whiteblue.css";
    Office\System::$custom_js[]=RF."/assets/js/module/print/stimul/scripts/stimulsoft.reports.pack.js";
    Office\System::$custom_js[]=RF."/assets/js/module/print/stimul/scripts/stimulsoft.viewer.pack.js";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList(["department_isactive"=>1,'orderby'=>"DepartmentOrder"]);
    $_statusList=[
        ["id"=>0,"title"=>"Чөлөөлөгдсөн"],
        ["id"=>1,"title"=>"Идэвхитэй"]
    ];
    $_yesList=[
        ["id"=>0,"title"=>"Үгүй"],
        ["id"=>1,"title"=>"Тийм"]
    ];
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
							data-url="<?=RF;?>/m/humanres/form/add_employee">
							<i class="la la-plus"></i>
							Гишүүн бүртгэх
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
            						<label class="font-12">Нэгж:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[employee_departmentid]">
            							<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_ALL,"selected"=>-1])?>
            						</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Төлөв:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[employee_isactive]">
            							<?php \System\Combo::getCombo(["data"=>$_statusList,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_ALL,"selected"=>1])?>
            						</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Мэдээлэл баяжуулах төлөв нээлттэй эсэх:</label>
            						<select class="form-control kt-input form-control-sm" data-col-index="2" name="searchdata[person_iseditable]">
            							<?php \System\Combo::getCombo(["data"=>$_yesList,"title"=>"title","value"=>"id","flag"=>\System\Combo::SELECT_ALL,"selected"=>-1])?>
            						</select>
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Регистрийн дугаар:</label>
            						<input type="text" class="form-control kt-input form-control-sm font-12" placeholder="" name="searchdata[person_registernumber]" data-col-index="0">
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Эцэг эхийн нэр:</label>
            						<input type="text" class="form-control kt-input form-control-sm font-12" placeholder="" name="searchdata[person_lastname]" data-col-index="0">
            					</div>
            					<div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            						<label class="font-12">Өөрийн нэр:</label>
            						<input type="text" class="form-control kt-input form-control-sm font-12" placeholder="" name="searchdata[person_firstname]" data-col-index="0">
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
					<th class="text-center" nowrap width="1%">Нэгж</th>
					<th class="text-center" nowrap width="20%">Албан тушаал</th>
					<th class="text-center" nowrap width="1%">Регистр</th>
					<th class="text-center" nowrap width="20%">Овог, нэр</th>
					<th class="text-center" nowrap width="20%">Утас</th>
					<th class="text-center">Томилогдсон байдал</th>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        </div>
    </div>
</div>
<div class="modal fade modal-top" tabindex="-1" role="dialog" id="detailPrintModalHumanres" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
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