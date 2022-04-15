
<!-- begin:: Header Menu -->
<?php 
    $officeObj=\Office\OfficeConfig::getOffice();
    $str="";
    if($officeObj->isExist()){
        $str=$officeObj->OfficeTitle;
    }
?>
<!-- Uncomment this to display the close button of the panel
<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
-->
<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
	<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default ">
		<ul class="kt-menu__nav ">
			<li class="kt-menu__item  kt-menu__item--open kt-menu__item--here kt-menu__item--submenu kt-menu__item--rel kt-menu__item--open kt-menu__item--here kt-menu__item--active" data-ktmenu-submenu-toggle="click" aria-haspopup="true">
				<span style="font-weight: bold; text-transform: uppercase;"><?=$str?></span> 
			</li>
		</ul>
	</div>
</div>

<!-- end:: Header Menu -->