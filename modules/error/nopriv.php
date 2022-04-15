<?php if(isset($_param['cont']) && $_param['cont']){?>
<div class="alert alert-danger" role="alert">
	<strong><?=isset($_param['title'])?$_param['title']:""?></strong> <?=isset($_param['descr'])?$_param['descr']:"Эрх байхгүй байна"?>
</div>
<?php }else{?>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<div class="alert alert-danger" role="alert">
    	<strong><?=isset($_param['title'])?$_param['title']:""?></strong> <?=isset($_param['descr'])?$_param['descr']:"Эрх байхгүй байна"?>
    </div>
</div>
<?php }?>