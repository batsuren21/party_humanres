<?php 
    \Office\System::$custom_js[]=RF."/assets/js/pages/dashboard.js";
    \Office\System::$custom_js[]=RF."/assets/js/module/humanres/employee/index.js";
    $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_SESSION[SESSSYSINFO]->PersonID]);
    $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(["employee_get_table"=>6,'employee_id'=>$personObj->PersonEmployeeID]);
?>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="row">
		<div class="col-xl-12">
			<div class="kt-portlet kt-portlet--height-fluid">
				<div class="kt-portlet__body">
					<div class="kt-widget kt-widget--user-profile-3">
						<div class="kt-widget__top">
							<div class="kt-widget__media kt-hidden-">
								<img src="<?=$personObj->getImage()?>" alt="image">
							</div>
							
							<div class="kt-widget__content">
								<div class="kt-widget__head">
									<a href="javascript:;" class="kt-widget__username">
										<?=$personObj->PersonLastName;?> <?=$personObj->PersonFirstName;?>
									</a>
									
								</div>
								<div class="kt-widget__subhead">
									<a href="javascript:;"><i class="flaticon2-calendar-3"></i><?=$employeeObj->DepartmentFullName?> </a>
									<a href="javascript:;"><i class="flaticon2-calendar-3"></i><?=$employeeObj->PositionFullName?> </a>
								</div>
								<div class="kt-widget__subhead">
									<a href="javascript:;"><i class="flaticon2-new-email"></i><?=$personObj->PersonContactEmail?></a>
									<a href="javascript:;"><i class="flaticon2-phone"></i><?=$employeeObj->PersonContactMobilePhone?> </a>
									<a href="javascript:;"><i class="flaticon2-phone"></i><?=$employeeObj->PersonContactWorkPhone?> </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'index_home.php';?>
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
<!-- end:: Content -->