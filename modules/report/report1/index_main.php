<?php
    $selectedModuleObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedModuleSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
    
    $__con = new Database();
    
    Office\System::$custom_js[]=RF."/assets/js/pages/components/charts/amcharts4/core.js";
    Office\System::$custom_js[]=RF."/assets/js/pages/components/charts/amcharts4/charts.js";
    Office\System::$custom_js[]=RF."/assets/js/pages/components/charts/amcharts4/themes/animated.js";
    Office\System::$custom_js[]=RF."/assets/js/module/report/report1/index.js";
    
    $__departmentid=isset($_GET['_departmentid'])?$_GET['_departmentid']:"";
    
    $_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();
    
    $_departmentList=\Humanres\DepartmentClass::getInstance()->getRowList([
        "department_isactive"=>1,
        "department_classid"=>\Humanres\DepartmentClass::CLASS_BASIC,
        'orderby'=>"DepartmentOrder"]);
    
    $_departmentObj=\Humanres\DepartmentClass::getInstance();
    if($__departmentid>0){
        $_departmentObj=\Humanres\DepartmentClass::getInstance()->getRow([
            'department_id'=>$__departmentid
        ]);
    }
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
						<table>
							<tr>
								<td width="1%" nowrap="nowrap">Зохион байгуулалт: </td>
								<td width="100%">	
            						<select class="form-control kt-input form-control-sm departmentchange" data-col-index="2" name="departmentid">
            							<?php \System\Combo::getCombo(["data"=>$_departmentList,"title"=>"DepartmentFullName","value"=>"DepartmentID","flag"=>\System\Combo::SELECT_ALL,"selected"=>$__departmentid])?>
            						</select>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div class="container clearfix">
            	<div class="fancy-title title-border text-center topmargin-sm">
            	<?php if($_departmentObj->isExist()){?>
            		<h4><span><?=$_departmentObj->DepartmentFullName?></span></h4>
        		<?php }else{?>
            		<h4><span><?=__TITLE__?></span></h4>
        		<?php }?>
        		</div>
    		</div>	
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
			<?php require_once 'sub_depposition.php';?>
    	</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
			<?php require_once 'sub_newmember.php';?>
    	</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
			<?php require_once 'sub_gender.php';?>
    	</div>
    	<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
    		<?php require_once 'sub_age.php';?>
    	</div>
	</div>
	
</div>
<script>
var __departmentid="<?=$__departmentid?>";
</script>