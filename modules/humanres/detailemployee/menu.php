<?php
if($personObj->PersonID!=""){
    $activeJobObj=\Humanres\PersonJobClass::getInstance()->getRow([
        "job_personid"=>$personObj->PersonID,
        "job_isnow"=>1,
        "rowstart"=>0,
        "rowlength"=>1,
    ]);
    $PersonWorkYearAll=$personObj->PersonWorkYearAll;
    $PersonWorkMonthAll=$personObj->PersonWorkMonthAll;
    $PersonWorkDayAll=$personObj->PersonWorkDayAll;
    
    $PersonWorkYearGov=$personObj->PersonWorkYearGov;
    $PersonWorkMonthGov=$personObj->PersonWorkMonthGov;
    $PersonWorkDayGov=$personObj->PersonWorkDayGov;
    
    $PersonWorkYearMilitary=$personObj->PersonWorkYearMilitary;
    $PersonWorkMonthMilitary=$personObj->PersonWorkMonthMilitary;
    $PersonWorkDayMilitary=$personObj->PersonWorkDayMilitary;
    
    $PersonWorkYearCompany=$personObj->PersonWorkYearCompany;
    $PersonWorkMonthCompany=$personObj->PersonWorkMonthCompany;
    $PersonWorkDayCompany=$personObj->PersonWorkDayCompany;
    
    $PersonWorkYearOrgan=$personObj->PersonWorkYearOrgan;
    $PersonWorkMonthOrgan=$personObj->PersonWorkMonthOrgan;
    $PersonWorkDayOrgan=$personObj->PersonWorkDayOrgan;
    
    if($activeJobObj->isExist()){
        $now = new DateTime();
        $_time=\System\Util::getDaysDiff($activeJobObj->JobDateStart,$now->format("Y-m-d"));
        $tmpDay=$personObj->PersonWorkDayAll==""?0:($personObj->PersonWorkDayAll+$_time['day'])%30;
        
        $tmpMonth=$personObj->PersonWorkMonthAll==""?$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30):$personObj->PersonWorkMonthAll+$_time['month']+floor(($personObj->PersonWorkDayAll+$_time['day'])/30);
        
        $PersonWorkYearAll=$personObj->PersonWorkYearAll==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearAll+$_time['year']+floor($tmpMonth/12);
        $PersonWorkMonthAll=($tmpMonth%12);
        $PersonWorkDayAll=$tmpDay;
        if($activeJobObj->JobOrganID==1){
            $tmpDay=$personObj->PersonWorkDayGov==""?0:($personObj->PersonWorkDayGov+$_time['day'])%30;
            $tmpMonth=$personObj->PersonWorkMonthGov==""?$_time['month']+floor(($personObj->PersonWorkDayGov+$_time['day'])/30):$personObj->PersonWorkMonthGov+$_time['month']+floor(($personObj->PersonWorkDayGov+$_time['day'])/30);
            $PersonWorkYearGov=$personObj->PersonWorkYearGov==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearGov+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthGov=$tmpMonth%12;
            $PersonWorkDayGov=$tmpDay;
        }
        if($activeJobObj->JobOrganTypeID==1){
            $tmpDay=$personObj->PersonWorkDayMilitary==""?0:(($personObj->PersonWorkDayMilitary+$_time['day'])%30);
            $tmpMonth=$personObj->PersonWorkMonthMilitary==""?$_time['month']+floor(($personObj->PersonWorkDayMilitary+$_time['day'])/30):$personObj->PersonWorkMonthMilitary+$_time['month']+floor(($personObj->PersonWorkDayMilitary+$_time['day'])/30);
            $PersonWorkYearMilitary=$personObj->PersonWorkYearMilitary==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearMilitary+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthMilitary=$tmpMonth%12;
            $PersonWorkDayMilitary=$tmpDay;
        }
        if($activeJobObj->JobOrganID>1){
            $tmpDay=$personObj->PersonWorkDayCompany==""?0:(($personObj->PersonWorkDayCompany+$_time['day'])%30);
            $tmpMonth=$personObj->PersonWorkMonthCompany==""?$_time['month']+floor(($personObj->PersonWorkDayCompany+$_time['day'])/30):$personObj->PersonWorkMonthCompany+$_time['month']+floor(($personObj->PersonWorkDayCompany+$_time['day'])/30);
            $PersonWorkYearCompany=$personObj->PersonWorkYearCompany==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearCompany+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthCompany=$tmpMonth%12;
            $PersonWorkDayCompany=$tmpDay;
        }
        
        if($activeJobObj->JobOrganSubID==1){
            $tmpDay=$personObj->PersonWorkDayOrgan==""?0:(($personObj->PersonWorkDayOrgan+$_time['day'])%30);
            $tmpMonth=$personObj->PersonWorkMonthOrgan==""?$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30):$personObj->PersonWorkMonthOrgan+$_time['month']+floor(($personObj->PersonWorkDayOrgan+$_time['day'])/30);
            $PersonWorkYearOrgan=$personObj->PersonWorkYearOrgan==""?$_time['year']+floor($tmpMonth/12):$personObj->PersonWorkYearOrgan+$_time['year']+floor($tmpMonth/12);
            $PersonWorkMonthOrgan=$tmpMonth%12;
            $PersonWorkDayOrgan=$tmpDay;
        }
    }
    $refObj=\Humanres\ReferenceClass::getInstance();
    $_salaryPercentList=$refObj->getRowList(["orderby"=>"RefPercentOrder"],\Humanres\ReferenceClass::TBL_SALARY_PERCENT);
