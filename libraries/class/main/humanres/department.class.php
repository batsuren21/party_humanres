<?php
namespace Humanres;
class DepartmentClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_DEPARTMENT="department";
    const TBL_DEPARTMENT_PREF="department_";
    
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
    function getSMSBody($_type=1, $_datetime=""){
        $_smsbody="";
        if($_type==1){
            
        }
        return $_smsbody;
    }
    public function __set($name, $value){
        if(isset($this->_data[$name])){
            $this->_data[$name]=$value;
        }else{
            $this->_data[$name]=$value;
        }
    }
    public function getPetitionType(){
        if(isset($this->_data["DepartmentTypeID"]) && $this->_data["DepartmentTypeID"]!=""){
            switch ($this->_data["DepartmentTypeID"]){
                case 1:
                    return [1,6];
                    break;
                case 2:
                    return [1];
                    break;
                case 3:
                    return [2,7];
                    break;
                case 4:
                    return [3];
                    break;
                case 5:
                    return [4];
                    break;
                case 6:
                    return [5,7];
                    break;
                case 7:
                    return [];
                    break;
            }
        }
        return [];
    }
    function isExist(){
        return isset($this->_data["DepartmentID"]) && $this->_data["DepartmentID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[self::TBL_DEPARTMENT_PREF.'table']) && $search[self::TBL_DEPARTMENT_PREF.'table']!=""){
            switch ($search[self::TBL_DEPARTMENT_PREF.'table']){
                case 1:
                    $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL." T1 on T.DepartmentID=T1.RelDepartmentID";
                    $qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]= isset($qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL])?$qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]:" T1.*";
                    $qry_cond= \Humanres\DepartmentRelClass::getQueryCondition(array_merge($search,array("maintable"=>"T1")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL].=" ".$rel_where_cond;
                    }
                    break;
                case 2:
                    $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL." T1 on T.DepartmentID=T1.RelDepartmentParentID";
                    $qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]= isset($qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL])?$qryselect[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL]:" T1.*";
                    $qry_cond= \Humanres\DepartmentRelClass::getQueryCondition(array_merge($search,array("maintable"=>"T1")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\DepartmentRelClass::TBL_DEPARTMENT_REL].=" ".$rel_where_cond;
                    }
                    break;
            }
        }
        if(isset($search[self::TBL_DEPARTMENT_PREF.'name']) && $search[self::TBL_DEPARTMENT_PREF.'name']!=""){
            $tmp_input=$search[self::TBL_DEPARTMENT_PREF.'name'];
            $tmp_cond=$_mainTable.".DepartmentName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_DEPARTMENT_PREF.'name_cond']) && $search[self::TBL_DEPARTMENT_PREF.'name_cond']!=""){
                switch($search[self::TBL_DEPARTMENT_PREF.'name_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".DepartmentName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".DepartmentName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'classid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'classid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentClassID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentClassID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'typeid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'typeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentTypeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentTypeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'organid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'organid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentOrganID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentOrganID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'isactive'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'isactive']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentIsActive IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentIsActive ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'periodid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'periodid']);
            $tmp_input=0;
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentPeriodID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentPeriodID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentID !='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'parentid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'parentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentParentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentParentID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentID !='".$tmp_input."'";
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
        if(isset($search[self::TBL_DEPARTMENT_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_DEPARTMENT_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".DepartmentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".DepartmentID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT." T";
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
        $qry = "select COUNT(T.DepartmentID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT." (
                    DepartmentParentID,
                    DepartmentClassID,
                    DepartmentPeriodID,
                    DepartmentName,
                    DepartmentFullName,
                    DepartmentOrder,
                    DepartmentIsActive,
                    DepartmentCreatePersonID,
                    DepartmentCreateEmployeeID,
                    DepartmentCreateDate
                ) values(
                    ".(isset($_data['DepartmentParentID']) && $_data['DepartmentParentID']!=""?$_data['DepartmentParentID']:0).",
                    ".$_data['DepartmentClassID'].",
                    ".$_data['DepartmentPeriodID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['DepartmentName'])).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['DepartmentFullName'])).",
                    ".$_data['DepartmentOrder'].",
                    ".$_data['DepartmentIsActive'].",
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
        if(!isset($_data["DepartmentPeriodID"]) || $_data["DepartmentPeriodID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа.  хоосон байна');
        }
        if(!isset($_data["DepartmentClassID"]) || $_data["DepartmentClassID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ангилал сонгоогүй байна',"department[DepartmentClassID]");
        }
        if(!isset($_data["DepartmentName"]) || $_data["DepartmentName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"department[DepartmentName]");
        }
        if(!isset($_data["DepartmentFullName"]) || $_data["DepartmentFullName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"department[DepartmentFullName]");
        }
        if(!isset($_data["DepartmentOrder"]) || $_data["DepartmentOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ хоосон байна',"department[DepartmentOrder]");
        }elseif(isset($_data["DepartmentOrder"]) && !is_numeric($_data["DepartmentOrder"])){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ тоо байх ёстой',"department[DepartmentOrder]");
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
            if(isset($_data['DepartmentTypeID'])){
                $qry_update[]=" DepartmentTypeID=".$_data['DepartmentTypeID'];
            }
            if(isset($_data['DepartmentClassID'])){
                $qry_update[]=" DepartmentClassID=".$_data['DepartmentClassID'];
            }
            if(isset($_data['DepartmentName'])){
                $qry_update[]=" DepartmentName=".\System\Util::getInput(\System\Util::uniConvert($_data['DepartmentName']));
            }
            if(isset($_data['DepartmentFullName'])){
                $qry_update[]=" DepartmentFullName=".\System\Util::getInput(\System\Util::uniConvert($_data['DepartmentFullName']));
            }
            if(isset($_data['DepartmentCountJob'])){
                $qry_update[]=" DepartmentCountJob=".$_data['DepartmentCountJob'];
            }
            if(isset($_data['DepartmentOrder'])){
                $qry_update[]=" DepartmentOrder=".$_data['DepartmentOrder'];
            }
            if(isset($_data['DepartmentCountPosition'])){
                $qry_update[]=" DepartmentCountPosition=".$_data['DepartmentCountPosition'];
            }
            if(isset($_data['DepartmentCountEmployee'])){
                $qry_update[]=" DepartmentCountEmployee=".$_data['DepartmentCountEmployee'];
            }
            if(isset($_data['DepartmentCountPosition'])){
                $qry_update[]=" DepartmentCountPosition=".$_data['DepartmentCountPosition'];
            }
            if(isset($_data['DepartmentCountEmployee'])){
                $qry_update[]=" DepartmentCountEmployee=".$_data['DepartmentCountEmployee'];
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" DepartmentUpdateDate=NOW()";
            $qry_update[]=" DepartmentUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" DepartmentUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where DepartmentID= '".$this->DepartmentID."'";
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
        if(isset($_data["DepartmentClassID"]) && $_data["DepartmentClassID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Ангилал сонгоогүй байна',"department[DepartmentClassID]");
        }
        if(isset($_data["DepartmentName"]) && $_data["DepartmentName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нэр хоосон байна',"department[DepartmentName]");
        }
        if(isset($_data["DepartmentFullName"]) && $_data["DepartmentFullName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"department[DepartmentFullName]");
        }
        if(isset($_data["DepartmentOrder"]) && $_data["DepartmentOrder"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ хоосон байна',"department[DepartmentOrder]");
        }elseif(isset($_data["DepartmentOrder"]) && !is_numeric($_data["DepartmentOrder"])){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эрэмбэ тоо байх ёстой',"department[DepartmentOrder]");
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
        if($this->DepartmentID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_DEPARTMENT;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where DepartmentID= '".$this->DepartmentID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}