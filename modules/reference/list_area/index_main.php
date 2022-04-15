<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    Office\System::$custom_css[]=RF."/assets/plugins/custom/jstree/jstree.bundle.css";
    Office\System::$custom_js[]=RF."/assets/plugins/custom/jstree/jstree.bundle.js";
    
    Office\System::$custom_js[]=RF."/assets/js/module/reference/arealist/index.js";
    
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
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div class="row">
				<div class="col-lg-6">
                    <div class="kt-portlet" data-ktportlet="true" id="areaTreePortlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <span class="kt-portlet__head-icon">
                                    <i class="flaticon-map kt-label-font-color-2"></i>
                                </span>
                                <h3 class="kt-portlet__head-title">
                                    Нутаг дэвсгэр
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-group">
                                    <a href="javascript:;" class="btn btn-sm btn-icon btn-default btn-circle" id="reloadtree"><i class="la la-refresh"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="kt-portlet__content" id="jstree">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" id="areaFormPortlet">
                    <div class="alert alert-secondary" role="alert">
                        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                        <div class="alert-text">
                            Та шинээр нутаг дэвсгэр нэмэх, засварлах, устгахыг хүсвэл тухайн цэсэн дээр хулганы 2 дахь товчийг дарж хийнэ үү.
                        </div>
                    </div>
                </div>
			</div>
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