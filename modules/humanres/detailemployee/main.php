<?php 
    $_priv_reg=\Office\Permission::getPriv(\Office\PrivClass::PRIV_HUMANRES_REG);
    
    $_id=isset($_POST['id'])?$_POST['id']:0;
    $personObj=\Humanres\PersonClass::getInstance()->getRow(["person_get_table"=>1,'person_id'=>$_id]);
    $_is_personself=$personObj->PersonID==$_SESSION[SESSSYSINFO]->PersonID;
?>
<div class="modal-header ">
    <h5 class="modal-title"><i class="flaticon2-crisp-icons"></i> Албан хаагч</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="kt-portlet__body ">
    	<div class="row">
    		<div class="col-lg-3 border_right_gray" ><?php require_once 'menu.php';?></div>
    		<div class="col-lg-9">
    			<div class="row">
    				<div class="col-lg-12">
        			<?php 
            			if(!isset($_GET['_spage']) || $_GET['_spage']==""){
            			     require_once 'main_center.php';
            			}elseif(isset($_GET['_spage']) && file_exists(DRF.'/modules/humanres/detailemployee/page_'.$_GET['_spage'].".php")){
            			    require_once DRF.'/modules/humanres/detailemployee/page_'.$_GET['_spage'].".php";
            			}
                    ?>
                	</div>
                </div>
    		</div>
    	</div>
		
	</div>	
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-outline-brand " data-dismiss="modal">Хаах</button>
</div>