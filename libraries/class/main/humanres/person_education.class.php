<?php
namespace Humanres;
class PersonEducationClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_EDUCATION="person_education";
    const TBL_PERSON_EDUCATION_PREF="education_";
    
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
        return isset($this->_data["EducationID"]) && $this->_data["EducationID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'get_table']) && $search[self::TBL_PERSON_EDUCATION_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_EDUCATION_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.EducationPersonID=TPerson.PersonID";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationPersonID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationID !='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'profession']) && $search[self::TBL_PERSON_EDUCATION_PREF.'profession']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'profession'];
            $tmp_cond=$_mainTable.".EducationProfession LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'profession_cond']) && $search[self::TBL_PERSON_EDUCATION_PREF.'profession_cond']!=""){
                switch($search[self::TBL_PERSON_EDUCATION_PREF.'profession_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".EducationProfession = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".EducationProfession like '%" .$tmp_input. "%'";
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
        
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle']) && $search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle'];
            $tmp_cond=$_mainTable.".EducationSchoolTitle LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle_cond']) && $search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle_cond']!=""){
                switch($search[self::TBL_PERSON_EDUCATION_PREF.'schooltitle_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".EducationSchoolTitle = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".EducationSchoolTitle like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'licence']) && $search[self::TBL_PERSON_EDUCATION_PREF.'licence']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'licence'];
            $tmp_cond=$_mainTable.".EducationLicence LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'licence_cond']) && $search[self::TBL_PERSON_EDUCATION_PREF.'licence_cond']!=""){
                switch($search[self::TBL_PERSON_EDUCATION_PREF.'licence_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".EducationLicence = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".EducationLicence like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'enddatestart']) && $search[self::TBL_PERSON_EDUCATION_PREF.'enddatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'enddatestart'];
            $tmp_cond=$_mainTable.".EducationDateEnd >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'enddateend']) && $search[self::TBL_PERSON_EDUCATION_PREF.'enddateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'enddateend'];
            $tmp_cond=$_mainTable.".EducationDateEnd <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'startdatestart']) && $search[self::TBL_PERSON_EDUCATION_PREF.'startdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'startdatestart'];
            $tmp_cond=$_mainTable.".EducationDateStart >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'startdateend']) && $search[self::TBL_PERSON_EDUCATION_PREF.'startdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_EDUCATION_PREF.'startdateend'];
            $tmp_cond=$_mainTable.".EducationDateStart <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'isnow'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'isnow']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationIsNow IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationIsNow ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'schoolid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'schoolid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationSchoolID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationSchoolID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'degreeid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'degreeid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationDegreeID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationDegreeID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'levelid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'levelid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationLevelID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationLevelID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_EDUCATION_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_EDUCATION_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".EducationID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".EducationID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDUCATION." T";
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
        $qry = "select COUNT(T.EducationID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDUCATION." (
                    EducationPersonID,
                    EducationLevelID,
                    EducationDegreeID,
                    EducationSchoolID,
                    EducationSchoolTitle,
                    EducationIsNow,
                    EducationDateStart,
                    EducationDateEnd,
                    EducationProfession,
                    EducationLicence,
                    EducationCreatePersonID,
                    EducationCreateEmployeeID,
                    EducationCreateDate
                ) values(
                    ".$_data['EducationPersonID'].",
                    ".$_data['EducationLevelID'].",
                    ".(isset($_data['EducationDegreeID'])?$_data['EducationDegreeID']:0).",
                    ".$_data['EducationSchoolID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['EducationSchoolTitle'])).",
                    ".(isset($_data['EducationIsNow'])?$_data['EducationIsNow']:0).",
                    ".\System\Util::getInput($_data['EducationDateStart']).",
                    ".\System\Util::getInput(isset($_data['EducationDateEnd'])?$_data['EducationDateEnd']:"").",
                    ".\System\Util::getInput(isset($_data['EducationProfession'])?\System\Util::uniConvert($_data['EducationProfession']):"").",
                    ".\System\Util::getInput(isset($_data['EducationLicence'])?\System\Util::uniConvert($_data['EducationLicence']):"").",
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
        if(!isset($_data["EducationPersonID"]) || $_data["EducationPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Албан хаагч сонгогдоогүй байна');
        }
        if(!isset($_data["EducationLevelID"]) || $_data["EducationLevelID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"education[EducationLevelID]");
        }
        if(!isset($_data["EducationSchoolID"]) || $_data["EducationSchoolID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"education[EducationSchoolID]");
        }
        if(!isset($_data["EducationSchoolTitle"]) || $_data["EducationSchoolTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationSchoolTitle]");
        }
        if(!isset($_data["EducationDateStart"]) || $_data["EducationDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationDateStart]");
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
           
            if(isset($_data['EducationLevelID'])){
                $qry_update[]=" EducationLevelID=".$_data['EducationLevelID'];
            }
            if(isset($_data['EducationDegreeID'])){
                $qry_update[]=" EducationDegreeID=".$_data['EducationDegreeID'];
            }
            if(isset($_data['EducationSchoolID'])){
                $qry_update[]=" EducationSchoolID=".$_data['EducationSchoolID'];
            }
            if(isset($_data['EducationIsNow'])){
                $qry_update[]=" EducationIsNow=".$_data['EducationIsNow'];
            }
            if(isset($_data['EducationSchoolTitle'])){
                $qry_update[]=" EducationSchoolTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['EducationSchoolTitle']));
            }
            if(isset($_data['EducationDateStart'])){
                $qry_update[]=" EducationDateStart=".\System\Util::getInput($_data['EducationDateStart']);
            }
            if(isset($_data['EducationDateEnd'])){
                $qry_update[]=" EducationDateEnd=".\System\Util::getInput($_data['EducationDateEnd']);
            }
            if(isset($_data['EducationProfession'])){
                $qry_update[]=" EducationProfession=".\System\Util::getInput(\System\Util::uniConvert($_data['EducationProfession']));
            }
            if(isset($_data['EducationLicence'])){
                $qry_update[]=" EducationLicence=".\System\Util::getInput(\System\Util::uniConvert($_data['EducationLicence']));
            }
            
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" EducationUpdateDate=NOW()";
            $qry_update[]=" EducationUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" EducationUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDUCATION." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where EducationID= '".$this->EducationID."'";
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
       
        if(isset($_data["EducationLevelID"]) && $_data["EducationLevelID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"education[EducationLevelID]");
        }
        if(isset($_data["EducationDegreeID"]) && $_data["EducationDegreeID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"education[EducationDegreeID]");
        }
        if(isset($_data["EducationSchoolID"]) && $_data["EducationSchoolID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"education[EducationSchoolID]");
        }
        if(isset($_data["EducationSchoolTitle"]) && $_data["EducationSchoolTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationSchoolTitle]");
        }
        if(isset($_data["EducationDateStart"]) && $_data["EducationDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationDateStart]");
        }
        if(isset($_data["EducationProfession"]) && $_data["EducationProfession"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationProfession]");
        }
        if(isset($_data["EducationLicence"]) && $_data["EducationLicence"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"education[EducationLicence]");
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
        if($this->EducationID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_EDUCATION;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where EducationID= '".$this->EducationID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}