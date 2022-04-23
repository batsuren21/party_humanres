<?php 
    if(!isset($petitionObj)){
        $_id=isset($_POST['id'])?$_POST['id']:0;
        $petitionObj=\Office\PetitionClass::getInstance()->getRow(['petition_id'=>$_id]);
        echo $_id;
    }
    $step=[
        1=>["id"=>1,"title"=>"Үндсэн бүртгэл","isdone"=>true],
        2=>["id"=>2,"title"=>"Удирдлагын заалт","isdone"=>($petitionObj->PetitionAdminPersonID>0)],
        3=>["id"=>3,"title"=>"Шилжүүлэлт","isdone"=>($petitionObj->PetitionIsDecided || $petitionObj->PetitionShiftCount>0)],
        3=>["id"=>3,"title"=>"Шилжүүлэлт","isdone"=>($petitionObj->PetitionIsDecided || $petitionObj->PetitionShiftCount>0)],
        4=>["id"=>4,"title"=>"Шийдвэрлэлт","isdone"=>($petitionObj->PetitionIsDecided)],
        5=>["id"=>5,"title"=>"Хаалт","isdone"=>($petitionObj->PetitionIsComplete)]
    ];
   
    
?>
<div class="kt-grid kt-wizard-v3 kt-wizard-v3--white" data-ktwizard-state="step-first">
	<div class="kt-grid__item">
		<!--begin: Form Wizard Nav -->
		<div class="kt-wizard-v3__nav">
			<div class="kt-wizard-v3__nav-items">
				<?php foreach($step as $st){?>
				<div class="kt-wizard-v3__nav-item" <?php if($st['isdone']){?>data-ktwizard-state="current"<?php }?>>
					<div class="kt-wizard-v3__nav-body  pt-0 pb-0">
						<div class="kt-wizard-v3__nav-label">
							<span><?=$st['id']?></span> <?=$st['title'];?> <?php if($st['isdone']){?><i class="flaticon2-check-mark"></i><?php }?>
						</div>
						<div class="kt-wizard-v3__nav-bar"></div>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col text-center">
        
    </div>
</div>