<?php
$_officeid=isset($_SESSION[SESSSYSINFO]->OfficeID)?$_SESSION[SESSSYSINFO]->OfficeID:\Office\OfficeConfig::getOfficeID();


$now=new \DateTime();
$_letterStatusList=\Office\LetterStatusClass::getInstance()->getRowList(['orderby'=>'StatusOrder']);

$select=array();
$select[]=" SUM(IF(T.LetterIsControl=0,1,0)) as LetterStat".\Office\LetterClass::LETTER_DIRECT;
$select[]=" SUM(IF(T.LetterIsControl=1 and T.LetterIsDecided=0,1,0)) as LetterStat".\Office\LetterClass::LETTER_CONTROL;
$select[]=" SUM(IF(T.LetterIsControl=1 and T.LetterIsDecided=0 and T.LetterControlEndDate>='".$now->format("Y-m-d")."',1,0)) as LetterStat".\Office\LetterClass::LETTER_CONTROL_IN_TIME;
$select[]=" SUM(IF(T.LetterIsControl=1 and T.LetterIsDecided=0 and T.LetterControlEndDate<'".$now->format("Y-m-d")."',1,0)) as LetterStat".\Office\LetterClass::LETTER_CONTROL_LATE_TIME;
$select[]=" SUM(IF(T.LetterIsDecided=1 and T.LetterDecidedDateLate=0,1,0)) as LetterStat".\Office\LetterClass::LETTER_DECIDED_INTIME;
$select[]=" SUM(IF(T.LetterIsDecided=1 and T.LetterDecidedDateLate>0,1,0)) as LetterStat".\Office\LetterClass::LETTER_DECIDED_LATE;
$select[]=" SUM(IF(T.LetterIsDecided=1,1,0)) as LetterStat".\Office\LetterClass::LETTER_DECIDED;
$select[]=" SUM(IF(T.LetterIsComplete=1,1,0)) as LetterStat".\Office\LetterClass::LETTER_CLOSED;
$select[]=" SUM(IF(T.LetterIsDecided=1 and T.LetterIsComplete=0,1,0)) as LetterStat".\Office\LetterClass::LETTER_DECIDED_NOTCLOSED;

$_priv_org=\Office\Permission::getPriv(\Office\PrivClass::PRIV_LETTER_MY_ORG);
$_priv_dep=\Office\Permission::getPriv(\Office\PrivClass::PRIV_LETTER_MY_DEP);
$_priv_dep_all=\Office\Permission::getPriv(\Office\PrivClass::PRIV_LETTER_MY_DEP_ALL);
$_priv_dep_low=\Office\Permission::getPriv(\Office\PrivClass::PRIV_LETTER_MY_DEP_LOW);

