<?php 
    $userObj=\Office\Permission::getLoggedUser();
?>
<!--begin: User Bar -->
<div class="kt-header__topbar-item kt-header__topbar-item--user">
	<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
		<div class="kt-header__topbar-user">
			<span class="kt-header__topbar-welcome kt-hidden-mobile">Сайн байна уу,</span>
			<span class="kt-header__topbar-username kt-hidden-mobile"><?=$userObj->PersonLFName;?></span>
			<img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />
			<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?=mb_substr($userObj->PersonFirstName, 0,1)?></span>
		</div>
	</div>
	<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
		<?php require_once DRF.'/partials/_topbar/dropdown/user.php';?>
	</div>
</div>
<!--end: User Bar -->