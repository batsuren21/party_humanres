<?php
    $selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem(); 
    $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub(); 
    
    
    echo \Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Мэдээлэл байхгүй байна"]);
    
    