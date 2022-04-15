<?php
namespace Humanres;
class PersonRelationClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_RELATION="person_relation";
    const TBL_PERSON_RELATION_PREF="relation_";
    
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
        return isset($this->_data["RelationID"]) && $this->_data["RelationID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'get_table']) && $search[self::TBL_PERSON_RELATION_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_RELATION_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.RelationPersonID=TPerson.PersonID";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'jobposition']) && $search[self::TBL_PERSON_RELATION_PREF.'jobposition']!=""){
            $tmp_input=$search[self::TBL_PERSON_RELATION_PREF.'jobposition'];
            $tmp_cond=$_mainTable.".RelationJobPosition LIKE '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'joborgan']) && $search[self::TBL_PERSON_RELATION_PREF.'joborgan']!=""){
            $tmp_input=$search[self::TBL_PERSON_RELATION_PREF.'joborgan'];
            $tmp_cond=$_mainTable.".RelationJobOrgan LIKE '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'firstname']) && $search[self::TBL_PERSON_RELATION_PREF.'firstname']!=""){
            $tmp_input=$search[self::TBL_PERSON_RELATION_PREF.'firstname'];
            $tmp_cond=$_mainTable.".RelationFirstName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_RELATION_PREF.'firstname_cond']) && $search[self::TBL_PERSON_RELATION_PREF.'firstname_cond']!=""){
                switch($search[self::TBL_PERSON_RELATION_PREF.'firstname_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".RelationFirstName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".RelationFirstName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'lastname']) && $search[self::TBL_PERSON_RELATION_PREF.'lastname']!=""){
            $tmp_input=$search[self::TBL_PERSON_RELATION_PREF.'lastname'];
            $tmp_cond=$_mainTable.".RelationLastName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_RELATION_PREF.'lastname_cond']) && $search[self::TBL_PERSON_RELATION_PREF.'lastname_cond']!=""){
                switch($search[self::TBL_PERSON_RELATION_PREF.'lastname_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".RelationLastName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".RelationLastName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'jobtypeid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_RELATION_PREF.'jobtypeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RelationJobTypeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RelationJobTypeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'relationid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_RELATION_PREF.'relationid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RelationRelationID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RelationRelationID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_RELATION_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RelationPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RelationPersonID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_RELATION_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RelationID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RelationID !='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_RELATION_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_RELATION_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RelationID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RelationID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_RELATION." T";
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
        $qry = "select COUNT(T.RelationID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_RELATION." (
                    RelationPersonID,
                    RelationRelationID,
                    RelationJobTypeID,
                    RelationLastName,
                    RelationFirstName,
                    RelationJobOrgan,
                    RelationJobPosition,
                    RelationCreatePersonID,
                    RelationCreateEmployeeID,
                    RelationCreateDate
                ) values(
                    ".$_data['RelationPersonID'].",
                    ".$_data['RelationRelationID'].",
                    ".$_data['RelationJobTypeID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['RelationLastName'])).",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['RelationFirstName'])).",
                    ".\System\Util::getInput(isset($_data['RelationJobOrgan'])?\System\Util::uniConvert($_data['RelationJobOrgan']):"").",
                    ".\System\Util::getInput(isset($_data['RelationJobPosition'])?\System\Util::uniConvert($_data['RelationJobPosition']):"").",
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
        if(!isset($_data["RelationPersonID"]) || $_data["RelationPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Албан хаагч сонгогдоогүй байна');
        }
        if(!isset($_data["RelationRelationID"]) || $_data["RelationRelationID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"relation[RelationRelationID]");
        }
        if(!isset($_data["RelationJobTypeID"]) || $_data["RelationJobTypeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"relation[RelationJobTypeID]");
        }
        if(!isset($_data["RelationLastName"]) || $_data["RelationLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"relation[RelationLastName]");
        }
        if(!isset($_data["RelationFirstName"]) || $_data["RelationFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"relation[RelationFirstName]");
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
           
            if(isset($_data['RelationRelationID'])){
                $qry_update[]=" RelationRelationID=".$_data['RelationRelationID'];
            }
            if(isset($_data['RelationJobTypeID'])){
                $qry_update[]=" RelationJobTypeID=".$_data['RelationJobTypeID'];
            }
            if(isset($_data['RelationLastName'])){
                $qry_update[]=" RelationLastName=".\System\Util::getInput(\System\Util::uniConvert($_data['RelationLastName']));
            }
            if(isset($_data['RelationFirstName'])){
                $qry_update[]=" RelationFirstName=".\System\Util::getInput(\System\Util::uniConvert($_data['RelationFirstName']));
            }
            if(isset($_data['RelationJobOrgan'])){
                $qry_update[]=" RelationJobOrgan=".\System\Util::getInput(\System\Util::uniConvert($_data['RelationJobOrgan']));
            }
            if(isset($_data['RelationJobPosition'])){
                $qry_update[]=" RelationJobPosition=".\System\Util::getInput(\System\Util::uniConvert($_data['RelationJobPosition']));
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" RelationUpdateDate=NOW()";
            $qry_update[]=" RelationUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" RelationUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_RELATION." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where RelationID= '".$this->RelationID."'";
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
        if(isset($_data["RelationRelationID"]) && $_data["RelationRelationID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"relation[RelationRelationID]");
        }
        if(isset($_data["RelationJobTypeID"]) && $_data["RelationJobTypeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"relation[RelationJobTypeID]");
        }
        if(isset($_data["RelationLastName"]) && $_data["RelationLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"relation[RelationLastName]");
        }
        if(isset($_data["RelationFirstName"]) && $_data["RelationFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"relation[RelationFirstName]");
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
        if($this->RelationID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_RELATION;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where RelationID= '".$this->RelationID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}