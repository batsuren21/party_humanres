<?php
namespace Humanres;
class HumanresStatisticClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    static $_time_list=[
        ['id'=>0,'title'=>"0-5жил /0-60 сар/","start"=>0,"end"=>60],
        ['id'=>1,'title'=>"5-10 жил /61-120 сар/","start"=>61,"end"=>120],
        ['id'=>2,'title'=>"11-15 жил /121-180 сар/","start"=>121,"end"=>180],
        ['id'=>3,'title'=>"16-20 жил /181-240 сар/","start"=>181,"end"=>240],
        ['id'=>4,'title'=>"21-25 жил /241-300 сар/","start"=>241,"end"=>300],
        ['id'=>5,'title'=>"26-30 жил /301-360 сар/","start"=>301,"end"=>360],
        ['id'=>6,'title'=>"31-35 жил /361-420 сар/","start"=>361,"end"=>420],
        ['id'=>7,'title'=>"36 болон түүнээс дээш жил /421 ба түүнээс дээш/","start"=>421,"end"=>3600]
    ];
    
    public function __construct() {
        $this->con=\Database::instance();
        parent::__construct();
    }
    public function __get($name){
        switch ($name){
            case "Error":
                return $this->_error;
                break;
            default :
                return isset($this->_data[$name])?$this->_data[$name]:"";
        }
    }

    public function __set($name, $value){
        if(isset($this->_data[$name])){
            $this->_data[$name]=$value;
        }else{
            $this->_data[$name]=$value;
        }
    }
    static function getList($list=[],$option=["field"=>""]){
        $result=[];
        if(count($list)<1) return $result;
        if(isset($option['field']) && $option['field']!=""){
            if(!isset($option['value'])){
                foreach ($list as $row){
                    if(isset($row[$option['field']])){
                        $result[$row[$option['field']]][]=$row;
                    }
                }
            }else{
                $value=is_array($option['value'])?$option['value']:[$option['value']];
                foreach ($list as $row){
                    if(isset($row[$option['field']]) && $row[$option['field']]==$value){
                        $result[$row[$option['field']]][]=$row;
                    }
                }
            }
        }
        return $result;
    }
    
    function getWorkDays($search=array(), $list=[],$type=1,$class=1){
        
        $now=new \DateTime();
        
        $_date=isset($search['humanres_dateend'])?$search['humanres_dateend']:$now->format("Y-m-d");
        
        $_sub_age="";
        if(isset($list['param_agelist'])){
            $_sub_age=", ( CASE";
            foreach ($list['param_agelist'] as $tmp){
                $_yearstart=$tmp['AgeStart'];
                $_yearend=$tmp['AgeEnd']+1;
                
                $start_date = date('Y-m-d', strtotime($_date. '-'.$_yearstart.' year'));
                $end_date = date('Y-m-d', strtotime($_date. '-'.$_yearend.' year'));
                $_sub_age.=" WHEN T.PersonBirthDate <= '".$start_date."' AND T.PersonBirthDate > '".$end_date."' THEN ".$tmp['AgeID'];
            }
            $_sub_age.=" END ) as PersonAge";
            
        }
        
        $str=array();
        $str[]="COUNT(DISTINCT T.PersonID) as PersonAllCount";
        $str[]="COUNT(DISTINCT IF(T.PersonGender = 1,T.PersonID, NULL)) as PersonGender1";
        $str[]="COUNT(DISTINCT IF(T.PersonGender = 0,T.PersonID, NULL)) as PersonGender0";
        if(isset($list['col_timelist'])){
            foreach ($list['col_timelist'] as $tmp){
                $str[]="COUNT(DISTINCT IF(T.PersonWorkMonth >= ".$tmp['start']." and T.PersonWorkMonth <= ".$tmp['end'].",T.PersonID, NULL)) as TimeList".$tmp['id'];
                $str[]="COUNT(DISTINCT IF(T.PersonWorkMonth >= ".$tmp['start']." and T.PersonWorkMonth <= ".$tmp['end']." and T.PersonGender = 1,T.PersonID, NULL)) as TimeList".$tmp['id']."_1";
                $str[]="COUNT(DISTINCT IF(T.PersonWorkMonth >= ".$tmp['start']." and T.PersonWorkMonth <= ".$tmp['end']." and T.PersonGender = 0,T.PersonID, NULL)) as TimeList".$tmp['id']."_0";
            }
        }
        $qry_sub="";
        if($class==1){
            //Улсад ажилласан жил
            $qry_sub=" from (";
            $qry_sub.=" select T.PersonID, T.PersonGender, T.EmployeeID, T.EmployeeDepartmentID, T.EmployeePositionID, IF(SUM(T.PersonWorkMonthAll) IS NULL,0,SUM(T.PersonWorkMonthAll)) as PersonWorkMonth,
                        IF(SUM(T.PersonWorkDayAll) IS NULL,0,SUM(T.PersonWorkDayAll)) as PersonWorkDay".$_sub_age;
            $qry_sub.=" from (";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, (TF.PersonWorkYearAll*12+ TF.PersonWorkMonthAll) as PersonWorkMonthAll, TF.PersonWorkDayAll";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" UNION ALL";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') AS WorkMonth,
                        TIMESTAMPDIFF(DAY, TF_J.JobDateStart+ INTERVAL TIMESTAMPDIFF(YEAR, TF_J.JobDateStart,'".$_date."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') - TIMESTAMPDIFF(YEAR,TF_J.JobDateStart,'".$_date."')*12) MONTH,'".$_date."') AS WorkDays
                    ";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=1";
            $qry_sub.=" ) T";
            $qry_sub.=" group by T.PersonID";
            $qry_sub.=" ) T";
        }elseif($class==2){
            //Төрд ажилласан жил
            $qry_sub=" from (";
            $qry_sub.=" select T.PersonID, T.PersonGender, T.EmployeeID, T.EmployeeDepartmentID, T.EmployeePositionID, IF(SUM(T.PersonWorkMonthGov) IS NULL,0,SUM(T.PersonWorkMonthGov)) as PersonWorkMonth,
                        IF(SUM(T.PersonWorkDayGov) IS NULL,0,SUM(T.PersonWorkDayGov)) as PersonWorkDay".$_sub_age;
            $qry_sub.=" from (";
                $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, (TF.PersonWorkYearGov*12+ TF.PersonWorkMonthGov) as PersonWorkMonthGov, TF.PersonWorkDayGov";
                $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
                $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
                $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry_sub.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry_sub.=" ".$_where_cond;
                    }
                }
                $qry_sub.=" UNION ALL";
                $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') AS WorkMonth,
                            TIMESTAMPDIFF(DAY, TF_J.JobDateStart+ INTERVAL TIMESTAMPDIFF(YEAR, TF_J.JobDateStart,'".$_date."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') - TIMESTAMPDIFF(YEAR,TF_J.JobDateStart,'".$_date."')*12) MONTH,'".$_date."') AS WorkDays
                        ";
                $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
                $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
                $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry_sub.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry_sub.=" ".$_where_cond;
                    }
                }
                $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=1 and TF_J.JobOrganID=1";
            $qry_sub.=" ) T";
            $qry_sub.=" group by T.PersonID";
            $qry_sub.=" ) T";
        }elseif($class==3){
            //Аж ахуйн нэгжид ажилласан жил
            $qry_sub=" from (";
            $qry_sub.=" select T.PersonID, T.PersonGender, T.EmployeeID, T.EmployeeDepartmentID, T.EmployeePositionID, IF(SUM(T.PersonWorkMonthCompany) IS NULL,0,SUM(T.PersonWorkMonthCompany)) as PersonWorkMonth,
                        IF(SUM(T.PersonWorkDayCompany) IS NULL,0,SUM(T.PersonWorkDayCompany)) as PersonWorkDay".$_sub_age;
            $qry_sub.=" from (";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, (TF.PersonWorkYearCompany*12+ TF.PersonWorkMonthCompany) as PersonWorkMonthCompany, TF.PersonWorkDayCompany";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" UNION ALL";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') AS WorkMonth,
                            TIMESTAMPDIFF(DAY, TF_J.JobDateStart+ INTERVAL TIMESTAMPDIFF(YEAR, TF_J.JobDateStart,'".$_date."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') - TIMESTAMPDIFF(YEAR,TF_J.JobDateStart,'".$_date."')*12) MONTH,'".$_date."') AS WorkDays
                        ";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=1 and TF_J.JobOrganID>1 AND TF_J.JobOrganID!=6";
            $qry_sub.=" ) T";
            $qry_sub.=" group by T.PersonID";
            $qry_sub.=" ) T";
        }elseif($class==4){
            //АТГ-т ажилласан жил
            
            $qry_sub=" from (";
            $qry_sub.=" select T.PersonID, T.PersonGender, T.EmployeeID, T.EmployeeDepartmentID, T.EmployeePositionID, 
                        IF(SUM(T.PersonWorkMonthOrgan) IS NULL,0,SUM(T.PersonWorkMonthOrgan)) as PersonWorkMonth,
                        IF(SUM(T.PersonWorkDayOrgan) IS NULL,0,SUM(T.PersonWorkDayOrgan)) as PersonWorkDay".$_sub_age;
            $qry_sub.=" from (";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, (TF.PersonWorkYearOrgan*12+ TF.PersonWorkMonthOrgan) as PersonWorkMonthOrgan, TF.PersonWorkDayOrgan";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" UNION ALL";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') AS WorkMonth,
                            TIMESTAMPDIFF(DAY, TF_J.JobDateStart+ INTERVAL TIMESTAMPDIFF(YEAR, TF_J.JobDateStart,'".$_date."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') - TIMESTAMPDIFF(YEAR,TF_J.JobDateStart,'".$_date."')*12) MONTH,'".$_date."') AS WorkDays
                        ";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=1 and TF_J.JobOrganSubID=1";
            $qry_sub.=" ) T";
            $qry_sub.=" group by T.PersonID";
            $qry_sub.=" ) T";
        }
        if($qry_sub!=""){
            if($type==1){
                $qry=" select 'ALL' as RefTypeClass, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" UNION ALL";
                $qry.=" select TP_TYPE.RefTypeClass, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP_TYPE.RefTypeClass";
            }elseif($type==2){
                $qry=" select CONCAT('ALL_',TP.PositionClassID) as RefTypeClass, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP.PositionClassID";
                $qry.=" UNION ALL";
                $qry.=" select CONCAT('ROW_',TP_TYPE.RefTypeClass,'_',TP.PositionClassID) as RefTypeClass, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                if(isset($search['position_classid']) && $search['position_classid']>0){
                    $qry.=" AND TP.PositionClassID=".$search['position_classid'];
                }
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP.PositionClassID, TP_TYPE.RefTypeClass";
            }elseif($type==3){
                $qry=" select 'ALL' as RefDegree, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"TP")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry.=" ".$_where_cond;
                    }
                }
                $qry.=" UNION ALL";
                $qry.=" select TP.PositionDegreeID as RefDegree, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"TP")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry.=" ".$_where_cond;
                    }
                }
                $qry.=" group by TP.PositionDegreeID";
            }elseif($type==4){
                $qry=" select 'ALL' as RefEduRank, ".implode(", ", $str);
                $qry.=" from (";
                    $qry.=" select MIN(TE.EduRankID) as RefEduRank, T.*";
                    $qry.=$qry_sub;
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEduRankClass::TBL_PERSON_EDURANK." TE on T.PersonID=TE.EduPersonID and TE.EduRankID in (4,5)";
                    $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" UNION ALL";
                $qry.=" select T.RefEduRank, ".implode(", ", $str);
                $qry.=" from (";
                    $qry.=" select MIN(TE.EduRankID) as RefEduRank, T.*";
                    $qry.=$qry_sub;
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEduRankClass::TBL_PERSON_EDURANK." TE on T.PersonID=TE.EduPersonID and TE.EduRankID in (4,5)";
                    $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" group by T.RefEduRank";
            }elseif($type==5){
                $qry=" select 'ALL' as RefEduLevel, ".implode(", ", $str);
                $qry.=" from (";
                    $qry.=" select MAX(TEL.RefLevelOrder) as RefEduLevel, T.*";
                    $qry.= $qry_sub;
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEducationClass::TBL_PERSON_EDUCATION." TE on T.PersonID=TE.EducationPersonID";
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_EDUCATION_LEVEL." TEL on TE.EducationLevelID=TEL.RefLevelID";
                    $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" UNION ALL";
                $qry.=" select T.RefEduLevel, ".implode(", ", $str);
                $qry.=" from (";
                    $qry.=" select MAX(TEL.RefLevelOrder) as RefEduLevel, T.*";
                    $qry.= $qry_sub;
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEducationClass::TBL_PERSON_EDUCATION." TE on T.PersonID=TE.EducationPersonID";
                    $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_EDUCATION_LEVEL." TEL on TE.EducationLevelID=TEL.RefLevelID";
                    $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" group by T.RefEduLevel";
            }elseif($type==6){
                $qry=" select 'ALL' as RefAge, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_AGE." TA on T.PersonAge=TA.AgeID";
                $qry.=" UNION ALL";
                $qry.=" select TA.AgeID as RefAge, ".implode(", ", $str);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_AGE." TA on T.PersonAge=TA.AgeID";
                $qry.=" group by TA.AgeID";
            }elseif($type==7){
                $qry=" select 'ALL' as RefSoldier, ".implode(", ", $str);
                $qry.=$qry_sub;
            }
        }else{
            return [];
        }
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function getWorkDaysEmployee($search=array(), $list=[],$type=1){
        $now=new \DateTime();
        
        $_date=isset($search['humanres_dateend'])?$search['humanres_dateend']:$now->format("Y-m-d");
    
        $str=array();
         
        if(isset($list['col_organlist'])){
            foreach ($list['col_organlist'] as $tmp){
                $str[]="SUM(IF(T.JobOrganID = ".$tmp['RefOrganID'].",T.PersonWorkMonth+T.PersonNowMonth, 0)) as RefOrganMonth".$tmp['RefOrganID'];
                $str[]="SUM(IF(T.JobOrganID = ".$tmp['RefOrganID'].",T.PersonWorkDay+T.PersonNowDay, 0)) as RefOrganDay".$tmp['RefOrganID'];
            }
        }
        if(isset($list['col_organsublist'])){
            foreach ($list['col_organsublist'] as $tmp){
                $str[]="SUM(IF(T.JobOrganTypeID=1 AND T.JobOrganSubID = ".$tmp['RefOrganSubID'].",T.PersonWorkMonth+T.PersonNowMonth, 0)) as RefOrganSubMonth".$tmp['RefOrganSubID'];
                $str[]="SUM(IF(T.JobOrganTypeID=1 AND T.JobOrganSubID = ".$tmp['RefOrganSubID'].",T.PersonWorkDay+T.PersonNowDay, 0)) as RefOrganSubDay".$tmp['RefOrganSubID'];
            }
        }
        $str[]="SUM(IF(T.JobOrganID=1 AND T.JobOrganSubID = 1,T.PersonWorkMonth+T.PersonNowMonth, 0)) as JobOrganSubMonth1";
        $str[]="SUM(IF(T.JobOrganID=1 AND T.JobOrganSubID = 1,T.PersonWorkDay+T.PersonNowDay, 0)) as JobOrganSubDay1";
        $str[]="SUM(IF(T.JobOrganID=1 AND T.JobOrganSubID != 1,T.PersonWorkMonth+T.PersonNowMonth, 0)) as JobOrganSubMonthOther";
        $str[]="SUM(IF(T.JobOrganID=1 AND T.JobOrganSubID != 1,T.PersonWorkDay+T.PersonNowDay, 0)) as JobOrganSubDayOther";
        
        $qry_sub=" from (";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, 
                TF_J.JobOrganTypeID, TF_J.JobOrganID, TF_J.JobOrganSubID, 
                (TF_J.JobWorkedYear*12+ TF_J.JobWorkedMonth) as PersonWorkMonth, 
                TF_J.JobWorkedDay as PersonWorkDay,
                0 as PersonNowMonth,
                0 as PersonNowDay,
                0 as JobTypeGov,
                0 as JobTypeCompany,
                0 as JobTypeATG,
                0 as JobTypeMilitary
            ";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=0";
            $qry_sub.=" UNION ALL";
            $qry_sub.=" SELECT TF.PersonID, TF.PersonGender, TF.PersonBirthDate, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TF_J.JobOrganTypeID, TF_J.JobOrganID, TF_J.JobOrganSubID, 
                        0 AS WorkMonth,
                        0 AS WorkDays,
                        TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') AS PersonNowMonth,
                        TIMESTAMPDIFF(DAY, TF_J.JobDateStart+ INTERVAL TIMESTAMPDIFF(YEAR, TF_J.JobDateStart,'".$_date."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,TF_J.JobDateStart,'".$_date."') - TIMESTAMPDIFF(YEAR,TF_J.JobDateStart,'".$_date."')*12) MONTH,'".$_date."') AS PersonNowDay,
                    IF(TF_J.JobOrganID=1,1,0) as JobTypeGov, 
                    IF(TF_J.JobOrganID>1 AND TF_J.JobOrganID!=6,1,0) as JobTypeCompany, 
                    IF(TF_J.JobOrganSubID=1,1,0) as JobTypeATG, 
                    IF(TF_J.JobOrganTypeID=1,1,0) as JobTypeMilitary
            ";
            $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry_sub.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry_sub.=" ".$_where_cond;
                }
            }
            $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonJobClass::TBL_PERSON_JOB." TF_J on TF.PersonID=TF_J.JobPersonID AND TF_J.JobIsNow=1";
        $qry_sub.=" ) T";
        
        $qry=" SELECT TF.PersonID, TF.PersonLastName, TF.PersonFirstName, TF.PersonLastLetter, TF.PersonFirstLetter, 
            (TF.PersonWorkYearAll*12 + TF.PersonWorkMonthAll+TL.PersonNowMonth) as AllPersonWorkMonth, 
            (TF.PersonWorkDayAll+TL.PersonNowDay) as AllPersonWorkDay ,
            (TF.PersonWorkYearGov*12 + TF.PersonWorkMonthGov+IF(TL.JobTypeGov>0,TL.PersonNowMonth,0)) as AllPersonGovMonth, 
            (TF.PersonWorkDayGov+IF(TL.JobTypeGov>0,TL.PersonNowDay,0)) as AllPersonGovDay, 
            (TF.PersonWorkYearMilitary*12 + TF.PersonWorkMonthMilitary+IF(TL.JobTypeMilitary>0,TL.PersonNowMonth,0)) as AllPersonMilitaryMonth, 
            (TF.PersonWorkDayMilitary+IF(TL.JobTypeMilitary>0,TL.PersonNowDay,0)) as AllPersonMilitaryDay, 
            TP.PositionID, TD.DepartmentID,TP.PositionFullName, TP.PositionName, TD.DepartmentFullName, TD.DepartmentName,  TL.*, TSal.*";
        $qry.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
        $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
        $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
        $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
        if($_join_inner_cond!="" || $_where_cond!=""){
            if($_join_inner_cond!=""){
                $qry.=" and ".$_join_inner_cond;
            }
            if($_where_cond!=""){
                $qry.=" ".$_where_cond;
            }
        }
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on TFE.EmployeePositionID=TP.PositionID";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." TD on TP.PositionDepartmentID=TD.DepartmentID";
        $qry.=" LEFT JOIN (";
            $qry.=" select T.PersonID,
            SUM(T.PersonNowMonth) as PersonNowMonth, SUM(T.PersonNowDay) as PersonNowDay,
            SUM(T.JobTypeGov) as JobTypeGov, SUM(T.JobTypeCompany) as JobTypeCompany, 
            SUM(T.JobTypeATG) as JobTypeATG, SUM(T.JobTypeMilitary) as JobTypeMilitary, ".implode(", ", $str);
            $qry.=$qry_sub;
            $qry.=" group by T.PersonID";
        $qry.=" ) TL on TF.PersonID=TL.PersonID";
        $qry.=" LEFT JOIN (";
            $qry.=" select T.*";
            $qry.=" from ".DB_DATABASE_HUMANRES.".". \Humanres\PersonSalaryClass::TBL_PERSON_SALARY." T";
            $qry.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF on T.SalaryPersonID=TF.PersonID";
            $qry.=" INNER JOIN ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry.=" ".$_where_cond;
                }
            }
            $qry.=" left join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonSalaryClass::TBL_PERSON_SALARY." T1 ON T.`SalaryPersonID`=T1.`SalaryPersonID` AND T.SalaryID < T1.SalaryID ";
            $qry.=" WHERE T1.SalaryID IS NULL";
        $qry.=" ) TSal on TF.PersonID=TSal.SalaryPersonID";
        $qry.=" order by TD.DepartmentOrder, TP.PositionOrder";
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function getAward($search=array(), $list=[],$type=1){
        
        $now=new \DateTime();
        
        $_date=isset($search['humanres_dateend'])?$search['humanres_dateend']:$now->format("Y-m-d");
        
        $_sub_age="";
        if(isset($list['param_agelist'])){
            $_sub_age=", ( CASE";
            foreach ($list['param_agelist'] as $tmp){
                $_yearstart=$tmp['AgeStart'];
                $_yearend=$tmp['AgeEnd']+1;
                
                $start_date = date('Y-m-d', strtotime($_date. '-'.$_yearstart.' year'));
                $end_date = date('Y-m-d', strtotime($_date. '-'.$_yearend.' year'));
                $_sub_age.=" WHEN T.PersonBirthDate <= '".$start_date."' AND T.PersonBirthDate > '".$end_date."' THEN ".$tmp['AgeID'];
            }
            $_sub_age.=" END ) as PersonAge";
            
        }
        
        $str=array();
        $str_up=array();
        if(isset($list['col_awardlist'])){
            foreach ($list['col_awardlist'] as $tmp){
                $str[]="COUNT(IF(TA.AwardRefID=".$tmp['RefAwardID'].",TA.AwardID, NULL)) as Award".$tmp['RefAwardID'];
                $str_up[]="SUM(T.Award".$tmp['RefAwardID'].") as SumAward".$tmp['RefAwardID'];
            }
        }
        if(isset($list['col_awardsublist'])){
            foreach ($list['col_awardsublist'] as $tmp){
                $str[]="COUNT(IF(TA.AwardRefSubID=".$tmp['RefAwardID'].",TA.AwardID, NULL)) as AwardSub".$tmp['RefAwardID'];
                $str_up[]="SUM(T.AwardSub".$tmp['RefAwardID'].") as SumAwardSub".$tmp['RefAwardID'];
            }
        }
        $qry_sub=" from (";
            $qry_sub.=" select T.* ".$_sub_age;
            $qry_sub.=" from (";
                $qry_sub.=" select TF.PersonID, TF.PersonGender, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TF.PersonBirthDate, ".implode(", ", $str);
                $qry_sub.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
                $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
                $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry_sub.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry_sub.=" ".$_where_cond;
                    }
                }
                $qry_sub.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonAwardClass::TBL_PERSON_AWARD." TA on TF.PersonID=TA.AwardPersonID";
                $qry_sub.=" group by TF.PersonID";
                
            $qry_sub.=" ) T";
        $qry_sub.=" ) T";
            
        if($qry_sub!=""){
            if($type==1){
                $qry=" select 'ALL' as RefTypeClass, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" UNION ALL";
                $qry.=" select TP_TYPE.RefTypeClass, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP_TYPE.RefTypeClass";
            }elseif($type==2){
                $qry=" select CONCAT('ALL_',TP.PositionClassID) as RefTypeClass, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP.PositionClassID";
                $qry.=" UNION ALL";
                $qry.=" select CONCAT('ROW_',TP_TYPE.RefTypeClass,'_',TP.PositionClassID) as RefTypeClass, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                if(isset($search['position_classid']) && $search['position_classid']>0){
                    $qry.=" AND TP.PositionClassID=".$search['position_classid'];
                }
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_POSITION_TYPE." TP_TYPE on TP.PositionTypeID=TP_TYPE.RefTypeID";
                $qry.=" group by TP.PositionClassID, TP_TYPE.RefTypeClass";
            }elseif($type==3){
                $qry=" select 'ALL' as RefDegree, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"TP")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry.=" ".$_where_cond;
                    }
                }
                $qry.=" UNION ALL";
                $qry.=" select TP.PositionDegreeID as RefDegree, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
                $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"TP")));
                $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                if($_join_inner_cond!="" || $_where_cond!=""){
                    if($_join_inner_cond!=""){
                        $qry.=" and ".$_join_inner_cond;
                    }
                    if($_where_cond!=""){
                        $qry.=" ".$_where_cond;
                    }
                }
                $qry.=" group by TP.PositionDegreeID";
            }elseif($type==4){
                $qry=" select 'ALL' as RefEduRank, ".implode(", ", $str_up);
                $qry.=" from (";
                $qry.=" select MIN(TE.EduRankID) as RefEduRank, T.*";
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEduRankClass::TBL_PERSON_EDURANK." TE on T.PersonID=TE.EduPersonID and TE.EduRankID in (4,5)";
                $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" UNION ALL";
                $qry.=" select T.RefEduRank, ".implode(", ", $str_up);
                $qry.=" from (";
                $qry.=" select MIN(TE.EduRankID) as RefEduRank, T.*";
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEduRankClass::TBL_PERSON_EDURANK." TE on T.PersonID=TE.EduPersonID and TE.EduRankID in (4,5)";
                $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" group by T.RefEduRank";
            }elseif($type==5){
                $qry=" select 'ALL' as RefEduLevel, ".implode(", ", $str_up);
                $qry.=" from (";
                $qry.=" select MAX(TEL.RefLevelOrder) as RefEduLevel, T.*";
                $qry.= $qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEducationClass::TBL_PERSON_EDUCATION." TE on T.PersonID=TE.EducationPersonID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_EDUCATION_LEVEL." TEL on TE.EducationLevelID=TEL.RefLevelID";
                $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" UNION ALL";
                $qry.=" select T.RefEduLevel, ".implode(", ", $str_up);
                $qry.=" from (";
                $qry.=" select MAX(TEL.RefLevelOrder) as RefEduLevel, T.*";
                $qry.= $qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonEducationClass::TBL_PERSON_EDUCATION." TE on T.PersonID=TE.EducationPersonID";
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_EDUCATION_LEVEL." TEL on TE.EducationLevelID=TEL.RefLevelID";
                $qry.=" group by T.PersonID";
                $qry.=" ) T";
                $qry.=" group by T.RefEduLevel";
            }elseif($type==6){
                $qry=" select 'ALL' as RefAge, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_AGE." TA on T.PersonAge=TA.AgeID";
                $qry.=" UNION ALL";
                $qry.=" select TA.AgeID as RefAge, ".implode(", ", $str_up);
                $qry.=$qry_sub;
                $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\ReferenceClass::TBL_AGE." TA on T.PersonAge=TA.AgeID";
                $qry.=" group by TA.AgeID";
            }
        }else{
            return [];
        }
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function getAwardEmployee($search=array(), $list=[],$type=1){
        
        $now=new \DateTime();
        
        $_date=isset($search['humanres_dateend'])?$search['humanres_dateend']:$now->format("Y-m-d");
        
        $str=array();
        $str_up=array();
        if(isset($list['col_awardlist'])){
            foreach ($list['col_awardlist'] as $tmp){
                $str[]="COUNT(IF(TA.AwardRefID=".$tmp['RefAwardID'].",TA.AwardID, NULL)) as Award".$tmp['RefAwardID'];
            }
        }
        if(isset($list['col_awardsublist'])){
            foreach ($list['col_awardsublist'] as $tmp){
                $str[]="GROUP_CONCAT(IF(TA.`AwardRefSubID`=".$tmp['RefAwardID'].", TA.`AwardDate`, NULL) SEPARATOR ', ') AS AwardSub".$tmp['RefAwardID'];
            }
        }
        
        $qry=" select T.*, TP.PositionID, TD.DepartmentID,TP.PositionFullName, TP.PositionName, TD.DepartmentFullName, TD.DepartmentName ";
        $qry.=" from (";
            $qry.=" select TF.PersonID, TF.PersonGender, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TF.PersonLastName, TF.PersonFirstName, TF.PersonLastLetter, TF.PersonFirstLetter, ".implode(", ", $str);
            $qry.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry.=" ".$_where_cond;
                }
            }
            $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonAwardClass::TBL_PERSON_AWARD." TA on TF.PersonID=TA.AwardPersonID and TA.AwardDate<='".$_date."'";
            $qry.=" group by TF.PersonID";
        $qry.=" ) T";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." TD on TP.PositionDepartmentID=TD.DepartmentID";
        $qry.=" order by TD.DepartmentOrder, TP.PositionOrder";
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function getStudyEmployee($search=array(), $list=[],$type=1){
        
        $now=new \DateTime();
        
        $_dateend=isset($search['humanres_dateend'])?$search['humanres_dateend']:$now->format("Y-m-d");
        $_datestart=isset($search['humanres_datestart'])?$search['humanres_datestart']:$now->format("Y")."-01-01";
        
        $str=array();
        $str_up=array();
        if(isset($list['col_dirids'])){
            foreach ($list['col_dirids'] as $id){
                $str[]="GROUP_CONCAT(IF(TS.`StudyDirectionID`=".$id.", TS.`StudyDateEnd`, NULL) SEPARATOR ', ') AS Direction".$id;
            }
        }
        if(isset($list['col_dirsubids'])){
            foreach ($list['col_dirsubids'] as $id){
                $str[]="GROUP_CONCAT(IF(TS.`StudyDirSubID`=".$id.", TS.`StudyDateEnd`, NULL) SEPARATOR ', ') AS DirSub".$id;
            }
        }
        if(isset($list['col_dirsub1ids'])){
            foreach ($list['col_dirsub1ids'] as $id){
                $str[]="GROUP_CONCAT(IF(TS.`StudyDirSub1ID`=".$id.", TS.`StudyDateEnd`, NULL) SEPARATOR ', ') AS Dir1Sub".$id;
            }
        }
        $qry=" select T.*, TP.PositionID, TD.DepartmentID,TP.PositionFullName, TP.PositionName, TD.DepartmentFullName, TD.DepartmentName ";
        $qry.=" from (";
            $qry.=" select TF.PersonID, TF.PersonGender, TFE.EmployeeID, TFE.EmployeeDepartmentID, TFE.EmployeePositionID, TF.PersonLastName, TF.PersonFirstName, TF.PersonLastLetter, TF.PersonFirstLetter, 
                COUNT(TS.StudyID) as AllCount, ".implode(", ", $str);
            $qry.=" FROM ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." TF";
            $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." TFE on TF.PersonEmployeeID=TFE.EmployeeID and TFE.EmployeeIsActive=1 and TFE.EmployeePositionID IS NOT NULL";
            $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TF")));
            $_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
            $_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
            if($_join_inner_cond!="" || $_where_cond!=""){
                if($_join_inner_cond!=""){
                    $qry.=" and ".$_join_inner_cond;
                }
                if($_where_cond!=""){
                    $qry.=" ".$_where_cond;
                }
            }
            $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonStudyClass::TBL_PERSON_STUDY." TS on TF.PersonID=TS.StudyPersonID and TS.StudyDateEnd<='".$_dateend."' and TS.StudyDateEnd>='".$_datestart."'";
            $qry.=" group by TF.PersonID";
        $qry.=" ) T";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." TP on T.EmployeePositionID=TP.PositionID";
        $qry.=" INNER join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." TD on TP.PositionDepartmentID=TD.DepartmentID";
        $qry.=" order by TD.DepartmentOrder, TP.PositionOrder";
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
}