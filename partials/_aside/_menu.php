<?php 
    $packageList=\Office\PackageClass::getInstance()->getRowList(['orderby'=>"PackageOrder"]);
    $selectedSystemObj=\Office\PackageSystemClass::getSelectedSystem();
    $selectedSystemSubObj=\Office\PackageSystemClass::getSelectedSystemSub();
?>
<!-- begin:: Aside Menu -->
<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
	<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
		<ul class="kt-menu__nav ">
			<?php 
		     $packSystemObj=\Office\PackageSystemClass::getInstance();
             foreach ($packageList as $rowpack){
                 $packageObj=\Office\PackageClass::getInstance($rowpack);
                 $sysList=$packSystemObj->getRowList(['packsys_packageid'=>$packageObj->PackageID,"packsys_parentid"=>0,"orderby"=>"SystemOrder"]);
                 $str_menu="";
                 ob_start();
                     
			     foreach ($sysList as $rowsys){
			         $sysObj=\Office\PackageSystemClass::getInstance($rowsys);
			         
			         $priv=1;
			         if($sysObj->SystemPrivID>0){
			             $priv=\Office\Permission::getPriv($sysObj->SystemPrivID);
			         }
			         if($priv){
			         $sysSubList=$packSystemObj->getRowList(["packsys_parentid"=>$sysObj->SystemID,"orderby"=>"SystemOrder"]);
			         if($sysObj->SystemCountChild<1){
			             $activeClass=$sysObj->SystemID==$selectedSystemObj->SystemID?" kt-menu__item--active ":"";
		     ?>
    		     <li class="kt-menu__item <?=$activeClass;?> " aria-haspopup="true">
    				<a href="<?=RF;?>/home" class="kt-menu__link ">
    				<i class="kt-menu__link-icon <?=$sysObj->SystemIcon?>"></i><span class="kt-menu__link-text"><?=$sysObj->SystemName?></span>
    				</a>
    			</li>
    		     <?php 
    	             }else{
    	                 $activeClass=$sysObj->SystemID==$selectedSystemObj->SystemID?" kt-menu__item--open ":"";
                 ?>
    			<li class="kt-menu__item  kt-menu__item--submenu <?=$activeClass?>" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
    				<a href="javascript:;" class="kt-menu__link kt-menu__toggle">
    					<i class="kt-menu__link-icon <?=$sysObj->SystemIcon?>"></i><span class="kt-menu__link-text"><?=$sysObj->SystemName?></span>
    					<i class="kt-menu__ver-arrow la la-angle-right"></i>
    				</a>
    				<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
    					<ul class="kt-menu__subnav">
    					<?php 
    					    foreach ($sysSubList as $rowsyssub){
    					       $sysSubObj=\Office\PackageSystemClass::getInstance($rowsyssub);
    					       
    					       $priv_sub=1;
    					       if($sysSubObj->SystemPrivID>0){
    					           $priv_sub=\Office\Permission::getPriv($sysSubObj->SystemPrivID);
    					       }
    					       if($priv_sub){
    					       $activeClassSub=$sysSubObj->SystemID==$selectedSystemSubObj->SystemID?" kt-menu__item--active ":"";
    				    ?>
    					<li class="kt-menu__item <?=$activeClassSub?>" aria-haspopup="true"><a href="<?=RF;?>/<?=$sysObj->SystemModule;?>/<?=$sysSubObj->SystemModule;?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?=$sysSubObj->SystemName;?></span></a></li>
    					<?php }}?>
    					</ul>
    				</div>
    			</li>
			<?php }}
                 }
    		      $str_menu= ob_get_contents();
    		      if($str_menu!="")
    		          ob_end_clean();
    		          if($packageObj->PackageID>1 && $str_menu!=""){
		     ?>
			<li class="kt-menu__section ">
				<h4 class="kt-menu__section-text"><?=$packageObj->PackageName;?></h4>
				<i class="kt-menu__section-icon flaticon-more-v2"></i>
			</li>
			<?php 
		          }
			     echo $str_menu;
             }
             ?>
		</ul>
	</div>
</div>

<!-- end:: Aside Menu -->