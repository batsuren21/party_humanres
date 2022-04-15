<?php
namespace Humanres;
class DepartmentRelClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_DEPARTMENT_REL="department_rel";
    
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
        return isset($this->_data["RelDepartmentID"]) && $this->_data["RelDepartmentID"]!="" && isset($this->_data["RelDepartmentParentID"]) && $this->_data["RelDepartmentParentID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";

        if(isset($search['departmentrel_depth'])){
            $tmp_input=$search['departmentrel_depth'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_input=count($tmp_input)>0?$tmp_input:array(-1);
                $tmp_cond=$_mainTable.".RelDepartmentDepth IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".RelDepartmentDepth ='".$tmp_input."'";
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
        if(isset($search['departmentrel_id'])){
            $tmp_input=$search['departmentrel_id'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_input=count($tmp_input)>0?$tmp_input:array(-1);
                $tmp_cond=$_mainTable.".RelDepartmentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".RelDepartmentID ='".$tmp_input."'";
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
        if(isset($search['departmentrel_parentid'])){
            $tmp_input=$search['departmentrel_parentid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_input=count($tmp_input)>0?$tmp_input:array(-1);
                $tmp_cond=$_mainTable.".RelDepartmentParentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".RelDepartmentParentID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT_REL." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        return $qry;
    }
    function getRowList($search=array()){
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
        $result = $this->con->select($qry);
        return \Database::getList($result, $search);
    }
    function addRowRel($_data){
        $this->clearError();
        if(!isset($_data['rel_departmentid']) || empty($_data['rel_departmentid'])){
            $this->addError(\System\Error::ERROR_DB, 'Нэмэх өгөгдөл олдсонгүй');
            return -1;
        }
        if(!isset($_data['rel_parentid']) || empty($_data['rel_parentid'])){
            $this->addError(\System\Error::ERROR_DB, 'Нэмэх өгөгдөл олдсонгүй');
            return -1;
        }
        $qry="
            insert into ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT_REL." (
                RelDepartmentID,
                RelDepartmentParentID,
                RelDepartmentDepth
            ) values(".$_data['rel_departmentid'].",".$_data['rel_parentid'].",1)
        ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Нэмэх өгөгдөл олдсонгүй');
            return -1;
        }
        $qry="
            insert into ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT_REL." (
                RelDepartmentID,
                RelDepartmentParentID,
                RelDepartmentDepth
            ) select ".$_data['rel_departmentid'].",RelDepartmentParentID,RelDepartmentDepth+1
            from ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT_REL."
            where RelDepartmentID=".$_data['rel_parentid']."
        ";
        $res = $this->con->qryexec($qry);
        if($res>-1){
            return $res;
        }
        $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд хадгалж чадсангүй. Info::'.$this->con->getError());
        return -1;
    }
    function deleteRowRel($_data=array()){
        $this->clearError();
        if($this->RelDepartmentID=="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT_REL;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where RelDepartmentID= '".$this->RelDepartmentID."'";
        else $qry.=" where ". implode(" and ", $_data['cond']);
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
            return false;
        }
        return true;
    }
}