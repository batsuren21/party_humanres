<?php
if($personObj->PersonID!=""){
    
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_salaryPercentList=$refObj->getRowList(["orderby"=>"RefPercentOrder"],\Humanres\ReferenceClass::TBL_SALARY_PERCENT);
?>

<div class="kt-widget kt-widget--user-profile-4">
	<div class="kt-widget__head">
		<div class="kt-widget__media">
			<img class="kt-widget__img kt-hidden-" src="<?=$personObj->getImage()?>" alt="image">
		</div>
		
	</div>
</div>
<div class="kt-widget kt-widget--user-profile-1">
	<div class="kt-widget__body">
		<div class="kt-widget__content">
		<?php if($_priv_reg){?>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Мэдээлэл баяжуулах эсэх:</span>
				<span class="kt-widget__data font-12 color-dark">
					<span class="kt-switch kt-switch--icon">
					<label>
						<input type="checkbox" id="changeEmployeeState" <?=$personObj->PersonIsEditable?"checked":""?>>
						<span></span>
					</label>
				</span>
				</span>
			</div>
			<?php }?>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Регистрийн дугаар:</span>
				<span class="kt-widget__data font-12 color-dark"><?=$personObj->PersonRegisterNumber?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Овог, нэр:</span>
				<span class="kt-widget__data font-12 color-dark"><?=$personObj->PersonLFName;?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Нэгж:</span>
				<span class="kt-widget__data font-12 color-dark"><?=$personObj->DepartmentFullName?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Үндсэн үүр:</span>
				<span class="kt-widget__data font-12 color-dark"><?=$personObj->PositionFullName?></span>
			</div>
			
			
		</div>
	</div>
</div>
<?php }?>
<ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3" role="tablist">
	<li class="kt-nav__item <?=!isset($_GET['_spage']) || $_GET['_spage']==""?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-architecture-and-city"></i>
			<span class="kt-nav__link-text">Үндсэн бүртгэл</span>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="employee"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=employee" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Үүрийн бүртгэл</span>
			<?php if($personObj->PersonCountEmployee>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountEmployee?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountEmployee?></span>
			</span>
			<?php }?>
		</a>
	</li>
	
	
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="education"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=education" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Боловсролын байдал</span>
			<?php if($personObj->PersonCountEducation>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountEducation?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountEducation?></span>
			</span>
			<?php }?>
		</a>
	</li>
	
	
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="edurank"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=edurank" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Эрдмийн зэрэг, цол</span>
			<?php if($personObj->PersonCountDegree>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountDegree?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountDegree?></span>
			</span>
			<?php }?>
		</a>
	</li>
	
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="award"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=award" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Шагналын байдал</span>
			<?php if($personObj->PersonCountAward>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountAward?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountAward?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="job"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=job" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Хөдөлмөр эрхлэлт</span>
			<?php if($personObj->PersonCountJob>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountJob?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountJob?></span>
			</span>
			<?php }?>
		</a>
	</li>
	
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="family"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=family" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Гэр бүлийн байдлын бүртгэл</span>
			<?php if($personObj->PersonCountFamily>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountFamily?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountFamily?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="relation"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanreselect/detailemployee/main?_spage=relation" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Садан төрлийн байдлын бүртгэл</span>
			<?php if($personObj->PersonCountRelate>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountRelate?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountRelate?></span>
			</span>
			<?php }?>
		</a>
	</li>
	
		
</ul>