?>

<div class="kt-widget kt-widget--user-profile-4">
	<div class="kt-widget__head">
		<div class="kt-widget__media">
			<img class="kt-widget__img kt-hidden-" src="<?=$personObj->getImage()?>" alt="image">
		</div>
		<div class="text-center mt-4">
			<a href="javascript:;" data-target="#detailPrintModalHumanres" 
			data-toggle="modal" 
			data-url="<?=RF?>/m/humanres/list/json?id=<?=$personObj->PersonID?>" 
			data-print="humanres"
			data-id="<?=$personObj->PersonID?>" 
			class="btn btn-outline-success btn-sm ">
				<i class="flaticon2-print"></i> Лавлагаа хэвлэх
			</a>
		</div>
	</div>
</div>
<div class="kt-widget kt-widget--user-profile-1">
	<div class="kt-widget__body">
		<div class="kt-widget__content">
		<?php if($_priv_reg){?>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Албан хаагч мэдээлэл баяжуулах эсэх:</span>
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
				<span class="kt-widget__label color-gray">Албан тушаал:</span>
				<span class="kt-widget__data font-12 color-dark"><?=$personObj->PositionFullName?></span>
			</div>
			<div class="kt-widget__info font-12 mt-2">
				<span class="kt-widget__label color-gray">Улсад ажилласан жил:</span>
				<span class="kt-widget__data font-12 color-dark"><?=\System\Util::formatDaysMonth($PersonWorkYearAll,$PersonWorkMonthAll,$PersonWorkDayAll)?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Төрд ажилласан жил:</span>
				<span class="kt-widget__data font-12 color-dark"><?=\System\Util::formatDaysMonth($PersonWorkYearGov,$PersonWorkMonthGov,$PersonWorkDayGov);?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Авлигатай тэмцэх газарт ажилласан жил:</span>
				<span class="kt-widget__data font-12 color-dark"><?=\System\Util::formatDaysMonth($PersonWorkYearOrgan,$PersonWorkMonthOrgan,$PersonWorkDayOrgan);?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Цэргийн албанд ажилласан жил:</span>
				<span class="kt-widget__data font-12 color-dark"><?=\System\Util::formatDaysMonth($PersonWorkYearMilitary,$PersonWorkMonthMilitary,$PersonWorkDayMilitary);?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Аж ахуй нэгжид ажилласан жил:</span>
				<span class="kt-widget__data font-12 color-dark"><?=\System\Util::formatDaysMonth($PersonWorkYearCompany,$PersonWorkMonthCompany,$PersonWorkDayCompany);?></span>
			</div>
			<div class="kt-widget__info font-12">
				<span class="kt-widget__label color-gray">Төрийн алба хаасан хугацааны нэмэгдэл:</span>
				<span class="kt-widget__data font-12 color-dark">
				<?php 
				    $_allmonth=$PersonWorkYearGov*12 + $PersonWorkMonthGov;
				    if($_allmonth>0){
				        foreach ($_salaryPercentList as $rs){
				            $refSalaryObj=\Humanres\ReferenceClass::getInstance($rs);
				            if($_allmonth>= $refSalaryObj->RefPercentStart && $_allmonth<= $refSalaryObj->RefPercentEnd){
				                echo $refSalaryObj->RefPercent."%";
				            }
				        }
				    }else echo "-";
                ?>	
				</span>
			</div>
		</div>
	</div>
</div>
<?php }?>
<ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3" role="tablist">
	<li class="kt-nav__item <?=!isset($_GET['_spage']) || $_GET['_spage']==""?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-architecture-and-city"></i>
			<span class="kt-nav__link-text">Үндсэн бүртгэл</span>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="employee"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=employee" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Албан тушаалын бүртгэл</span>
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
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="soldier"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=soldier" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Цэргийн жинхэнэ алба хаасан эсэх бүртгэл</span>
			<?php if($personObj->PersonSoldierUpdateDate!=""){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><i class="flaticon2-check-mark"></i></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><i class="flaticon2-cross"></i></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="posrank"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=posrank" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Албан тушаалын зэрэг, дэв</span>
			<?php if($personObj->PersonCountPosRank>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountPosRank?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountPosRank?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="education"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=education" role="tab" data-id="<?=$personObj->PersonID?>">
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
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="study"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=study" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Сургалтад хамрагдсан байдал</span>
			<?php if($personObj->PersonCountStudy>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountStudy?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountStudy?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="language"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=language" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Гадаад хэлний мэдлэг</span>
			<?php if($personObj->PersonCountLanguage>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountLanguage?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountLanguage?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="edurank"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=edurank" role="tab" data-id="<?=$personObj->PersonID?>">
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
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=award" role="tab" data-id="<?=$personObj->PersonID?>">
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
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=job" role="tab" data-id="<?=$personObj->PersonID?>">
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
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="holiday"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=holiday" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Ээлжийн амралт</span>
			<?php if($personObj->PersonCountHoliday>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountHoliday?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountHoliday?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="bill"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=bill" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Ээлжийн амралтын тооцоо</span>
			<?php if($personObj->PersonCountBill>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountBill?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountBill?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="punishment"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=punishment" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Сахилгын шийтгэл</span>
			<?php if($personObj->PersonCountPunishment>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountPunishment?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountPunishment?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="trip"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=trip" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Томилолтын бүртгэл</span>
			<?php if($personObj->PersonCountTrip>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountTrip?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountTrip?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="salary"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=salary" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Цалин хөлсний бүрэлдэхүүн</span>
			<?php if($personObj->PersonCountSalary>0){?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--success"><?=$personObj->PersonCountSalary?></span>
			</span>
			<?php }else{?>
			<span class="kt-nav__link-badge">
				<span class="kt-badge kt-badge--warning"><?=$personObj->PersonCountSalary?></span>
			</span>
			<?php }?>
		</a>
	</li>
	<li class="kt-nav__item <?=isset($_GET['_spage']) && $_GET['_spage']=="family"?"active":"";?>">
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=family" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Албан хаагчийн гэр бүлийн байдлын бүртгэл</span>
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
		<a class="kt-nav__link detailsubpage" data-toggle="tab" href="javascript:;" data-url="<?=RF;?>/m/humanres/detailemployee/main?_spage=relation" role="tab" data-id="<?=$personObj->PersonID?>">
			<i class="kt-nav__link-icon flaticon2-calendar-5"></i>
			<span class="kt-nav__link-text">Албан хаагчийн төрөл садангийн байдлын бүртгэл</span>
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