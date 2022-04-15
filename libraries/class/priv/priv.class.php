<?php
namespace System;
define("TBL_SYS_PACKAGE_MODULE", "`sys_packagesystemmodule`");
define("TBL_SYS_PACKAGE_PRIV", "`sys_packagesystempriv`");

class SystemPrivClass{
    private $con;
    private $ModuleID;
    private $ModuleSystemID;
    private $ModuleClassName;
    private $ModuleName;
    private $ModulePrivDefault;
    private $ModuleOrder;
    private $ModulePrivLocal;
    private $PrivID;
    private $PrivNetworkID;
    private $PrivPersonID;
    private $PrivModuleID;
    private $Priv;
    private $searchTerm;
    private $error;
    private $cloneObj="";
    
    public function __construct($dbcon = ""){
        $this->con=\Database::instance();
    }
    
    public static function getInstanceByArray($row, $dbcon = ""){
        $instance = new self($dbcon);
        $instance->initArray($row);
        return $instance;
    }
    
    public function initArray($row){
        $this->ModuleID= isset($row['ModuleID']) ? $row['ModuleID'] : "";
        $this->ModuleSystemID= isset($row['ModuleSystemID']) ? $row['ModuleSystemID'] : "";
        $this->ModuleClassName= isset($row['ModuleClassName']) ? $row['ModuleClassName'] : "";
        $this->ModuleName= isset($row['ModuleName']) ? $row['ModuleName'] : "";
        $this->ModulePrivDefault= isset($row['ModulePrivDefault']) ? $row['ModulePrivDefault'] : "";
        $this->ModuleOrder= isset($row['ModuleOrder']) ? $row['ModuleOrder'] : "";
        $this->ModulePrivLocal= isset($row['ModulePrivLocal']) ? $row['ModulePrivLocal'] : "";
        $this->PrivID= isset($row['PrivID']) ? $row['PrivID'] : "";
        $this->PrivNetworkID= isset($row['PrivNetworkID']) ? $row['PrivNetworkID'] : "";
        $this->PrivPersonID= isset($row['PrivPersonID']) ? $row['PrivPersonID'] : "";
        $this->PrivModuleID= isset($row['PrivModuleID']) ? $row['PrivModuleID'] : "";
        $this->Priv= isset($row['Priv']) ? $row['Priv'] : "";
    }
    
