<?php
namespace Humanres;
class ReferenceClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_AWARD="ref_award";
    const TBL_DEPARTMENT_CLASS="ref_department_class";
    const TBL_DEPARTMENT_TYPE="ref_department_type";
    const TBL_EDUCATION_DEGREE="ref_education_degree";
    const TBL_EDUCATION_LEVEL="ref_education_level";
    const TBL_EDUCATION_RANK="ref_education_rank";
    const TBL_EDUCATION_SCHOOL="ref_education_school";
    const TBL_EMPLOYEE_START="ref_employee_start";
    const TBL_EMPLOYEE_QUIT="ref_employee_quit";
    const TBL_EMPLOYEE_QUIT_SUB="ref_employee_quit_sub";
    const TBL_ETHNICITY="ref_ethnicity";
    const TBL_POS_RANK="ref_pos_rank";
    const TBL_JOB_ORGAN="ref_job_organ";
    const TBL_JOB_ORGANTYPE="ref_job_organtype";
    const TBL_JOB_TYPE="ref_job_type";
    const TBL_JOB_POSITION="ref_job_position";
    const TBL_JOB_ORGANSUB="ref_job_organsub";
    const TBL_LANGUAGE="ref_language";
    const TBL_LANGUAGE_LEVEL="ref_language_level";
    const TBL_POSITION_CLASS="ref_position_class";
    const TBL_POSITION_DEGREE="ref_position_degree";
    const TBL_POSITION_RANK="ref_position_rank";
    const TBL_POSITION_TYPE="ref_position_type";
    const TBL_PUNISHMENT="ref_punishment";
    const TBL_RELATION="ref_relation";
    const TBL_SALARY_CONDITION="ref_salary_condition";
    const TBL_SALARY_DEGREE="ref_salary_degree";
    const TBL_SALARY_EDU="ref_salary_edu";
    const TBL_SALARY_PERCENT="ref_salary_percent";
    const TBL_STUDY_DIRECTION="ref_study_direction";
    const TBL_STUDY_DIRECTION_SUB="ref_study_direction_sub";
    const TBL_STUDY_DIRECTION_SUB1="ref_study_direction_sub1";
    const TBL_TRIP="ref_trip";
    const TBL_SOLDIER="ref_soldier";
    const TBL_AGE="ref_age";
    
    static $_tables=array(
        self::TBL_AWARD=>"RefAward",
        self::TBL_DEPARTMENT_CLASS=>"RefClass",
        self::TBL_DEPARTMENT_TYPE=>"RefType",
        self::TBL_EDUCATION_DEGREE=>"RefDegree",
        self::TBL_EDUCATION_LEVEL=>"RefLevel",
        self::TBL_EDUCATION_RANK=>"RefRank",
        self::TBL_EDUCATION_SCHOOL=>"RefSchool",
        self::TBL_EMPLOYEE_START=>"RefStart",
        self::TBL_EMPLOYEE_QUIT=>"RefQuit",
        self::TBL_EMPLOYEE_QUIT_SUB=>"RefSub",
        self::TBL_ETHNICITY=>"RefEthnic",
        self::TBL_JOB_ORGAN=>"RefOrgan",
        self::TBL_JOB_TYPE=>"RefType",
        self::TBL_LANGUAGE=>"RefLanguage",
        self::TBL_LANGUAGE_LEVEL=>"RefLevel",
        self::TBL_POSITION_CLASS=>"RefClass",
        self::TBL_POSITION_DEGREE=>"RefDegree",
        self::TBL_POSITION_RANK=>"RefRank",
        self::TBL_POSITION_TYPE=>"RefType",
        self::TBL_PUNISHMENT=>"RefPunishment",
        self::TBL_RELATION=>"RefRelation",
        self::TBL_STUDY_DIRECTION=>"RefDirection",
        self::TBL_STUDY_DIRECTION_SUB=>"RefDirSub",
        self::TBL_STUDY_DIRECTION_SUB1=>"RefDirSub1",
        self::TBL_TRIP=>"RefTrip",
        self::TBL_SOLDIER=>"RefSoldier",
        self::TBL_JOB_POSITION=>"RefPosition",
        self::TBL_JOB_ORGANSUB=>"RefOrganSub",
        self::TBL_JOB_ORGANTYPE=>"RefOrganType",
        self::TBL_POS_RANK=>"RefRank",
        self::TBL_SALARY_CONDITION=>"RefCondition",
        self::TBL_SALARY_DEGREE=>"RefDegree",
        self::TBL_SALARY_EDU=>"RefEdu",
        self::TBL_SALARY_PERCENT=>"RefPercent",
        self::TBL_AGE=>"ref_age"
    );
    static $_position_class=array(
        ['id'=>1,'title'=>"Удирдах"],
        ['id'=>2,'title'=>"Гүйцэтгэх"],
        ['id'=>3,'title'=>"Туслах"],
    );
    private $_table;
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
    function setTable($_table){
        $this->_table=$_table;
    }
    function isExist(){
        $_pref=isset(self::$_tables[$this->_table])?self::$_tables[$this->_table]:"";
        return isset($this->_data[$_pref."ID"]) && $this->_data[$_pref."ID"]!=""?true:false;
    }
    static function getQueryCondition($search=array(),$_table=""){
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        
        if(!isset(self::$_tables[$_table]) || $_table=="") return [
            "qryselect"=>$qryselect,
            "join_inner"=>$join_inner,
            "join_inner_cond"=>$join_inner_cond,
            "join_left"=>$join_left,
            "where_cond"=>$where_cond,
        ];
        $_pref=isset(self::$_tables[$_table])?self::$_tables[$_table]:self::$_tables[self::TBL_CLASS];
        
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[$_pref.'_get_table']) && $search[$_pref.'_get_table']!=""){
            switch ($search[$_pref.'_get_table']){
                case 1:
                    break;
            }
        }
        if(isset($search['ref_type'])){
            $tmp_input=self::getParam($search['ref_type']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."Type IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."Type ='".$tmp_input."'";
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
        if(isset($search['ref_parentid'])){
            $tmp_input=self::getParam($search['ref_parentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."ParentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."ParentID ='".$tmp_input."'";
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
        if(isset($search['ref_dirsub_directionid'])){
            $tmp_input=self::getParam($search['ref_dirsub_directionid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."DirectionID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."DirectionID ='".$tmp_input."'";
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
        if(isset($search['ref_dirsub1_directionid'])){
            $tmp_input=self::getParam($search['ref_dirsub1_directionid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."DirectionID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."DirectionID ='".$tmp_input."'";
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
        if(isset($search['ref_dirsub1_dirsubid'])){
            $tmp_input=self::getParam($search['ref_dirsub1_dirsubid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."DirSubID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."DirSubID ='".$tmp_input."'";
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
        if(isset($search['ref_id'])){
            $tmp_input=self::getParam($search['ref_id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".".$_pref."ID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".".$_pref."ID ='".$tmp_input."'";
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
        $_tmp=isset(self::$_tables[$this->_table])?$this->_table:self::TBL_CLASS;
        $qryselect= isset($qry_cond['qryselect'])?$qry_cond['qryselect']:array();
        $join_inner= isset($qry_cond['join_inner'])?$qry_cond['join_inner']:array();
        $join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
        $join_left= isset($qry_cond['join_left'])?$qry_cond['join_left']:array();
        $where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
        $qry="";
        if(count($qryselect)>0 && $_type==1) $qry.=", ".  implode(",", $qryselect);
        $qry.=" from ".DB_DATABASE_HUMANRES.".".$_tmp." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        return $qry;
    }
    function getRowCount($search=array(),$_table=""){
        $qry_cond=self::getQueryCondition($search,$_table);
        
        if(!isset(self::$_tables[$_table]) || $_table=="") return 0;
        $_pref=self::$_tables[$_table];
        $this->_table=$_table;
        $qry = "select COUNT(T.".$_pref."ID) as AllCount"; 
        $qry.=$this->getQueryBody($qry_cond,2);
        $result = $this->con->select($qry);
        return \Database::getRowCell($result);
    }
    function getRowList($search=array(),$_table=""){
        if(!isset(self::$_tables[$_table]) || $_table=="") return [];
        
        $this->_table=$_table;
        $qry_cond=self::getQueryCondition($search,$_table);
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
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
}