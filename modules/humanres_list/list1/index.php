<?php
    $selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem(); 
    $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub(); 
    
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_ACCESS);
    $_priv_list_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_LIST);
    
    if($_priv_access && $_priv_list_access){
        if(isset($_GET['id'])){
            require_once (DRF_ADMIN."/modules/mod_period/detail.php");
        }else{
            if(isset($_GET['felonyid'])){
                $page="detail";
            }else $page="main";
            $page=isset($_GET['_pagesub'])?$_GET['_pagesub']:$page;
            if(file_exists(DRF."/modules/".$selectedSystemObj->SystemModule."/".$selectedSystemSubObj->SystemModule."/index_".$page.".php")){
                require_once (DRF."/modules/".$selectedSystemObj->SystemModule."/".$selectedSystemSubObj->SystemModule."/index_".$page.".php");
            }else{
                require_once DRF.'/error/mainbody.php';
                exit;
            }
        }
    }else{
        echo \Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд нэвтрэх эрх байхгүй байна"]);
    }
    
    