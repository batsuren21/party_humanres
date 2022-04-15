<!--begin: Head -->
<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(<?=RF;?>/assets/media/misc/bg-1.jpg)">
	<div class="kt-user-card__avatar">
		<img class="kt-hidden" alt="Pic" src="assets/media/users/300_25.jpg" />

		<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
		<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?=mb_substr($userObj->PersonFirstName, 0,1)?></span>
	</div>
	<div class="kt-user-card__name">
		<?=$userObj->PersonLastName?> <?=$userObj->PersonFirstName?>
	</div>
	<div class="kt-user-card__badge">
	</div>
</div>

<!--end: Head -->

<!--begin: Navigation -->
<div class="kt-notification">
	<div class="kt-notification__custom kt-space-between">
		<a href="<?=RF_LOGIN;?>/process/logout" class="btn btn-label btn-label-brand btn-sm btn-bold">Гарах</a>
		<a href="javascript:;" class="btn btn-clean btn-sm btn-bold" data-toggle="modal" data-target="#regModalProfile" data-url="<?=RF;?>/m/profile/form/password">Нууц үг солих</a>
	</div>
</div>

<!--end: Navigation -->