<?php
namespace Office;
class PackageSystemClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PACKAGE_SYSTEM="sys_packagesystem";
    
    private static $selectedSystemObj;
    private static $selectedSystemSubObj;
    
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
        return isset($this->_data["SystemID"]) && $this->_data["SystemID"]!=""?true:false;
    }
    final public static function getSelectedSystem(){
        if (!isset(self::$selectedSystemObj)) {
            $_module=isset($_GET['_module']) && $_GET['_module']!=""?$_GET['_module']:"home";
            return self::$selectedSystemObj=self::getInstance()->getRow(array("packsys_module"=>$_module));
        }
        return self::$selectedSystemObj;
    }
    final public static function getSelectedSystemSub(){
        if (!isset(self::$selectedSystemSubObj)) {
            $parObj=self::getSelectedSystem();
            if(!$parObj->isExist()){
                return self::getInstance();
            }
            $_submodule=isset($_GET['_submodule']) && $_GET['_submodule']!=""?$_GET['_submodule']:"";
            return self::$selectedSystemSubObj=self::getInstance()->getRow(array("packsys_parentid"=>$parObj->SystemID,"packsys_module"=>$_submodule));
        }
        return self::$selectedSystemSubObj;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search['packsys_packageid'])){
            $tmp_input=self::getParam($search['packsys_packageid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".SystemPackageID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".SystemPackageID ='".$tmp_input."'";
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
        if(isset($search['packsys_parentid'])){
            $tmp_input=self::getParam($search['packsys_parentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".SystemParentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".SystemParentID ='".$tmp_input."'";
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
        if(isset($search['packsys_module'])){
            $tmp_input=self::getParam($search['packsys_module']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".SystemModule IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".SystemModule ='".$tmp_input."'";
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
        if(isset($search['packsys_id'])){
            $tmp_input=self::getParam($search['packsys_id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".SystemID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".SystemID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE.".".self::TBL_PACKAGE_SYSTEM." T";
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
        $qry = "select COUNT(T.SystemID) as AllCount"; 
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
    
}