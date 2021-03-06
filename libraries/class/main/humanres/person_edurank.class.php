<?php
namespace Humanres;
class PersonEduRankClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_EDURANK="person_edurank";
    const TBL_PERSON_EDURANK_PREF="edurank_";
    
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
        return isset($this->_data["EduID"]) && $this->_data["EduID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'get_table']) && $search[self::TBL_PERSON_EDURANK_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_EDURANK_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.EduPersonID=TPerson.PersonID";
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
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'edudatestart']) && $search[self::TBL_PERSON_EDURANK_PREF.'edudatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDURANK_PREF.'edudatestart'];
            $tmp_cond=$_mainTable.".EduDate >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'edudateend']) && $search[self::TBL_PERSON_EDURANK_PREF.'edudateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDURANK_PREF.'edudateend'];
            $tmp_cond=$_mainTable.".EduDate <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'refid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDURANK_PREF.'refid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EduRankID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EduRankID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDURANK_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EduPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EduPersonID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDURANK_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EduID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EduID !='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_EDURANK_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDURANK_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EduID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EduID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDURANK." T";
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
        $qry = "select COUNT(T.EduID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDURANK." (
                    EduPersonID,
                    EduRankID,
                    EduDate,
                    EduLicence,
                    EduCreatePersonID,
                    EduCreateEmployeeID,
                    EduCreateDate
                ) values(
                    ".$_data['EduPersonID'].",
                    ".$_data['EduRankID'].",
                    ".\System\Util::getInput($_data['EduDate']).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['EduLicence'])).",
                    ".$_data['CreatePersonID'].",
                    ".$_data['CreateEmployeeID'].",
                    NOW()
                )
            ";
            $res = $this->con->insert($qry);
            if($this->con->getConnection()->affected_rows>0){
                return $res;
            }
            $this->addError(\System\Error::ERROR_DB, '?????????????????? ???????? ?????????????? ??????????????????. Info::'.$this->con->getError());
            return 0;
        }
        return 0;
    }
    function validateAddRow($_data=array(),$type=1){
        if(!isset($_data["EduPersonID"]) || $_data["EduPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????. ?????????? ?????????? ???????????????????????? ??????????');
        }
        if(!isset($_data["EduRankID"]) || $_data["EduRankID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????',"edurank[EduRankID]");
        }
        if(!isset($_data["EduDate"]) || $_data["EduDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????',"edurank[EduDate]");
        }
        if(!isset($_data["EduLicence"]) || $_data["EduLicence"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '???????????? ??????????',"edurank[EduLicence]");
        }
        if($type==1){
            if(!isset($_data["CreatePersonID"]) || $_data["CreatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????. ?????????????? ?????? ?????????????????? ??????????????????.');
            }
            if(!isset($_data["CreateEmployeeID"]) || $_data["CreateEmployeeID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????. ?????????????? ?????? ?????????????????? ??????????????????.');
            }
        }
        return !$this->hasError();
    }
    function updateRow($_data){
        $this->clearError();
        if($this->validateUpdateRow($_data)){
            $qry_update=array();
           
            if(isset($_data['EduRankID'])){
                $qry_update[]=" EduRankID=".$_data['EduRankID'];
            }
            if(isset($_data['EduLicence'])){
                $qry_update[]=" EduLicence=".\System\Util::getInput(\System\Util::uniConvert($_data['EduLicence']));
            }
            if(isset($_data['EduDate'])){
                $qry_update[]=" EduDate=".\System\Util::getInput($_data['EduDate']);
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, '?????????????????? ?????????????? ??????????????????.');
                return false;
            }
            $qry_update[]=" EduUpdateDate=NOW()";
            $qry_update[]=" EduUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" EduUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDURANK." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where EduID= '".$this->EduID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
                
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, '?????????????????? ???????? ?????????? ?????????????? ??????????????????. Info::'.$this->con->getError());
                return false;
            }
            return true;
        }
        return false;
    }
    function validateUpdateRow($_data=array(),$type=1){
        if(isset($_data["EduRankID"]) && $_data["EduRankID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????',"edurank[EduRankID]");
        }
        if(isset($_data["EduLicence"]) && $_data["EduLicence"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????',"edurank[EduLicence]");
        }
        if(isset($_data["EduDate"]) && $_data["EduDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '???????????? ??????????',"edurank[EduDate]");
        }
        if($type==1){
            if(!isset($_data["UpdatePersonID"]) || $_data["UpdatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????. ?????????????? ?????? ?????????????????? ??????????????????.');
            }
            if(!isset($_data["UpdateEmployeeID"]) || $_data["UpdateEmployeeID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, '?????????????????? ??????????. ?????????????? ?????? ?????????????????? ??????????????????.');
            }
        }
        return !$this->hasError();
    }
    function deleteRow($_data=array()){
        $this->clearError();
        if($this->EduID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, '???????????? ?????????????? ??????????????????.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDURANK;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where EduID= '".$this->EduID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, '?????????????????? ???????????? ??????????????????. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}