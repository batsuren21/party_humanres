<?php
    $_path=array(
        DRF."/libraries/class/",
        DRF."/libraries/class/abstract/",
        DRF."/libraries/class/trait/",
        DRF."/libraries/class/main/",
        DRF."/libraries/class/main/humanres/",
        DRF."/libraries/class/main/package/",
        DRF."/libraries/class/priv/",
    );
    foreach($_path as $path){
        $files = scandir($path);
        foreach ($files as $file) {
            $ext=strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if($ext=='php'){
                require_once $path.$file;
            }
        }
    }