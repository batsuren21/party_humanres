<?php
    require_once '../libraries/connect.php';
	
    Office\System::$custom_css[]=RF."/login/assets/css/demo1/pages/custom/general/user/login-v1.css";
    Office\System::$custom_js[]=RF."/login/js/pages/login/login.js";
    if(\Office\Permission::isLoginPerson()){
        header("Location: ".RF_LOGIN);
    }
?>
<!DOCTYPE html>
<html lang="en" >
    <?php require_once DRF."/login/partials/_headercss.php"?>
    <!-- begin::Body -->
    <body style="background-image: url(<?=RF?>/login/assets/media/misc/background_1.jpg)" class="kt-login-v1--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
        <?php require_once("_layout.php");?>
        <?php require_once(DRF."/login/partials/_footerjs.php");?>
    </body>
    <!-- end::Body -->
</html>