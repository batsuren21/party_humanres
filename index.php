<?php
    require_once 'libraries/connect.php';
    
    if(!isset($_GET['_page'])){
        if(!\Office\Permission::isLoginPerson()){
            header("Location: ".RF_LOGIN);
        }
        $_module=isset($_GET['_module']) && $_GET['_module']!=""?$_GET['_module']:"home";
        $_submodule=isset($_GET['_submodule']) && $_GET['_submodule']!=""?$_GET['_submodule']."/":"";
       
//         $_SESSION['editor_base_URL']=RF."/editor/".($loggedUserObj->UserOrganID!=""?"org".$loggedUserObj->UserOrganID."/":"");
        $selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem();
        if(isset($_GET['_module']) && $_GET['_module']!="" && !$selectedSystemObj->isExist()){
            require_once DRF.'/error/mainbody.php';
            exit;
        }
        
        if($selectedSystemObj->SystemCountChild>0){
            $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
            if(isset($_GET['_submodule']) && $_GET['_submodule']!="" && !$selectedSystemSubObj->isExist()){
                require_once DRF.'/error/mainbody.php';
                exit;
            }
        }
        if(!file_exists(DRF.'/modules/'.$_module.'/'.$_submodule.'index.php')){
            require_once DRF.'/error/mainbody.php';
            exit;
        }
        $__content=\Office\System::getPage($_module.'/'.$_submodule.'index',array(),DRF);
?>
<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<?php require_once "partials/_headercss.php"?>
	<!-- end::Head -->
	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
		<?php require_once("layout.php");?>
		<?php require_once("partials/_footerjs.php");?>
	</body>
	<!-- end::Body -->
</html>
<?php 
    }else{
        if(file_exists(DRF.'/modules/'.$_GET['_page'])){
            require_once DRF.'/modules/'.$_GET['_page'];
        }else{
            require_once DRF.'/error/main.php';
            exit;
        }
    }
?>