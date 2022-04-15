<?php
namespace Humanres;
class EmployeeClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_EMPLOYEE="employee";
    const TBL_EMPLOYEE_PREF="employee_";

    public function __construct() {
        $this->con=\Database::instance();
        parent::__construct();
    }
 
    public function __get($name){
        switch ($name){
            case "PersonLFName":
                return isset($this->_data['PersonLastLetter']) && isset($this->_data['PersonFirstName'])?($this->_data['PersonLastLetter'].".".$this->_data['PersonFirstName']):"";
                break;
            case "PersonFLName":
                return isset($this->_data['PersonLastLetter']) && isset($this->_data['PersonFirstName'])?($this->_data['PersonFirstName'].".".$this->_data['PersonLastLetter']):"";
                break;
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
        return isset($this->_data["EmployeeID"]) && $this->_data["EmployeeID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[self::TBL_EMPLOYEE_PREF.'get_table']) && $search[self::TBL_EMPLOYEE_PREF.'get_table']!=""){
            switch ($search[self::TBL_EMPLOYEE_PREF.'get_table']){
                case 3:
                    $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." T1 on T.EmployeeDepartmentID=T1.DepartmentID";
                    $qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT]= isset($qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT])?$qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT]:" T1.*";
                    $qry_cond= \Humanres\DepartmentClass::getQueryCondition(array_merge($search,array("maintable"=>"T1")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT].=" and ".$rel_join_inner_cond;
                        if($rel_where_cond!="")
                            $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT].=" ".$rel_where_cond;
                    }
                    $join_inner[\Humanres\PersonClass::TBL_PERSON]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." T2 on T.EmployeePersonID=T2.PersonID";
                    $qryselect[\Humanres\PersonClass::TBL_PERSON]= isset($qryselect[\Humanres\PersonClass::TBL_PERSON])?$qryselect[\Humanres\PersonClass::TBL_PERSON]:" T2.*";
                    $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"T2")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" and ".$rel_join_inner_cond;
                        if($rel_where_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" ".$rel_where_cond;
                    }
                    break;
                case 4:
                    $join_inner[\Humanres\PersonClass::TBL_PERSON]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." T2 on T.EmployeePersonID=T2.PersonID";
                    $qryselect[\Humanres\PersonClass::TBL_PERSON]= isset($qryselect[\Humanres\PersonClass::TBL_PERSON])?$qryselect[\Humanres\PersonClass::TBL_PERSON]:" T2.*";
                    $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"T2")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" and ".$rel_join_inner_cond;
                        if($rel_where_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" ".$rel_where_cond;
                    }
                    break;
                case 5:
                    $join_inner[\Humanres\PositionClass::TBL_POSITION]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." T1 on T.EmployeePositionID=T1.PositionID";
                    $qryselect[\Humanres\PositionClass::TBL_POSITION]= isset($qryselect[\Humanres\PositionClass::TBL_POSITION])?$qryselect[\Humanres\PositionClass::TBL_POSITION]:" T1.*";
                    $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"T1")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\PositionClass::TBL_POSITION].=" and ".$rel_join_inner_cond;
                        if($rel_where_cond!="")
                            $join_inner[\Humanres\PositionClass::TBL_POSITION].=" ".$rel_where_cond;
                    }
                    $join_inner[\Humanres\PersonClass::TBL_PERSON]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." T2 on T.EmployeePersonID=T2.PersonID";
                    $qryselect[\Humanres\PersonClass::TBL_PERSON]= isset($qryselect[\Humanres\PersonClass::TBL_PERSON])?$qryselect[\Humanres\PersonClass::TBL_PERSON]:" T2.*";
                    $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"T2")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" and ".$rel_join_inner_cond;
                        if($rel_where_cond!="")
                            $join_inner[\Humanres\PersonClass::TBL_PERSON].=" ".$rel_where_cond;
                    }
                    break;
                case 6:
                        $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." T3 on T.EmployeeDepartmentID=T3.DepartmentID";
                        $qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT]= isset($qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT])?$qryselect[\Humanres\DepartmentClass::TBL_DEPARTMENT]:" T3.*";
                        $qry_cond= \Humanres\DepartmentClass::getQueryCondition(array_merge($search,array("maintable"=>"T3")) );
                        $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                        $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                        if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                            if($rel_join_inner_cond!="")
                                $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT].=" ".$rel_where_cond;
                        }
                        $join_inner[\Humanres\PositionClass::TBL_POSITION]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." T1 on T.EmployeePositionID=T1.PositionID";
                        $qryselect[\Humanres\PositionClass::TBL_POSITION]= isset($qryselect[\Humanres\PositionClass::TBL_POSITION])?$qryselect[\Humanres\PositionClass::TBL_POSITION]:" T1.*";
                        $qry_cond= \Humanres\PositionClass::getQueryCondition(array_merge($search,array("maintable"=>"T1")) );
                        $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                        $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                        if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                            if($rel_join_inner_cond!="")
                                $join_inner[\Humanres\PositionClass::TBL_POSITION].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\PositionClass::TBL_POSITION].=" ".$rel_where_cond;
                        }
                        $join_inner[\Humanres\PersonClass::TBL_PERSON]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PersonClass::TBL_PERSON." T2 on T.EmployeePersonID=T2.PersonID";
                        $qryselect[\Humanres\PersonClass::TBL_PERSON]= isset($qryselect[\Humanres\PersonClass::TBL_PERSON])?$qryselect[\Humanres\PersonClass::TBL_PERSON]:" T2.*";
                        $qry_cond= \Humanres\PersonClass::getQueryCondition(array_merge($search,array("maintable"=>"T2")) );
                        $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                        $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                        if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                            if($rel_join_inner_cond!="")
                                $join_inner[\Humanres\PersonClass::TBL_PERSON].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\PersonClass::TBL_PERSON].=" ".$rel_where_cond;
                        }
                        break;
            }
        }
       
        if(isset($search[self::TBL_EMPLOYEE_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_EMPLOYEE_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EmployeePersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EmployeePersonID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_EMPLOYEE_PREF.'isactive'])){
            $tmp_input=self::getParam($search[self::TBL_EMPLOYEE_PREF.'isactive']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EmployeeIsActive IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EmployeeIsActive ='".$tmp_input."'";
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
        if(isset($search[self::TBL_EMPLOYEE_PREF.'departmentid'])){
            $tmp_input=self::getParam($search[self::TBL_EMPLOYEE_PREF.'departmentid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EmployeeDepartmentID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EmployeeDepartmentID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_EMPLOYEE_PREF.'positionid'])){
            $tmp_input=self::getParam($search[self::TBL_EMPLOYEE_PREF.'positionid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EmployeePositionID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EmployeePositionID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_EMPLOYEE_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_EMPLOYEE_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EmployeeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EmployeeID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_EMPLOYEE." T";
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
        $qry = "select COUNT(T.EmployeeID) as AllCount"; 
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_EMPLOYEE." (
                    EmployeePersonID,
                    EmployeeDepartmentID,
                    EmployeePositionID,
                    EmployeeIsActive,
                    EmployeeStartID,
                    EmployeeStartDate,
                    EmployeeStartOrderNo,
                    EmployeeStartOrderDate,
                    EmployeeCreatePersonID,
                    EmployeeCreateEmployeeID,
                    EmployeeCreateDate
                ) values(
                    ".$_data['EmployeePersonID'].",
                    ".$_data['EmployeeDepartmentID'].",
                    ".$_data['EmployeePositionID'].",
                    1,
                    ".$_data['EmployeeStartID'].",
                    ".\System\Util::getInput($_data['EmployeeStartDate']).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['EmployeeStartOrderNo'])).",
                    ".\System\Util::getInput($_data['EmployeeStartOrderDate']).",
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
        if(!isset($_data["EmployeePersonID"]) || $_data["EmployeePersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Бүртгэл хоосон байна');
        }
        if(!isset($_data["EmployeeDepartmentID"]) || $_data["EmployeeDepartmentID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Бүтцийн нэгж хоосон байна');
        }
        if(!isset($_data["EmployeePositionID"]) || $_data["EmployeePositionID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Албан тушаал хоосон байна');
        }
        if(!isset($_data["EmployeeStartID"]) || $_data["EmployeeStartID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Томилсон байдал сонгоогүй байна',"employee[EmployeeStartID]");
        }
        if(!isset($_data["EmployeeStartDate"]) || $_data["EmployeeStartDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Томилогдсон огноо сонгоогүй байна',"employee[EmployeeStartDate]");
        }
        if(!isset($_data["EmployeeStartOrderNo"]) || $_data["EmployeeStartOrderNo"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Тушаалын дугаар хоосон байна',"employee[EmployeeStartOrderNo]");
        }
        if(!isset($_data["EmployeeStartOrderDate"]) || $_data["EmployeeStartOrderDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Тушаалын огноо хоосон байна',"employee[EmployeeStartOrderDate]");
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
            
            if(isset($_data['EmployeeIsActive'])){
                $qry_update[]=" EmployeeIsActive=".$_data['EmployeeIsActive'];
            }
            if(isset($_data['EmployeeStartID'])){
                $qry_update[]=" EmployeeStartID=".$_data['EmployeeStartID'];
            }
            if(isset($_data['EmployeeStartDate'])){
                $qry_update[]=" EmployeeStartDate=".\System\Util::getInput($_data['EmployeeStartDate']);
            }
            if(isset($_data['EmployeeStartOrderNo'])){
                $qry_update[]=" EmployeeStartOrderNo=".\System\Util::getInput($_data['EmployeeStartOrderNo']);
            }
            if(isset($_data['EmployeeStartOrderDate'])){
                $qry_update[]=" EmployeeStartOrderDate=".\System\Util::getInput($_data['EmployeeStartOrderDate']);
            }
            if(isset($_data['EmployeeQuitID'])){
                $qry_update[]=" EmployeeQuitID=".\System\Util::getInput($_data['EmployeeQuitID'],\System\Util::INPUT_NONTEXT);
            }
            if(isset($_data['EmployeeQuitSubID'])){
                $qry_update[]=" EmployeeQuitSubID=".\System\Util::getInput($_data['EmployeeQuitSubID'],\System\Util::INPUT_NONTEXT);
            }
            if(isset($_data['EmployeeQuitDate'])){
                $qry_update[]=" EmployeeQuitDate=".\System\Util::getInput($_data['EmployeeQuitDate']);
            }
            if(isset($_data['EmployeeQuitOrderNo'])){
                $qry_update[]=" EmployeeQuitOrderNo=".\System\Util::getInput($_data['EmployeeQuitOrderNo']);
            }
            if(isset($_data['EmployeeQuitOrderDate'])){
                $qry_update[]=" EmployeeQuitOrderDate=".\System\Util::getInput($_data['EmployeeQuitOrderDate']);
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" EmployeeUpdateDate=NOW()";
            $qry_update[]=" EmployeeUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" EmployeeUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_EMPLOYEE." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
                $qry.=" where EmployeeID= '".$this->EmployeeID."'";
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
      
        return !$this->hasError();
    }
    function deleteRow($_data=array()){
        $this->clearError();
        if($this->EmployeeID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_EMPLOYEE;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where EmployeeID= '".$this->EmployeeID."'";
        else $qry.=" where ". implode(" and ", $_data['cond']);
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
            return false;
        }
        return true;
    }
    
    function transferAllData($fromEmpID=0, $toEmpID=0){
        $fromEmpObj=self::getInstance()->getRow(["employee_get_table"=>6,'employee_id'=>$fromEmpID]);
        $toEmpObj=self::getInstance()->getRow(["employee_get_table"=>6,'employee_id'=>$toEmpID]);
        if(!$fromEmpObj->isExist() || !$toEmpObj->isExist()){
            $this->addError(\System\Error::ERROR_DB, 'Мэдээлэл дутуу байна. Info:: '.$this->con->getError());
            return false;
        }
        $from_empid=$fromEmpID;
        $to_depid=$toEmpObj->EmployeeDepartmentID;
        $to_empid=$toEmpID;
        
        $qry=" update ".DB_DATABASE.".".\Office\FelonyClass::TBL_FELONY." set FelonyMainDepartmentID={$to_depid}, FelonyMainEmployeeID={$to_empid} where FelonyMainEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\FelonyDetectiveClass::TBL_FELONY_DETECTIVE." set DetectiveDepartmentID={$to_depid}, DetectiveEmployeeID={$to_empid} where DetectiveEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\LetterClass::TBL_LETTER." set LetterToDepartmentID={$to_depid}, LetterToEmployeeID={$to_empid} where LetterToEmployeeID={$from_empid}; "; 
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\LetterClass::TBL_LETTER." set LetterAdminDepartmentID={$to_depid}, LetterAdminEmployeeID={$to_empid} where LetterAdminEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\LetterClass::TBL_LETTER." set LetterShiftLastDepartmentID={$to_depid}, LetterShiftLastEmployeeID={$to_empid} where LetterShiftLastEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\LetterShiftClass::TBL_LETTER_SHIFT." set ShiftFromDepartmentID={$to_depid}, ShiftFromEmployeeID={$to_empid} where ShiftFromEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\LetterShiftClass::TBL_LETTER_SHIFT." set ShiftToDepartmentID={$to_depid}, ShiftToEmployeeID={$to_empid} where ShiftToEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\PetitionClass::TBL_PETITION." set PetitionToDepartmentID={$to_depid}, PetitionToEmployeeID={$to_empid} where PetitionToEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\PetitionClass::TBL_PETITION." set PetitionAdminDepartmentID={$to_depid}, PetitionAdminEmployeeID={$to_empid} where PetitionAdminEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\PetitionClass::TBL_PETITION." set PetitionShiftLastDepartmentID={$to_depid}, PetitionShiftLastEmployeeID={$to_empid} where PetitionShiftLastEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\PetitionShiftClass::TBL_PETITION_SHIFT." set ShiftFromDepartmentID={$to_depid}, ShiftFromEmployeeID={$to_empid} where ShiftFromEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\PetitionShiftClass::TBL_PETITION_SHIFT." set ShiftToDepartmentID={$to_depid}, ShiftToEmployeeID={$to_empid} where ShiftToEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        $qry=" update ".DB_DATABASE.".".\Office\SentLetterClass::TBL_SENTLETTER." set SentLetterFromDepartmentID={$to_depid}, SentLetterFromEmployeeID={$to_empid} where SentLetterFromEmployeeID={$from_empid}; ";
        $res = $this->con->qryexec($qry);
        if($res<0){
            $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийн санд засаж хадгалж чадсангүй. Info::'.$this->con->getError());
            return false;
        }
        
        return true;
    }
}