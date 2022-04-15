<?php 
$selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem();

?>
<!-- begin:: Subheader -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
	<div class="kt-container  kt-container--fluid ">
		<div class="kt-subheader__main">
			<h3 class="kt-subheader__title">
				<?=$selectedSystemObj->SystemName;?> </h3>
			<?php 
			if($selectedSystemObj->SystemCountChild>0){
			    $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
			?>
			<span class="kt-subheader__separator kt-hidden"></span>
			<div class="kt-subheader__breadcrumbs">
				<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
				<span class="kt-subheader__breadcrumbs-separator"></span>
				<a href="<?=RF;?>/<?=$selectedSystemObj->SystemModule;?>/<?=$selectedSystemSubObj->SystemModule;?>" class="kt-subheader__breadcrumbs-link">
					<?=$selectedSystemSubObj->SystemName;?> </a>
				
				<!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->

			</div>
			<?php }?>
		</div>
	</div>
</div>

<!-- end:: Subheader -->