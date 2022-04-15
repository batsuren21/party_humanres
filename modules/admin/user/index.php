<?php
    $selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem(); 
    $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub(); 
    
    $_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_ACCESS);
    
    if($_priv_access){
        if(isset($_GET['id'])){
        }else{
            if(isset($_GET['departmentid'])){
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
        echo \Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд бүтцийн нэгж бүртгэх эрх байхгүй байна"]);
    }
    
    