$_priv=0;
if($_priv_org){
    $_priv=\Office\PrivClass::PRIV_LETTER_MY_ORG;
}elseif($_priv_dep_all){
    $_priv=\Office\PrivClass::PRIV_LETTER_MY_DEP_ALL;
}elseif($_priv_dep_low){
    $_priv=\Office\PrivClass::PRIV_LETTER_MY_DEP_LOW;
}elseif($_priv_dep){
    $_priv=\Office\PrivClass::PRIV_LETTER_MY_DEP;
}
$search=array();
switch ($_priv){
    case \Office\PrivClass::PRIV_LETTER_MY_ORG:
        break;
    case \Office\PrivClass::PRIV_LETTER_MY_DEP_ALL:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["letter_lastshiftdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_LETTER_MY_DEP_LOW:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["letter_lastshiftdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_LETTER_MY_DEP:
        $search=array_merge($search,["letter_lastshiftdepartmentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        break;
    default:
        $search=array_merge($search,["letter_lastshiftpersonid"=>$_SESSION[SESSSYSINFO]->PersonID]);
        break;
}

$letterObj=\Office\LetterClass::getInstance();
$_resultList=$letterObj->getRowList(array_merge($search,["_select"=>$select,"letter_officeid"=>$_officeid]));
$_allCount=$letterObj->getRowCount(array_merge($search,["_select"=>$select,"letter_officeid"=>$_officeid]));
$_mainList=isset($_resultList[0])?$_resultList[0]:array();

?>


	<!--Begin::Row-->
	<div class="row">
		<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
            <div class="kt-portlet">
            	<div class="kt-portlet__head">
            		<div class="kt-portlet__head-label">
            			<h3 class="kt-portlet__head-title">
            				<?=$_priv<1?"Надад ирсэн бичиг":"Ирсэн бичиг"?>
            			</h3>
            		</div>
            	</div>
            	<div class="kt-portlet__body">
            
            		<!--begin::Section-->
            		<div class="kt-section">
            			
            			<div class="kt-section__content">
            				<table class="table table-sm table-head-bg-brand">
            					<thead class="thead-inverse">
            						<tr>
            							<th width="1%" nowrap><strong>Д/д</strong></th>
            							<th><strong>Төлөв</strong></th>
            							<th class="text-center"><strong>Бичгийн тоо</strong></th>
        							</tr>
            					</thead>
            					<tbody>
            					<?php 
            					   $par_id=0;
            					   $j=0;
            					   $j1=0;
            					   foreach ($_letterStatusList as $row){
            					       $statusObj=\Office\LetterStatusClass::getInstance($row);
            					       $num=isset($_mainList['LetterStat'.$statusObj->StatusID]) && $_mainList['LetterStat'.$statusObj->StatusID]!=""?$_mainList['LetterStat'.$statusObj->StatusID]:0;
            					       if($statusObj->StatusParentID<1){
            					           $j++;
            					           $j1=0;
            					       }elseif($par_id!=$statusObj->StatusParentID){
            					           $par_id=$statusObj->StatusParentID;
            					           $j1++;
            					       }elseif($par_id==$statusObj->StatusParentID){
            					           $j1++;
            					       }
    					       ?>
            						<tr>
            							<td class="text-right"><?=$j.($j1>0?".".$j1.".":".");?></td>
            							<td><?=$statusObj->StatusTitle?></td>
            							<td class="text-center"><span class="kt-badge  kt-badge--<?=$statusObj->StatusClass;?> kt-badge--inline kt-badge--pill"><?=$num;?></span></td>
            						</tr>
        						<?php }?>
            						<tr>
            							<td class="text-right"></td>
            							<td><strong>Нийт</strong></td>
            							<td class="text-center"><strong><?=$_allCount;?></strong></td>
            						</tr>
            					</tbody>
            				</table>
            			</div>
            		</div>
            
            		<!--end::Section-->
            	</div>
            </div>
		</div>
<?php 
$_petitionStatusList=\Office\PetitionStatusClass::getInstance()->getRowList(['orderby'=>'StatusOrder']);

$select=array();

$select[]=" SUM(IF(T.PetitionIsDecided=0,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_CONTROL;
$select[]=" SUM(IF(T.PetitionIsDecided=0 and T.PetitionControlEndDate>='".$now->format("Y-m-d")."',1,0)) as PetitionStat".\Office\PetitionClass::PETITION_CONTROL_IN_TIME;
$select[]=" SUM(IF(T.PetitionIsDecided=0 and T.PetitionControlEndDate<'".$now->format("Y-m-d")."',1,0)) as PetitionStat".\Office\PetitionClass::PETITION_CONTROL_LATE_TIME;
$select[]=" SUM(IF(T.PetitionIsDecided=1 and T.PetitionDecidedDateLate=0,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_DECIDED_INTIME;
$select[]=" SUM(IF(T.PetitionIsDecided=1 and T.PetitionDecidedDateLate>0,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_DECIDED_LATE;
$select[]=" SUM(IF(T.PetitionIsDecided=1,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_DECIDED;
$select[]=" SUM(IF(T.PetitionIsComplete=1,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_CLOSED;
$select[]=" SUM(IF(T.PetitionIsDecided=1 and T.PetitionIsComplete=0,1,0)) as PetitionStat".\Office\PetitionClass::PETITION_DECIDED_NOTCLOSED;

$_priv_org=\Office\Permission::getPriv(\Office\PrivClass::PRIV_PETITION_MY_ORG);
$_priv_dep=\Office\Permission::getPriv(\Office\PrivClass::PRIV_PETITION_MY_DEP);
$_priv_dep_all=\Office\Permission::getPriv(\Office\PrivClass::PRIV_PETITION_MY_DEP_ALL);
$_priv_dep_low=\Office\Permission::getPriv(\Office\PrivClass::PRIV_PETITION_MY_DEP_LOW);

$_priv=0;
if($_priv_org){
    $_priv=\Office\PrivClass::PRIV_PETITION_MY_ORG;
}elseif($_priv_dep_all){
    $_priv=\Office\PrivClass::PRIV_PETITION_MY_DEP_ALL;
}elseif($_priv_dep_low){
    $_priv=\Office\PrivClass::PRIV_PETITION_MY_DEP_LOW;
}elseif($_priv_dep){
    $_priv=\Office\PrivClass::PRIV_PETITION_MY_DEP;
}
$search=array();
switch ($_priv){
    case \Office\PrivClass::PRIV_PETITION_MY_ORG:
        break;
    case \Office\PrivClass::PRIV_PETITION_MY_DEP_ALL:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["petition_lastshiftdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_PETITION_MY_DEP_LOW:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["petition_lastshiftdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_PETITION_MY_DEP:
        $search=array_merge($search,["petition_lastshiftdepartmentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        break;
    default:
        $search=array_merge($search,["petition_lastshiftpersonid"=>$_SESSION[SESSSYSINFO]->PersonID]);
        break;
}


$petitionObj=\Office\PetitionClass::getInstance();
$_resultList=$petitionObj->getRowList(array_merge($search,["_select"=>$select,"petition_officeid"=>$_officeid]));
$_allCount=$petitionObj->getRowCount(array_merge($search,["_select"=>$select,"petition_officeid"=>$_officeid]));
$_mainList=isset($_resultList[0])?$_resultList[0]:array();
?>
		<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
			<div class="kt-portlet">
            	<div class="kt-portlet__head">
            		<div class="kt-portlet__head-label">
            			<h3 class="kt-portlet__head-title">
            				<?=$_priv<1?"Надад ирсэн өргөдөл, гомдол":"Өргөдөл, гомдол"?>
            			</h3>
            		</div>
            	</div>
            	<div class="kt-portlet__body">
            		<!--begin::Section-->
            		<div class="kt-section">
            			
            			<div class="kt-section__content">
            				<table class="table table-sm table-head-bg-brand">
            					<thead class="thead-inverse">
            						<tr>
            							<th width="1%" nowrap><strong>Д/д</strong></th>
            							<th><strong>Төлөв</strong></th>
            							<th class="text-center"><strong>Бичгийн тоо</strong></th>
        							</tr>
            					</thead>
            					<tbody>
            						
            					<?php 
            					   $par_id=0;
            					   $j=0;
            					   $j1=0;
            					   foreach ($_petitionStatusList as $row){
            					       $statusObj=\Office\PetitionStatusClass::getInstance($row);
            					       $num=isset($_mainList['PetitionStat'.$statusObj->StatusID]) && $_mainList['PetitionStat'.$statusObj->StatusID]!=""?$_mainList['PetitionStat'.$statusObj->StatusID]:0;
            					       if($statusObj->StatusParentID<1){
            					           $j++;
            					           $j1=0;
            					       }elseif($par_id!=$statusObj->StatusParentID){
            					           $par_id=$statusObj->StatusParentID;
            					           $j1++;
            					       }elseif($par_id==$statusObj->StatusParentID){
            					           $j1++;
            					       }
    					       ?>
            						<tr>
            							<td class="text-right"><?=$j.($j1>0?".".$j1.".":".");?></td>
            							<td><?=$statusObj->StatusTitle?></td>
            							<td class="text-center"><span class="kt-badge  kt-badge--<?=$statusObj->StatusClass;?> kt-badge--inline kt-badge--pill"><?=$num;?></span></td>
            						</tr>
        						<?php }?>
            						<tr>
            							<td class="text-right"></td>
            							<td><strong>Нийт</strong></td>
            							<td class="text-center"><strong><?=$_allCount;?></strong></td>
            						</tr>
            					</tbody>
            				</table>
            			</div>
            		</div>
            
            		<!--end::Section-->
            	</div>
            </div>
		</div>		
	</div>
<?php 
$_sentStatusList=\Office\SentLetterStatusClass::getInstance()->getRowList(['orderby'=>'StatusOrder']);

$select=array();
$select[]=" SUM(IF(T.SentLetterIsReturn=0,1,0)) as SentLetterStat".\Office\SentLetterClass::SENTLETTER_NORETURN;
$select[]=" SUM(IF(T.SentLetterIsReturn=1,1,0)) as SentLetterStat".\Office\SentLetterClass::SENTLETTER_RETURN_ALL;
$select[]=" SUM(IF(T.SentLetterIsReturn=1 and T.SentLetterReturnFinish=0 and T.SentLetterReturnDate>='".$now->format("Y-m-d")."',1,0)) as SentLetterStat".\Office\SentLetterClass::SENTLETTER_RETURN_NO_INTIME;
$select[]=" SUM(IF(T.SentLetterIsReturn=1 and T.SentLetterReturnFinish=0 and T.SentLetterReturnDate<'".$now->format("Y-m-d")."',1,0)) as SentLetterStat".\Office\SentLetterClass::SENTLETTER_RETURN_NO_LATE;
$select[]=" SUM(IF(T.SentLetterIsReturn=1 and T.SentLetterReturnFinish=1,1,0)) as SentLetterStat".\Office\SentLetterClass::SENTLETTER_RETURNED;

$_priv_org=\Office\Permission::getPriv(\Office\PrivClass::PRIV_SENTLETTER_MY_ORG);
$_priv_dep=\Office\Permission::getPriv(\Office\PrivClass::PRIV_SENTLETTER_MY_DEP);
$_priv_dep_all=\Office\Permission::getPriv(\Office\PrivClass::PRIV_SENTLETTER_MY_DEP_ALL);
$_priv_dep_low=\Office\Permission::getPriv(\Office\PrivClass::PRIV_SENTLETTER_MY_DEP_LOW);

$_priv=0;
if($_priv_org){
    $_priv=\Office\PrivClass::PRIV_SENTLETTER_MY_ORG;
}elseif($_priv_dep_all){
    $_priv=\Office\PrivClass::PRIV_SENTLETTER_MY_DEP_ALL;
}elseif($_priv_dep_low){
    $_priv=\Office\PrivClass::PRIV_SENTLETTER_MY_DEP_LOW;
}elseif($_priv_dep){
    $_priv=\Office\PrivClass::PRIV_SENTLETTER_MY_DEP;
}
$search=array();
switch ($_priv){
    case \Office\PrivClass::PRIV_SENTLETTER_MY_ORG:
        break;
    case \Office\PrivClass::PRIV_SENTLETTER_MY_DEP_ALL:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["sentletter_fromdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_SENTLETTER_MY_DEP_LOW:
        $_list=\Humanres\DepartmentClass::getInstance()->getRowList(["_getparams"=>array("DepartmentID"),"department_parentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        $dep_ids=isset($_list['DepartmentID'])?array_merge([$_SESSION[SESSSYSINFO]->DepartmentID],$_list['DepartmentID']):[$_SESSION[SESSSYSINFO]->DepartmentID];
        $search=array_merge($search,["sentletter_fromdepartmentid"=>$dep_ids]);
        break;
    case \Office\PrivClass::PRIV_SENTLETTER_MY_DEP:
        $search=array_merge($search,["sentletter_fromdepartmentid"=>$_SESSION[SESSSYSINFO]->DepartmentID]);
        break;
    default:
        $search=array_merge($search,["sentletter_frompersonid"=>$_SESSION[SESSSYSINFO]->PersonID]);
        break;
}

$sentLetterObj=\Office\SentLetterClass::getInstance();
$_resultList=$sentLetterObj->getRowList(array_merge($search,["_select"=>$select,"sentletter_officeid"=>$_officeid]));
$_allCount=$sentLetterObj->getRowCount(array_merge($search,["_select"=>$select,"sentletter_officeid"=>$_officeid]));
$_mainList=isset($_resultList[0])?$_resultList[0]:array();
?>
	<div class="row">
		<div class="col-lg-6 col-xl-6 order-lg-1 order-xl-1">
            <div class="kt-portlet">
            	<div class="kt-portlet__head">
            		<div class="kt-portlet__head-label">
            			<h3 class="kt-portlet__head-title">
            				
            				<?=$_priv<1?"Миний явуулсан бичиг":"Явуулсан бичиг"?>
            			</h3>
            		</div>
            	</div>
            	<div class="kt-portlet__body">
            
            		<!--begin::Section-->
            		<div class="kt-section">
            			
            			<div class="kt-section__content">
            				<table class="table table-sm table-head-bg-brand">
            					<thead class="thead-inverse">
            						<tr>
            							<th width="1%" nowrap><strong>Д/д</strong></th>
            							<th><strong>Төлөв</strong></th>
            							<th class="text-center"><strong>Бичгийн тоо</strong></th>
        							</tr>
            					</thead>
            					<tbody>
            					<?php 
            					   $par_id=0;
            					   $j=0;
            					   $j1=0;
            					   foreach ($_sentStatusList as $row){
            					       $statusObj=\Office\SentLetterStatusClass::getInstance($row);
            					       $num=isset($_mainList['SentLetterStat'.$statusObj->StatusID]) && $_mainList['SentLetterStat'.$statusObj->StatusID]!=""?$_mainList['SentLetterStat'.$statusObj->StatusID]:0;
            					       if($statusObj->StatusParentID<1){
            					           $j++;
            					           $j1=0;
            					       }elseif($par_id!=$statusObj->StatusParentID){
            					           $par_id=$statusObj->StatusParentID;
            					           $j1++;
            					       }elseif($par_id==$statusObj->StatusParentID){
            					           $j1++;
            					       }
    					       ?>
            						<tr>
            							<td class="text-right"><?=$j.($j1>0?".".$j1.".":".");?></td>
            							<td><?=$statusObj->StatusTitle?></td>
            							<td class="text-center"><span class="kt-badge  kt-badge--<?=$statusObj->StatusClass;?> kt-badge--inline kt-badge--pill"><?=$num;?></span></td>
            						</tr>
        						<?php }?>
            						<tr>
            							<td class="text-right"></td>
            							<td><strong>Нийт</strong></td>
            							<td class="text-center"><strong><?=$_allCount;?></strong></td>
            						</tr>
            					</tbody>
            				</table>
            			</div>
            		</div>
            
            		<!--end::Section-->
            	</div>
            </div>
		</div>
	</div>

<!-- end:: Content -->