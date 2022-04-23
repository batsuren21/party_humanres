<?php
namespace Humanres;
class PositionClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_POSITION="position";
    const TBL_POSITION_PREF="position_";
    
    const CLASS_BASIC=1;
    const CLASS_ELECT=2;
    
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
        return isset($this->_data["PositionID"]) && $this->_data["PositionID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[self::TBL_POSITION_PREF.'get_table']) && $search[self::TBL_POSITION_PREF.'get_table']!=""){
            switch ($search[self::TBL_POSITION_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\DepartmentClass::TBL_DEPARTMENT;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".".$tmpTable." TDep on ".$_mainTable.".PositionDepartmentID=TDep.DepartmentID";
                    $qryselect[$tmpTable]= isset($qryselect[$tmpTable])?$qryselect[$tmpTable]:" TDep.*";
                    $qry_cond= \Humanres\DepartmentClass::getQueryCondition(array_merge($search,array("maintable"=>"TDep")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[$tmpTable].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[$tmpTable].=" ".$rel_where_cond;
                    }
                    break;
                
            }
        }
        if(isset($search[self::TBL_POSITION_PREF.'name']) && $search[self::TBL_POSITION_PREF.'name']!=""){
            $tmp_input=$search[self::TBL_POSITION_PREF.'name'];
            $tmp_cond=$_mainTable.".PositionName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_POSITION_PREF.'name_cond']) && $search[self::TBL_POSITION_PREF.'name_cond']!=""){
                switch($search[self::TBL_POSITION_PREF.'name_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".PositionName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".PositionName like '%" .$tmp_input. "%'";
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
        
        if(isset($search[self::TBL_POSITION_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionID !='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'departmentid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'departmentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionDepartmentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionDepartmentID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'classid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'classid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionClassID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionClassID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'rankid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'rankid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionRankID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionRankID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'typeid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'typeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionTypeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionTypeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'degreeid'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'degreeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionDegreeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionDegreeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_POSITION_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_POSITION_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PositionID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PositionID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_POSITION." T";
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
        $qry = "select COUNT(T.PositionID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_POSITION." (
                    PositionDepartmentID,
                    PositionClassID,
                    PositionTypeID,
                    PositionName,
                    PositionFullName,
                    PositionOrder,
                    PositionCreatePersonID,
                    PositionCreateEmployeeID,
                    PositionCreateDate
                ) values(
                    ".$_data['PositionDepartmentID'].",
                    ".$_data['PositionClassID'].",
                    ".$_data['PositionTypeID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['PositionName'])).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['PositionFullName'])).",
                    ".$_data['PositionOrder'].",
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
        if(!isset($_data["PositionDepartmentID"]) || $_data["PositionDepartmentID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэгж сонгоогүй байна',"position[PositionDepartmentID]");
        }
        if(!isset($_data["PositionClassID"]) || $_data["PositionClassID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ангилал сонгоогүй байна',"position[PositionClassID]");
        }
        if(!isset($_data["PositionName"]) || $_data["PositionName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"position[PositionName]");
        }
        if(!isset($_data["PositionFullName"]) || $_data["PositionFullName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"position[PositionFullName]");
        }
        if(!isset($_data["PositionOrder"]) || $_data["PositionOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ хоосон байна',"position[PositionOrder]");
        }elseif(isset($_data["PositionOrder"]) && !is_numeric($_data["PositionOrder"])){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ тоо байх ёстой',"position[PositionOrder]");
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
            
            if(isset($_data['PositionClassID'])){
                $qry_update[]=" PositionClassID=".$_data['PositionClassID'];
            }
            if(isset($_data['PositionTypeID'])){
                $qry_update[]=" PositionTypeID=".$_data['PositionTypeID'];
            }
            if(isset($_data['PositionName'])){
                $qry_update[]=" PositionName=".\System\Util::getInput(\System\Util::uniConvert($_data['PositionName']));
            }
            if(isset($_data['PositionFullName'])){
                $qry_update[]=" PositionFullName=".\System\Util::getInput(\System\Util::uniConvert($_data['PositionFullName']));
            }
            
            if(isset($_data['PositionCountEmployee'])){
                $qry_update[]=" PositionCountEmployee=".$_data['PositionCountEmployee'];
            }
            if(isset($_data['PositionCountEmployeed'])){
                $qry_update[]=" PositionCountEmployeed=".$_data['PositionCountEmployeed'];
            }
            if(isset($_data['PositionCountEmployeeRecord'])){
                $qry_update[]=" PositionCountEmployeeRecord=".$_data['PositionCountEmployeeRecord'];
            }
            if(isset($_data['PositionOrder'])){
                $qry_update[]=" PositionOrder=".$_data['PositionOrder'];
            }
            
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" PositionUpdateDate=NOW()";
            $qry_update[]=" PositionUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" PositionUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_POSITION." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where PositionID= '".$this->PositionID."'";
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
        
        if(isset($_data["PositionClassID"]) && $_data["PositionClassID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ангилал сонгоогүй байна',"position[PositionClassID]");
        }
        if(isset($_data["PositionName"]) && $_data["PositionName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"position[PositionName]");
        }
        if(isset($_data["PositionFullName"]) && $_data["PositionFullName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"position[PositionFullName]");
        }
        if(isset($_data["PositionOrder"]) && $_data["PositionOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ хоосон байна',"position[PositionOrder]");
        }elseif(isset($_data["PositionOrder"]) && !is_numeric($_data["PositionOrder"])){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ тоо байх ёстой',"position[PositionOrder]");
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
        if($this->PositionID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_POSITION;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
        $qry.=" where PositionID= '".$this->PositionID."'";
        else $qry.=" where ". implode(" and ", $_data['cond']);
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
            return false;
        }
        return true;
    }
}