    public function getQueryList(){
        $qrycond = "where";
        
        $qryselect=array();
        $join_count=1;
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $order=array();
        $where_cond="";
        
        if(isset($this->searchTerm['personid'])){
            $qry_temp=" left join ".DB_DATABASE.".".TBL_SYS_PACKAGE_PRIV." T{$join_count} on T.ModuleID=T{$join_count}.PrivModuleID";
            $qry_temp .= " and T{$join_count}.`PrivPersonID` = '".$this->searchTerm['personid']."'";
            $qryselect[]=" T{$join_count}.*";
            $join_left[]=$qry_temp;
            $join_count++;
        }
        if(isset($this->searchTerm['systemid'])){
            $temp_cond="T.ModuleSystemID = '".$this->searchTerm['systemid']."'";
            if(count($join_inner)>0) $join_inner_cond.= " and ".$temp_cond;
            else {
                $where_cond.= " {$qrycond} ".$temp_cond;
                $qrycond=" and";
            }
        }
        if(isset($this->searchTerm['localid'])){
            $temp_cond="T.ModuleSystemLocalID = '".$this->searchTerm['localid']."'";
            if(count($join_inner)>0) $join_inner_cond.= " and ".$temp_cond;
            else {
                $where_cond.= " {$qrycond} ".$temp_cond;
                $qrycond=" and";
            }
        }
        if(isset($this->searchTerm['id'])){
            $temp_cond="";
            if(is_array($this->searchTerm['id'])){
                $temp_cond=" T.ModuleID IN (". implode(",", $this->searchTerm['id']).")";
            }else{
                $temp_cond=" T.ModuleID = '".$this->searchTerm['id']."'";
            }
            if(count($join_inner)>0) $join_inner_cond.= " and ".$temp_cond;
            else {
                $where_cond.= " {$qrycond} ".$temp_cond;
                $qrycond=" and";
            }
        }
        
        $qry = "select T.*";
        if(count($qryselect)>0) $qry.=", ".  implode(",", $qryselect);
        $qry.=" from ".DB_DATABASE.".".TBL_SYS_PACKAGE_MODULE." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        $qry.=" order by T.ModuleOrder, T.ModuleClassName";
        if(count($order)>0) $qry.=", ".implode(", ", $order);
      
        return $qry;
    }
    public function getRowList(){
        $list=array();
       
        $result = $this->con->select($this->getQueryList());
        return \Database::getList($result, []);
    }
    public function getPersonListByPriv($_privid, $_priv=1){
        $list=array();
        
        $qry = "select T.*";
        $qry.=" from ".DB_DATABASE.".".TBL_SYS_PACKAGE_PRIV." T";
        $qry.=" where T.PrivModuleID=".$_privid;
        $qry.=" and T.Priv=".$_priv;
        $result = $this->con->select($qry);
        return \Database::getList($result, ["_column"=>"PrivPersonID"]);
    }
    public function getList($type=1){
        $list=array();
        
        $result = $this->con->select($this->getQueryList());
        if($type==1){
            while($row = $result->fetch_array(MYSQLI_BOTH)){
                $obj = self::getInstanceByArray($row, $this->con);
                $list[] = $obj;
            }
        }else{
            while($row = $result->fetch_object()){
                $list[] = $row;
            }
        }
        return $list;
    }
    public function getPrivList($search=array()){
        $list=array();
        $qrycond = "where";
        
        $qryselect=array();
        $join_count=1;
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $order=array();
        $where_cond="";
        
        if(isset($search['networkapiid']) && isset($search['personid'])){
            $qry_temp=" left join ".DB_DATABASE.".".TBL_SYS_PACKAGE_PRIV." T{$join_count} on T.ModuleID=T{$join_count}.PrivModuleID";
            $qry_temp .= " and T{$join_count}.`PrivNetworkID` = '".$search['networkapiid']."'";
            $qry_temp .= " and T{$join_count}.`PrivPersonID` = '".$search['personid']."'";
            $qryselect[]=" T{$join_count}.*";
            $join_left[]=$qry_temp;
            $join_count++;
        }
        if(isset($search['systemid'])){
            $temp_cond="T.ModuleSystemID = '".$search['systemid']."'";
            if(count($join_inner)>0) $join_inner_cond.= " and ".$temp_cond;
            else {
                $where_cond.= " {$qrycond} ".$temp_cond;
                $qrycond=" and";
            }
        }
        
        $qry = "select T.*";
        if(count($qryselect)>0) $qry.=", ".  implode(",", $qryselect);
        $qry.=" from ".DB_DATABASE.".".TBL_SYS_PACKAGE_MODULE." T";
        if(count($join_inner)>0) {
            $qry.=implode(" ", $join_inner);
            $qry.=$join_inner_cond;
        }
        if(count($join_left)>0) $qry.=implode(" ", $join_left);
        $qry.=$where_cond;
        $qry.=" order by T.ModuleOrder";
        if(count($order)>0) $qry.=", ".implode(", ", $order);
        $result = $this->con->select($qry);
        while($row = $result->fetch_object()){
            $priv= isset($row->ModulePrivDefault)?$row->ModulePrivDefault:0;
            $priv=$row->Priv==""?$priv:$row->Priv;
            $list["m".$row->ModuleID]=$priv;
        }
        return $list;
    }
    
    public function __get($name){
        if(property_exists(get_class($this), $name)){
            return $this->$name;
        }else{
            throw new Exception('Класт параметр байхгүй');
        }
    }
    
    public function __set($name, $value){
        if(property_exists(get_class($this), $name)){
            switch ($name){
                default: $this->$name = $value;
            }
        }else{
            throw new Exception('Класт параметр байхгүй');
        }
    }
    
    public function savePriv($param_GET=array()){
       
        if(!isset($param_GET['moduleid']) || $param_GET['moduleid']==""){
            $this->error = new \System\Error(\System\Error::ERROR_DB, 'Дэд системийн модулийн дугаар хоосон байна.');
            return false;
        }
        if(!isset($param_GET['personid']) || $param_GET['personid']==""){
            $this->error = new \System\Error(\System\Error::ERROR_DB, 'Хэрэглэгчийн дугаар хоосон байна.');
            return false;
        }
        if(!isset($param_GET['priv']) || $param_GET['priv']===""){
            $this->error = new \System\Error(\System\Error::ERROR_DB, 'Эрх хоосон байна.');
            return false;
        }
        $priv=$param_GET['priv']?1:0;
        $qry=" insert into ".DB_DATABASE.".".TBL_SYS_PACKAGE_PRIV;
        $qry.=" values(NULL,1,{$param_GET['personid']},{$param_GET['moduleid']},{$priv})";
        $qry.=" ON DUPLICATE KEY UPDATE Priv=VALUES(Priv)";
        
        $res = $this->con->insert($qry);
        if($res === \System\Error::ERROR_DB) {
            $this->error = new \System\Error(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй.');
            return false;
        }
        return true;
    }
    
    public function clearError() {
        $this->error = "";
    }
    
    public function getError() {
        return $this->error;
    }
}
