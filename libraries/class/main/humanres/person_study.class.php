<?php
namespace Humanres;
class PersonStudyClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON_STUDY="person_study";
    const TBL_PERSON_STUDY_PREF="study_";
    
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
        return isset($this->_data["StudyID"]) && $this->_data["StudyID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'get_table']) && $search[self::TBL_PERSON_STUDY_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_STUDY_PREF.'get_table']){
                case 1:
                    $tmpTable=\Humanres\PersonClass::TBL_PERSON;
                    $join_inner[$tmpTable]=" inner join ".DB_DATABASE_HUMANRES.".". $tmpTable." TPerson on T.StudyPersonID=TPerson.PersonID";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'personid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'personid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyPersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyPersonID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'licence']) && $search[self::TBL_PERSON_STUDY_PREF.'licence']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'licence'];
            $tmp_cond=$_mainTable.".StudyLicence LIKE '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'title']) && $search[self::TBL_PERSON_STUDY_PREF.'title']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'title'];
            $tmp_cond=$_mainTable.".StudyTitle LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_STUDY_PREF.'title_cond']) && $search[self::TBL_PERSON_STUDY_PREF.'title_cond']!=""){
                switch($search[self::TBL_PERSON_STUDY_PREF.'title_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".StudyTitle = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".StudyTitle like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'schooltitle']) && $search[self::TBL_PERSON_STUDY_PREF.'schooltitle']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'schooltitle'];
            $tmp_cond=$_mainTable.".StudySchoolTitle LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_STUDY_PREF.'schooltitle_cond']) && $search[self::TBL_PERSON_STUDY_PREF.'schooltitle_cond']!=""){
                switch($search[self::TBL_PERSON_STUDY_PREF.'schooltitle_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".StudySchoolTitle = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".StudySchoolTitle like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'countryid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'countryid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyCountryID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyCountryID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'directionid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'directionid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyDirectionID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyDirectionID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'dirsubid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'dirsubid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyDirSubID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyDirSubID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'dirsub1id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'dirsub1id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyDirSub1ID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyDirSub1ID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'enddatestart']) && $search[self::TBL_PERSON_STUDY_PREF.'enddatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'enddatestart'];
            $tmp_cond=$_mainTable.".StudyDateEnd >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'enddateend']) && $search[self::TBL_PERSON_STUDY_PREF.'enddateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'enddateend'];
            $tmp_cond=$_mainTable.".StudyDateEnd <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'startdatestart']) && $search[self::TBL_PERSON_STUDY_PREF.'startdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'startdatestart'];
            $tmp_cond=$_mainTable.".StudyDateStart >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'startdateend']) && $search[self::TBL_PERSON_STUDY_PREF.'startdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_STUDY_PREF.'startdateend'];
            $tmp_cond=$_mainTable.".StudyDateStart <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyID !='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_STUDY_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_STUDY_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".StudyID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".StudyID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_STUDY." T";
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
        $qry = "select COUNT(T.StudyID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_STUDY." (
                    StudyPersonID,
                    StudyDirectionID,
                    StudyDirSubID,
                    StudyDirSub1ID,
                    StudyCountryID,
                    StudySchoolTitle,
                    StudyDateStart,
                    StudyDateEnd,
                    StudyDay,
                    StudyTitle,
                    StudyDescr,
                    StudyLicence,
                    StudyLicenceDate,
                    StudyCreatePersonID,
                    StudyCreateEmployeeID,
                    StudyCreateDate
                ) values(
                    ".$_data['StudyPersonID'].",
                    ".$_data['StudyDirectionID'].",
                    ".\System\Util::getInput(isset($_data['StudyDirSubID'])?$_data['StudyDirSubID']:"").",
                    ".\System\Util::getInput(isset($_data['StudyDirSub1ID'])?$_data['StudyDirSub1ID']:"").",
                    ".$_data['StudyCountryID'].",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['StudySchoolTitle'])).",
                    ".\System\Util::getInput($_data['StudyDateStart']).",
                    ".\System\Util::getInput($_data['StudyDateEnd']).",
                    DATEDIFF('".$_data['StudyDateEnd']."', '".$_data['StudyDateStart']."'),
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['StudyTitle'])).",
                    ".\System\Util::getInput(isset($_data['StudyDescr'])?\System\Util::uniConvert($_data['StudyDescr']):"").",
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['StudyLicence'])).",
                    ".\System\Util::getInput($_data['StudyLicenceDate']).",
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
        if(!isset($_data["StudyPersonID"]) || $_data["StudyPersonID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Албан хаагч сонгогдоогүй байна');
        }
        if(!isset($_data["StudyDirectionID"]) || $_data["StudyDirectionID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"study[StudyDirectionID]");
        }
        if(!isset($_data["StudyCountryID"]) || $_data["StudyCountryID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"study[StudyCountryID]");
        }
        if(!isset($_data["StudySchoolTitle"]) || $_data["StudySchoolTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudySchoolTitle]");
        }
        if(!isset($_data["StudyDateStart"]) || $_data["StudyDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyDateStart]");
        }
        if(!isset($_data["StudyDateEnd"]) || $_data["StudyDateEnd"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyDateEnd]");
        }
        if(!isset($_data["StudyTitle"]) || $_data["StudyTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyTitle]");
        }
        if(!isset($_data["StudyLicence"]) || $_data["StudyLicence"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyLicence]");
        }
        if(!isset($_data["StudyLicenceDate"]) || $_data["StudyLicenceDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyLicenceDate]");
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
           
            if(isset($_data['StudyDirectionID'])){
                $qry_update[]=" StudyDirectionID=".$_data['StudyDirectionID'];
            }
            if(isset($_data['StudyDirSubID'])){
                $qry_update[]=" StudyDirSubID=".\System\Util::getInput($_data['StudyDirSubID']);
            }
            if(isset($_data['StudyDirSub1ID'])){
                $qry_update[]=" StudyDirSub1ID=".\System\Util::getInput($_data['StudyDirSub1ID']);
            }
            if(isset($_data['StudyCountryID'])){
                $qry_update[]=" StudyCountryID=".$_data['StudyCountryID'];
            }
            if(isset($_data['StudySchoolTitle'])){
                $qry_update[]=" StudySchoolTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['StudySchoolTitle']));
            }
            if(isset($_data['StudyDateStart'])){
                $qry_update[]=" StudyDateStart=".\System\Util::getInput($_data['StudyDateStart']);
            }
            if(isset($_data['StudyDateEnd'])){
                $qry_update[]=" StudyDateEnd=".\System\Util::getInput($_data['StudyDateEnd']);
            }
            if(isset($_data['StudyDateStart']) || isset($_data['StudyDateEnd'])){
                $qry_update[]=" StudyDay=DATEDIFF('".$_data['StudyDateEnd']."', '".$_data['StudyDateStart']."')";
            }
            if(isset($_data['StudyTitle'])){
                $qry_update[]=" StudyTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['StudyTitle']));
            }
            if(isset($_data['StudyDescr'])){
                $qry_update[]=" StudyDescr=".\System\Util::getInput(\System\Util::uniConvert($_data['StudyDescr']));
            }
            if(isset($_data['StudyLicence'])){
                $qry_update[]=" StudyLicence=".\System\Util::getInput(\System\Util::uniConvert($_data['StudyLicence']));
            }
            if(isset($_data['StudyLicenceDate'])){
                $qry_update[]=" StudyLicenceDate=".\System\Util::getInput($_data['StudyLicenceDate']);
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" StudyUpdateDate=NOW()";
            $qry_update[]=" StudyUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" StudyUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_STUDY." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where StudyID= '".$this->StudyID."'";
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
        if(isset($_data["StudyDirectionID"]) && $_data["StudyDirectionID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"study[StudyDirectionID]");
        }
        if(isset($_data["StudyCountryID"]) && $_data["StudyCountryID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"study[StudyCountryID]");
        }
        if(isset($_data["StudySchoolTitle"]) && $_data["StudySchoolTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudySchoolTitle]");
        }
        if(isset($_data["StudyDateStart"]) && $_data["StudyDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyDateStart]");
        }
        if(isset($_data["StudyDateEnd"]) && $_data["StudyDateEnd"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyDateEnd]");
        }
        if(isset($_data["StudyLicence"]) && $_data["StudyLicence"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyLicence]");
        }
        if(isset($_data["StudyLicenceDate"]) && $_data["StudyLicenceDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"study[StudyLicenceDate]");
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
        if($this->StudyID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON_STUDY;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where StudyID= '".$this->StudyID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
}