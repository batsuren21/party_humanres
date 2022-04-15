<?php
namespace Office;
class AreaClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_AREA="ref_area";

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
        return isset($this->_data["AreaID"]) && $this->_data["AreaID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search['area_get_table']) && $search['area_get_table']!=""){
            switch ($search['area_get_table']){
                case 1:
                    $tmpTable=self::TBL_AREA;
                    $qryselect[$tmpTable]= " T1.AreaID as ChildID, T1.AreaName as ChildName";
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE.".". $tmpTable." T1 on ".$_mainTable.".AreaID=T1.AreaParentID";
                    break;
            }
        }
        
        if(isset($search['area_parentid'])){
            $tmp_input=self::getParam($search['area_parentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".AreaParentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".AreaParentID ='".$tmp_input."'";
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
        if(isset($search['area_globalid'])){
            $tmp_input=self::getParam($search['area_globalid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".AreaGlobalID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".AreaGlobalID ='".$tmp_input."'";
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
        if(isset($search['area_id'])){
            $tmp_input=self::getParam($search['area_id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".AreaID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".AreaID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE.".".self::TBL_AREA." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        return $qry;
    }
    function getRowCount($search=array()){
        $qry_cond=self::getQueryCondition($search);
        $qry = "select COUNT(T.AreaID) as AllCount"; 
        $qry.=$this->getQueryBody($qry_cond,2);
        $result = $this->con->select($qry);
        return \Database::getRowCell($result);
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
    function addRow($_data){
        $this->clearError();
        if($this->validateAddRow($_data)){
            $qry="
                insert into ".DB_DATABASE.".".self::TBL_AREA." (
                    AreaParentID,
                    AreaGlobalID,
                    AreaName
                ) values(
                    ".$_data['AreaParentID'].",
                    ".$_data['AreaGlobalID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['AreaName']))."
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
        if(!isset($_data["AreaName"]) || $_data["AreaName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"area[AreaName]");
        }
        if(!isset($_data["AreaParentID"]) || $_data["AreaParentID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'ParentID Хоосон байна');
        }
        if(!isset($_data["AreaGlobalID"]) || $_data["AreaGlobalID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'GlobalID Хоосон байна');
        }
        return !$this->hasError();
    }
    function updateRow($_data){
        $this->clearError();
        if($this->validateUpdateRow($_data)){
            $qry_update=array();
            
            if(isset($_data['AreaName'])){
                $qry_update[]=" AreaName=".\System\Util::getInput(\System\Util::uniConvert($_data['AreaName']));
            }
            
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            
            $qry=" update ".DB_DATABASE.".".self::TBL_AREA." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
                $qry.=" where AreaID = '".$this->AreaID."'";
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
        if(isset($_data["AreaName"]) && $_data["AreaName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"area[AreaName]");
        }
        
        return !$this->hasError();
    }
    function deleteRow($_data=array()){
        $this->clearError();
        if($this->AreaID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE.".".self::TBL_AREA;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where AreaID= '".$this->AreaID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}