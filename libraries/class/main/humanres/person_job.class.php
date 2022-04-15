<?php
namespace Humanres;
class PersonJobClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_JOB="person_job";
    const TBL_PERSON_JOB_PREF="job_";
    
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
    function isExist(){
        return isset($this->_data["JobID"]) && $this->_data["JobID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_JOB_PREF.'get_table']) && $search[self::TBL_PERSON_JOB_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_JOB_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.JobPersonID=TPerson.PersonID";
                    $qryselect[$tmpTable]= isset($qryselect[$tmpTable])?$qryselect[$tmpTable]:" TPerson.*";
                    $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"TPerson")) );
                    
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[$tmpTable].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[$tmpTable].=" ".$rel_where_cond;
                    }
                    
                    if(isset($qry_cond['qryselect']) && count($qry_cond['qryselect'])>0){
                        $qryselect=array_merge($qryselect,$qry_cond['qryselect']);
                    }
                    if(isset($qry_cond['join_inner']) && count($qry_cond['join_inner'])>0){
                        $join_inner=array_merge($join_inner,$qry_cond['join_inner']);
                    }
                    break;
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobPersonID ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'isnow'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'isnow']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobIsNow IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobIsNow ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'organtypeid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'organtypeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobOrganTypeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobOrganTypeID ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        
        if(isset($search[self::TBL_PERSON_JOB_PREF.'isabsent'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'isabsent']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobIsAbsent IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobIsAbsent ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'organid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'organid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobOrganID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobOrganID ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'organsubid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'organsubid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobOrganSubID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobOrganSubID ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'organtitle']) && $search[self::TBL_PERSON_JOB_PREF.'organtitle']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'organtitle'];
            $tmp_cond=$_mainTable.".JobOrganTitle LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_JOB_PREF.'organtitle_cond']) && $search[self::TBL_PERSON_JOB_PREF.'organtitle_cond']!=""){
                switch($search[self::TBL_PERSON_JOB_PREF.'organtitle_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".JobOrganTitle = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".JobOrganTitle like '%" .$tmp_input. "%'";
                        break;
                }
            }
            if(count($join_inner)>0){
                $first_value = reset($join_inner);
                $_table = key($join_inner);
                $join_inner[$_table] .= " and ".$tmp_cond;
            }
            else {
                $where_cond.= " {$qrycond} ".$tmp_cond;
                $qrycond=" and";
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'startdatestart']) && $search[self::TBL_PERSON_JOB_PREF.'startdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'startdatestart'];
            $tmp_cond=$_mainTable.".JobDateStart >='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'startdateend']) && $search[self::TBL_PERSON_JOB_PREF.'startdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'startdateend'];
            $tmp_cond=$_mainTable.".JobDateStart <='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitdatestart']) && $search[self::TBL_PERSON_JOB_PREF.'quitdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitdatestart'];
            $tmp_cond=$_mainTable.".JobDateQuit >='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitdateend']) && $search[self::TBL_PERSON_JOB_PREF.'quitdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitdateend'];
            $tmp_cond=$_mainTable.".JobDateQuit <='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitorderdatestart']) && $search[self::TBL_PERSON_JOB_PREF.'quitorderdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitorderdatestart'];
            $tmp_cond=$_mainTable.".JobQuitOrderDate >='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitorderdateend']) && $search[self::TBL_PERSON_JOB_PREF.'quitorderdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitorderdateend'];
            $tmp_cond=$_mainTable.".JobQuitOrderDate <='".$tmp_input."'";
            
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobID !='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'startorder']) && $search[self::TBL_PERSON_JOB_PREF.'startorder']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'startorder'];
            $tmp_cond=$_mainTable.".JobStartOrder LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_JOB_PREF.'startorder_cond']) && $search[self::TBL_PERSON_JOB_PREF.'startorder_cond']!=""){
                switch($search[self::TBL_PERSON_JOB_PREF.'startorder_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".JobStartOrder = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".JobStartOrder like '%" .$tmp_input. "%'";
                        break;
                }
            }
            if(count($join_inner)>0){
                $first_value = reset($join_inner);
                $_table = key($join_inner);
                $join_inner[$_table] .= " and ".$tmp_cond;
            }
            else {
                $where_cond.= " {$qrycond} ".$tmp_cond;
                $qrycond=" and";
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitorder']) && $search[self::TBL_PERSON_JOB_PREF.'quitorder']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitorder'];
            $tmp_cond=$_mainTable.".JobQuitOrder LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_JOB_PREF.'quitorder_cond']) && $search[self::TBL_PERSON_JOB_PREF.'quitorder_cond']!=""){
                switch($search[self::TBL_PERSON_JOB_PREF.'quitorder_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".JobQuitOrder = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".JobQuitOrder like '%" .$tmp_input. "%'";
                        break;
                }
            }
            if(count($join_inner)>0){
                $first_value = reset($join_inner);
                $_table = key($join_inner);
                $join_inner[$_table] .= " and ".$tmp_cond;
            }
            else {
                $where_cond.= " {$qrycond} ".$tmp_cond;
                $qrycond=" and";
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'quitreason']) && $search[self::TBL_PERSON_JOB_PREF.'quitreason']!=""){
            $tmp_input=$search[self::TBL_PERSON_JOB_PREF.'quitreason'];
            $tmp_cond=$_mainTable.".JobQuitReason LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_JOB_PREF.'quitreason_cond']) && $search[self::TBL_PERSON_JOB_PREF.'quitreason_cond']!=""){
                switch($search[self::TBL_PERSON_JOB_PREF.'quitreason_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".JobQuitReason = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".JobQuitReason like '%" .$tmp_input. "%'";
                        break;
                }
            }
            if(count($join_inner)>0){
                $first_value = reset($join_inner);
                $_table = key($join_inner);
                $join_inner[$_table] .= " and ".$tmp_cond;
            }
            else {
                $where_cond.= " {$qrycond} ".$tmp_cond;
                $qrycond=" and";
            }
        }
        if(isset($search[self::TBL_PERSON_JOB_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_JOB_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".JobID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".JobID ='".$tmp_input."'";
            }
            if($tmp_cond!=""){
                if(count($join_inner)>0){
                    $first_value = reset($join_inner);
                    $_table = key($join_inner);
                    $join_inner[$_table] .= " and ".$tmp_cond;
                }
                else {
                    $where_cond.= " {$qrycond} ".$tmp_cond;
                    $qrycond=" and";
                }
            }
        }
        
        return array("qryselect"=>$qryselect,
            "join_inner"=>$join_inner,
            "join_inner_cond"=>$join_inner_cond,
            "join_left"=>$join_left,
            "where_cond"=>$where_cond,
        );
    }
    function getQueryBody($qry_cond=array(),$_type=1){
        $qryselect= isset($qry_cond['qryselect'])?$qry_cond['qryselect']:array();
        $join_inner= isset($qry_cond['join_inner'])?$qry_cond['join_inner']:array();
        $join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
        $join_left= isset($qry_cond['join_left'])?$qry_cond['join_left']:array();
        $where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
        $qry="";
        if(count($qryselect)>0 && $_type==1) $qry.=", ".  implode(",", $qryselect);
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_JOB." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        return $qry;
    }
    function getRowCount($search=array(),$_debug=0){
        $qry_cond=self::getQueryCondition($search);
        $qry = "select COUNT(T.JobID) as AllCount";
        $qry.=$this->getQueryBody($qry_cond,2);
        if($_debug>0){
            print_r($qry);
            if($_debug>1) exit;
        }
        $result = $this->con->select($qry);
        return \Database::getRowCell($result);
    }
    function getRowList($search=array(),$_debug=0){
        $list=array();
        
        $qry_cond=self::getQueryCondition($search);
        $order=array();
        if(isset($search['orderby'])){
            if(is_array($search['orderby'])){
                $order=$search['orderby'];
            }else{
                $order[]=$search['orderby'];
            }
        }
        $groupby=array();
        if(isset($search['groupby'])){
            if(is_array($search['groupby'])){
                $groupby=$search['groupby'];
            }else{
                $groupby[]=$search['groupby'];
            }
        }
        $qryselect= isset($search['_select'])?$search['_select']:array();
        if(count($qryselect)<1) $qry = "select T.*";
        else $qry = "select ". implode(",", $qryselect);
        $qry.=$this->getQueryBody($qry_cond);
        if(count($groupby)>0){
            $qry.=" group by ";
            $qry.=" ".implode(", ", $groupby);
        }
        if(count($order)>0){
            $qry.=" order by ";
            $qry.=" ".implode(", ", $order);
        }
        if(isset($search['rowstart']) && isset($search['rowlength'])){
            $qry.=" limit ".$search['rowstart'].",".$search['rowlength'];
        }
        if($_debug>0){
            print_r($qry);
            if($_debug>1) exit;
        }
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function addRow($_data){
        $this->clearError();
        if($this->validateAddRow($_data)){
            $qry="
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_JOB." (
                    JobPersonID,
                    JobOrganTypeID,
                    JobOrganID,
                    JobOrganSubID,
                    JobPositionID,
                    JobOrganTitle,
                    JobDepartmentTitle,
                    JobPositionTitle,
                    JobIsNow,
                    JobDateStart,
                    JobStartOrder,
                    JobDateQuit,
                    JobQuitReason,
                    JobQuitOrder,
                    JobQuitOrderDate,
                    JobWorkedYear,
                    JobWorkedMonth,
                    JobWorkedDay,
                    JobCreatePersonID,
                    JobCreateEmployeeID,
                    JobCreateDate
                ) values(
                    ".$_data['JobPersonID'].",
                    ".$_data['JobOrganTypeID'].",
                    ".$_data['JobOrganID'].",
                    ".(isset($_data['JobOrganSubID'])?$_data['JobOrganSubID']:0).",
                    ".(isset($_data['JobPositionID'])?$_data['JobPositionID']:0).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['JobOrganTitle'])).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['JobDepartmentTitle'])).",
                    ".\System\Util::getInput(isset($_data['JobPositionTitle'])?\System\Util::uniConvert($_data['JobPositionTitle']):"").",
                    ".(isset($_data['JobIsNow'])?$_data['JobIsNow']:0).",
                    ".\System\Util::getInput($_data['JobDateStart']).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['JobStartOrder'])).",
                    ".\System\Util::getInput(isset($_data['JobDateQuit'])?$_data['JobDateQuit']:"").",
                    ".\System\Util::getInput(isset($_data['JobQuitReason'])?\System\Util::uniConvert($_data['JobQuitReason']):"").",
                    ".\System\Util::getInput(isset($_data['JobQuitOrder'])?\System\Util::uniConvert($_data['JobQuitOrder']):"").",
                    ".\System\Util::getInput(isset($_data['JobQuitOrderDate'])?$_data['JobQuitOrderDate']:"").",
                    ".(isset($_data['JobWorkedYear'])?$_data['JobWorkedYear']:0).",
                    ".(isset($_data['JobWorkedMonth'])?$_data['JobWorkedMonth']:0).",
                    ".(isset($_data['JobWorkedDay'])?$_data['JobWorkedDay']:0).",
                    ".$_data['CreatePersonID'].",
                    ".$_data['CreateEmployeeID'].",
                    NOW()
                )
            ";
            $res = $this->con->insert($qry);
            if($this->con->getConnection()->affected_rows>0){
                return $res;
            }
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд хадгалж чадсангүй. Info::'.$this->con->getError());
            return 0;
        }
        return 0;
    }
    function validateAddRow($_data=array(),$type=1){
        if(!isset($_data["JobPersonID"]) || $_data["JobPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Албан хаагч сонгогдоогүй байна');
        }
        if(!isset($_data["JobOrganTypeID"]) || $_data["JobOrganTypeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"job[JobOrganTypeID]");
        }
        if(!isset($_data["JobOrganID"]) || $_data["JobOrganID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"job[JobOrganID]");
        }
        if(!isset($_data["JobOrganTitle"]) || $_data["JobOrganTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobOrganTitle]");
        }
        if(!isset($_data["JobDepartmentTitle"]) || $_data["JobDepartmentTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDepartmentTitle]");
        }
//         if(!isset($_data["JobPositionTitle"]) || $_data["JobPositionTitle"]===""){
//             $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobPositionTitle]");
//         }
        if(!isset($_data["JobDateStart"]) || $_data["JobDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDateStart]");
        }
        if(!isset($_data["JobStartOrder"]) || $_data["JobStartOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobStartOrder]");
        }
        if(isset($_data['JobIsNow']) && !$_data['JobIsNow']){
            if(!isset($_data["JobDateQuit"]) || $_data["JobDateQuit"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDateQuit]");
            }
            if(isset($_data["JobDateStart"]) && $_data["JobDateStart"]!=="" && isset($_data["JobDateQuit"]) && $_data["JobDateQuit"]!=="" ){
                $_startDate=new \DateTime($_data["JobDateStart"]);
                $_endDate=new \DateTime($_data["JobDateQuit"]);
                if($_endDate<$_startDate){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ажилд орсон огноо ажлаас гарсан огнооны ард байна');
                }
                
            }
            if(!isset($_data["JobQuitReason"]) || $_data["JobQuitReason"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitReason]");
            }
            if(!isset($_data["JobQuitOrder"]) || $_data["JobQuitOrder"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitOrder]");
            }
//             if(!isset($_data["JobQuitOrderDate"]) || $_data["JobQuitOrderDate"]===""){
//                 $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitOrderDate]");
//             }
        }
        if($type==1){
            if(!isset($_data["CreatePersonID"]) || $_data["CreatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
            if(!isset($_data["CreateEmployeeID"]) || $_data["CreateEmployeeID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
        }
        return !$this->hasError();
    }
    function updateRow($_data){
        $this->clearError();
        if($this->validateUpdateRow($_data)){
            $qry_update=array();
            if(isset($_data['JobOrganTypeID'])){
                $qry_update[]=" JobOrganTypeID=".$_data['JobOrganTypeID'];
            }
            if(isset($_data['JobOrganID'])){
                $qry_update[]=" JobOrganID=".$_data['JobOrganID'];
            }
            if(isset($_data['JobOrganSubID'])){
                $qry_update[]=" JobOrganSubID=".$_data['JobOrganSubID'];
            }
            if(isset($_data['JobPositionID'])){
                $qry_update[]=" JobPositionID=".$_data['JobPositionID'];
            }
            if(isset($_data['JobOrganTitle'])){
                $qry_update[]=" JobOrganTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['JobOrganTitle']));
            }
            if(isset($_data['JobDepartmentTitle'])){
                $qry_update[]=" JobDepartmentTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['JobDepartmentTitle']));
            }
            if(isset($_data['JobPositionTitle'])){
                $qry_update[]=" JobPositionTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['JobPositionTitle']));
            }
            if(isset($_data['JobDateStart'])){
                $qry_update[]=" JobDateStart=".\System\Util::getInput($_data['JobDateStart']);
            }
            if(isset($_data['JobStartOrder'])){
                $qry_update[]=" JobStartOrder=".\System\Util::getInput(\System\Util::uniConvert($_data['JobStartOrder']));
            }
            if(isset($_data['JobIsNow'])){
                $qry_update[]=" JobIsNow=".\System\Util::getInput($_data['JobIsNow']);
            }
            if(isset($_data['JobDateQuit'])){
                $qry_update[]=" JobDateQuit=".\System\Util::getInput($_data['JobDateQuit']);
            }
            if(isset($_data['JobWorkedYear'])){
                $qry_update[]=" JobWorkedYear=".$_data['JobWorkedYear'];
            }
            if(isset($_data['JobWorkedMonth'])){
                $qry_update[]=" JobWorkedMonth=".$_data['JobWorkedMonth'];
            }
            if(isset($_data['JobWorkedDay'])){
                $qry_update[]=" JobWorkedDay=".$_data['JobWorkedDay'];
            }
            if(isset($_data['JobQuitReason'])){
                $qry_update[]=" JobQuitReason=".\System\Util::getInput(\System\Util::uniConvert($_data['JobQuitReason']));
            }
            if(isset($_data['JobQuitOrder'])){
                $qry_update[]=" JobQuitOrder=".\System\Util::getInput(\System\Util::uniConvert($_data['JobQuitOrder']));
            }
            if(isset($_data['JobQuitOrderDate'])){
                $qry_update[]=" JobQuitOrderDate=".\System\Util::getInput($_data['JobQuitOrderDate']);
            }
            if(isset($_data['JobIsAbsent'])){
                $qry_update[]=" JobIsAbsent=".$_data['JobIsAbsent'];
            }
            if(isset($_data['JobAbsentDateStart'])){
                $qry_update[]=" JobAbsentDateStart=".\System\Util::getInput($_data['JobAbsentDateStart']);
            }
            if(isset($_data['JobAbsentYear'])){
                $qry_update[]=" JobAbsentYear=".$_data['JobAbsentYear'];
            }
            if(isset($_data['JobAbsentMonth'])){
                $qry_update[]=" JobAbsentMonth=".$_data['JobAbsentMonth'];
            }
            if(isset($_data['JobAbsentDay'])){
                $qry_update[]=" JobAbsentDay=".$_data['JobAbsentDay'];
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" JobUpdateDate=NOW()";
            $qry_update[]=" JobUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" JobUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_JOB." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where JobID= '".$this->JobID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
                
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
                return false;
            }
            return true;
        }
        return false;
    }
    function validateUpdateRow($_data=array(),$type=1){
        if(isset($_data["JobOrganID"]) && $_data["JobOrganID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"job[JobOrganID]");
        }
        if(isset($_data["JobOrganTitle"]) && $_data["JobOrganTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobOrganTitle]");
        }
        if(isset($_data["JobDepartmentTitle"]) && $_data["JobDepartmentTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDepartmentTitle]");
        }
        if(isset($_data["JobPositionTitle"]) && $_data["JobPositionTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobPositionTitle]");
        }
        if(isset($_data["JobDateStart"]) && $_data["JobDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDateStart]");
        }
        if(isset($_data["JobStartOrder"]) && $_data["JobStartOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobStartOrder]");
        }
        if(isset($_data['JobIsNow']) && !$_data['JobIsNow']){
            if(isset($_data["JobDateQuit"]) && $_data["JobDateQuit"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobDateQuit]");
            }
            if(isset($_data["JobDateStart"]) && $_data["JobDateStart"]!=="" && isset($_data["JobDateQuit"]) && $_data["JobDateQuit"]!=="" ){
                $_startDate=new \DateTime($_data["JobDateStart"]);
                $_endDate=new \DateTime($_data["JobDateQuit"]);
                if($_endDate<$_startDate){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ажилд орсон огноо ажлаас гарсан огнооны ард байна');
                }
            }
            if(isset($_data["JobQuitReason"]) && $_data["JobQuitReason"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitReason]");
            }
            if(isset($_data["JobQuitOrder"]) && $_data["JobQuitOrder"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitOrder]");
            }
//             if(isset($_data["JobQuitOrderDate"]) && $_data["JobQuitOrderDate"]===""){
//                 $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"job[JobQuitOrderDate]");
//             }
        }
        if($type==1){
            if(!isset($_data["UpdatePersonID"]) || $_data["UpdatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
            if(!isset($_data["UpdateEmployeeID"]) || $_data["UpdateEmployeeID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
        }
        return !$this->hasError();
    }
    function deleteRow($_data=array()){
        $this->clearError();
        if($this->JobID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_JOB;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where JobID= '".$this->JobID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}