<?php
$_priv_access=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_ACCESS);
$_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_ADMIN_USER_PRIV);

if($_priv_access && $_priv_reg){
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $_paramid=isset($_POST['paramid'])?$_POST['paramid']:0;
    
    $userObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
    $_sysObj=\Office\PackageSystemListClass::getInstance()->getRow(['packsyslist_id'=>$_paramid]);
    
    $_icon="flaticon2-edit";
    $_title=$_sysObj->SystemName." :: Эрх солих";
?>
<form class="kt-form kt-form--label-right" id="letterForm" action="<?=RF;?>/process/admin/edituser" enctype="multipart/form-data">
<div class="modal-header">
    <h5 class="modal-title"><i class="<?=$_icon;?>"></i> <?=$_title;?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
<?php 
    $tmpObj = new \System\SystemPrivClass();
    $tmpObj->searchTerm=["localid"=>$_paramid,"personid"=>$userObj->PersonID];
    $moduleList=$tmpObj->getRowList();
?>    
		<table class="table table-striped font-12">
			<tbody>
				<?php 
				  $j=1;
				  $_classname="";
				  foreach ($moduleList as $row){
				      $priv= $row['Priv']!=null?$row['Priv']:$row['ModulePrivDefault'];
				      if($_classname!=$row['ModuleClassName']){
				          $_classname=$row['ModuleClassName'];
				?>
				<tr>
					<td colspan="3"><strong><?=$row['ModuleClassName'];?></strong></td>
				</tr>
				<?php }?>
				<tr>
					<td class="color-gray" width="1%" nowrap><?=$j;?>.</td>
					<td>
						<?=$row['ModuleName']?>
					</td>
					<td class="color-gray" width="1%" nowrap>
    					<div class="kt-radio-inline">
    						<label class="kt-radio kt-radio--solid">
        						<input type="radio" name="priv<?=$row['ModuleID']?>" <?=$priv?"checked":""?> class="privchange" data-id="<?=$row['ModuleID']?>" value="1"> Тийм
        						<span></span>
        					</label>
        					<label class="kt-radio kt-radio--solid">
        						<input type="radio" name="priv<?=$row['ModuleID']?>" <?=!$priv?"checked":""?> class="privchange" data-id="<?=$row['ModuleID']?>" value="0"> Үгүй
        						<span></span>
        					</label>
        				</div>
						
					</td>
					
				</tr>
				<?php  $j++; }?>
			</tbody>
		</table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>
</form>
<?php }else{?>
<div class="modal-header">
    <h5 class="modal-title">Бүртгэл</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body">
       <?=\Office\System::getPage("error/nopriv",["title"=>"","descr"=>"Танд бүртгэх эрх байхгүй байна"]);?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>
<?php }?>
