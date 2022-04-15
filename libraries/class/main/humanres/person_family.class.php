<?php
namespace Humanres;
class PersonFamilyClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_FAMILY="person_family";
    const TBL_PERSON_FAMILY_PREF="family_";
    
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
        return isset($this->_data["FamilyID"]) && $this->_data["FamilyID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'get_table']) && $search[self::TBL_PERSON_FAMILY_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_FAMILY_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.FamilyPersonID=TPerson.PersonID";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'jobposition']) && $search[self::TBL_PERSON_FAMILY_PREF.'jobposition']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'jobposition'];
            $tmp_cond=$_mainTable.".FamilyJobPosition LIKE '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'joborgan']) && $search[self::TBL_PERSON_FAMILY_PREF.'joborgan']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'joborgan'];
            $tmp_cond=$_mainTable.".FamilyJobOrgan LIKE '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'birthdatestart']) && $search[self::TBL_PERSON_FAMILY_PREF.'birthdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'birthdatestart'];
            $tmp_cond=$_mainTable.".FamilyBirthDate >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'birthdateend']) && $search[self::TBL_PERSON_FAMILY_PREF.'birthdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'birthdateend'];
            $tmp_cond=$_mainTable.".FamilyBirthDate <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'birthcityid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'birthcityid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyBirthCityID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyBirthCityID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'firstname']) && $search[self::TBL_PERSON_FAMILY_PREF.'firstname']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'firstname'];
            $tmp_cond=$_mainTable.".FamilyFirstName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_FAMILY_PREF.'firstname_cond']) && $search[self::TBL_PERSON_FAMILY_PREF.'firstname_cond']!=""){
                switch($search[self::TBL_PERSON_FAMILY_PREF.'firstname_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".FamilyFirstName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".FamilyFirstName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'lastname']) && $search[self::TBL_PERSON_FAMILY_PREF.'lastname']!=""){
            $tmp_input=$search[self::TBL_PERSON_FAMILY_PREF.'lastname'];
            $tmp_cond=$_mainTable.".FamilyLastName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_FAMILY_PREF.'lastname_cond']) && $search[self::TBL_PERSON_FAMILY_PREF.'lastname_cond']!=""){
                switch($search[self::TBL_PERSON_FAMILY_PREF.'lastname_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".FamilyLastName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".FamilyLastName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'jobtypeid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'jobtypeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyJobTypeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyJobTypeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'relationid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'relationid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyRelationID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyRelationID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyPersonID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyID !='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_FAMILY_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_FAMILY_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".FamilyID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".FamilyID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_FAMILY." T";
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
        $qry = "select COUNT(T.FamilyID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_FAMILY." (
                    FamilyPersonID,
                    FamilyRelationID,
                    FamilyJobTypeID,
                    FamilyBirthIsAbroad,
                    FamilyBirthCountryID,
                    FamilyBirthCityID,
                    FamilyBirthDistrictID,
                    FamilyLastName,
                    FamilyFirstName,
                    FamilyBirthDate,
                    FamilyJobOrgan,
                    FamilyJobPosition,
                    FamilyCreatePersonID,
                    FamilyCreateEmployeeID,
                    FamilyCreateDate
                ) values(
                    ".$_data['FamilyPersonID'].",
                    ".$_data['FamilyRelationID'].",
                    ".$_data['FamilyJobTypeID'].",
                    ".(isset($_data['FamilyBirthIsAbroad'])?$_data['FamilyBirthIsAbroad']:0).",
                    ".(isset($_data['FamilyBirthCountryID'])?$_data['FamilyBirthCountryID']:0).",
                    ".(isset($_data['FamilyBirthCityID'])?$_data['FamilyBirthCityID']:0).",
                    ".(isset($_data['FamilyBirthDistrictID'])?$_data['FamilyBirthDistrictID']:0).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyLastName'])).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyFirstName'])).",
                    ".\System\Util::getInput($_data['FamilyBirthDate']).",
                    ".\System\Util::getInput(isset($_data['FamilyJobOrgan'])?\System\Util::uniConvert($_data['FamilyJobOrgan']):"").",
                    ".\System\Util::getInput(isset($_data['FamilyJobPosition'])?\System\Util::uniConvert($_data['FamilyJobPosition']):"").",
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
        if(!isset($_data["FamilyPersonID"]) || $_data["FamilyPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Албан хаагч сонгогдоогүй байна');
        }
        if(!isset($_data["FamilyRelationID"]) || $_data["FamilyRelationID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyRelationID]");
        }
        if(!isset($_data["FamilyJobTypeID"]) || $_data["FamilyJobTypeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyJobTypeID]");
        }
        if(!isset($_data["FamilyLastName"]) || $_data["FamilyLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyLastName]");
        }
        if(!isset($_data["FamilyFirstName"]) || $_data["FamilyFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyFirstName]");
        }
        if(!isset($_data["FamilyBirthDate"]) || $_data["FamilyBirthDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyBirthDate]");
        }
        if(isset($_data['FamilyBirthIsAbroad']) && $_data['FamilyBirthIsAbroad']){
            if(isset($_data["FamilyBirthCountryID"]) && $_data["FamilyBirthCountryID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyBirthCountryID]");
            }
        }else{
            if(isset($_data["FamilyBirthCityID"]) && $_data["FamilyBirthCityID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyBirthCityID]");
            }
            if(isset($_data["FamilyBirthDistrictID"]) && $_data["FamilyBirthDistrictID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyBirthDistrictID]");
            }
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
           
            if(isset($_data['FamilyRelationID'])){
                $qry_update[]=" FamilyRelationID=".$_data['FamilyRelationID'];
            }
            if(isset($_data['FamilyJobTypeID'])){
                $qry_update[]=" FamilyJobTypeID=".$_data['FamilyJobTypeID'];
            }
            if(isset($_data['FamilyBirthIsAbroad'])){
                $qry_update[]=" FamilyBirthIsAbroad=".$_data['FamilyBirthIsAbroad'];
                if($_data['FamilyBirthIsAbroad']){
                    $qry_update[]=" FamilyBirthCountryID=".$_data['FamilyBirthCountryID'];
                    $qry_update[]=" FamilyBirthCityID=0";
                    $qry_update[]=" FamilyBirthDistrictID=0";
                }else{
                    $qry_update[]=" FamilyBirthCountryID=0";
                    $qry_update[]=" FamilyBirthCityID=".$_data['FamilyBirthCityID'];
                    $qry_update[]=" FamilyBirthDistrictID=".$_data['FamilyBirthDistrictID'];
                }
            }
            if(isset($_data['FamilyLastName'])){
                $qry_update[]=" FamilyLastName=".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyLastName']));
            }
            if(isset($_data['FamilyFirstName'])){
                $qry_update[]=" FamilyFirstName=".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyFirstName']));
            }
            if(isset($_data['FamilyJobOrgan'])){
                $qry_update[]=" FamilyJobOrgan=".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyJobOrgan']));
            }
            if(isset($_data['FamilyJobPosition'])){
                $qry_update[]=" FamilyJobPosition=".\System\Util::getInput(\System\Util::uniConvert($_data['FamilyJobPosition']));
            }
            if(isset($_data['FamilyBirthDate'])){
                $qry_update[]=" FamilyBirthDate=".\System\Util::getInput($_data['FamilyBirthDate']);
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" FamilyUpdateDate=NOW()";
            $qry_update[]=" FamilyUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" FamilyUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_FAMILY." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where FamilyID= '".$this->FamilyID."'";
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
        if(isset($_data["FamilyRelationID"]) && $_data["FamilyRelationID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyRelationID]");
        }
        if(isset($_data["FamilyJobTypeID"]) && $_data["FamilyJobTypeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyJobTypeID]");
        }
        if(isset($_data["FamilyBirthCityID"]) && $_data["FamilyBirthCityID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"family[FamilyBirthCityID]");
        }
        if(isset($_data["FamilyBirthDistrictID"]) && $_data["FamilyBirthDistrictID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyBirthDistrictID]");
        }
        if(isset($_data["FamilyLastName"]) && $_data["FamilyLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyLastName]");
        }
        if(isset($_data["FamilyFirstName"]) && $_data["FamilyFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyFirstName]");
        }
        if(isset($_data["FamilyBirthDate"]) && $_data["FamilyBirthDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"family[FamilyBirthDate]");
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
        if($this->FamilyID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_FAMILY;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where FamilyID= '".$this->FamilyID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}