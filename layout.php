<!-- begin:: Page -->
<?php require_once("partials/_header/base-mobile.php")?>
<div class="kt-grid kt-grid--hor kt-grid--root">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
		<?php require_once("partials/_aside/base.php")?>
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
			<?php require_once("partials/_header/base.php")?>
			<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
				<?php require_once("partials/_subheader/subheader-v1.php")?>
				<?php require_once("partials/_content/base.php")?>
				<div class="modal fade modal-top" tabindex="-1" role="dialog" id="regModalProfile" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        </div>
                    </div>
                </div>
			</div>
			<?php require_once("partials/_footer/base.php");?>
		</div>
	</div>
</div>
<!-- end:: Page -->
<?php require_once("partials/_scrolltop.php");?>