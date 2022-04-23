<?php
namespace Humanres;
class PersonClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_PERSON="person";
    const TBL_PERSON_PREF="person_";

    const FILE_PATH="/files/person/portrait/";
    private $img_size = array();
    
    static $gender=array(
        0=>["id"=>0,"title"=>"Эмэгтэй"],
        1=>["id"=>1,"title"=>"Эрэгтэй"],
    );
    
    static $_age=array(
        0=>["id"=>0,"title"=>"Эмэгтэй"],
        1=>["id"=>1,"title"=>"Эрэгтэй"],
    );
    
    const USER_CREATED=1;
    const USER_CREATED_NOT=0;
    
    static $is_true=array(
        0=>["id"=>0,"title"=>"Үгүй"],
        1=>["id"=>1,"title"=>"Тийм"],  
    );
    
    public function __construct() {
        $this->con=\Database::instance();
        
        parent::__construct();
        $this->img_size['medium'] = array(
            'width' => '400',
            'height' => '',
            'fldr' => 'medium/'
        );
        $this->img_size['small'] = array(
            'width' => '200',
            'height' => '200',
            'fldr' => 'small/'
        );
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
        return isset($this->_data["PersonID"]) && $this->_data["PersonID"]!=""?true:false;
    }
    public function getImage($option=array('file'=>"","folder"=>"",'size' => 'small')){
        $file=empty($option['file'])?"PersonImageSource":$option['file'];

        $filesource=isset($this->_data[$file])?$this->_data[$file]:"";
        $folder=isset($option['folder'])?$option['folder']."/":"";
        $size=isset($option['size'])?$option['size']:"small";
        switch($size){
            case 'small' :
                $fldr = 'small/';
                break;
            case 'medium' :
                $fldr = 'medium/';
                break;
            default: 
                $fldr = '';
        }
        if(empty($filesource)) return SYS_HOST."/".RF. self::FILE_PATH."/".$folder.$fldr."default.png";
        return SYS_HOST."/".RF. self::FILE_PATH."/".$folder.$fldr.$filesource;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[self::TBL_PERSON_PREF.'get_table']) && $search[self::TBL_PERSON_PREF.'get_table']!=""){
            switch ($search[self::TBL_PERSON_PREF.'get_table']){
                case 1:
                    $join_inner[\Humanres\EmployeeClass::TBL_EMPLOYEE]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\EmployeeClass::TBL_EMPLOYEE." T2 on ".$_mainTable.".PersonEmployeeID=T2.EmployeeID";
                    $qryselect[\Humanres\EmployeeClass::TBL_EMPLOYEE]= isset($qryselect[\Humanres\EmployeeClass::TBL_EMPLOYEE])?$qryselect[\Humanres\EmployeeClass::TBL_EMPLOYEE]:" T2.*";
                    $qry_cond= \Humanres\EmployeeClass::getQueryCondition(array_merge($search,array("maintable"=>"T2")) );
                    $rel_join_inner_cond= isset($qry_cond['join_inner_cond'])?$qry_cond['join_inner_cond']:"";
                    $rel_where_cond= isset($qry_cond['where_cond'])?$qry_cond['where_cond']:"";
                    if($rel_join_inner_cond!="" || $rel_where_cond!=""){
                        if($rel_join_inner_cond!="")
                            $join_inner[\Humanres\EmployeeClass::TBL_EMPLOYEE].=" and ".$rel_join_inner_cond;
                            if($rel_where_cond!="")
                                $join_inner[\Humanres\EmployeeClass::TBL_EMPLOYEE].=" ".$rel_where_cond;
                    }
                    $join_inner[\Humanres\DepartmentClass::TBL_DEPARTMENT]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\DepartmentClass::TBL_DEPARTMENT." T3 on T2.EmployeeDepartmentID=T3.DepartmentID";
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
                    $join_inner[\Humanres\PositionClass::TBL_POSITION]=" inner join ".DB_DATABASE_HUMANRES.".". \Humanres\PositionClass::TBL_POSITION." T1 on T2.EmployeePositionID=T1.PositionID";
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
                    
                    break;
            }
        }
        
        if(isset($search[self::TBL_PERSON_PREF.'useriscreated'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'useriscreated'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonUserIsCreated IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonUserIsCreated ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'iseditable'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'iseditable'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonIsEditable IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonIsEditable ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'issoldering'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'issoldering'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonIsSoldiering IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonIsSoldiering ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'ethnicid'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'ethnicid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonEthnicID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonEthnicID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_PREF.'educationlevelid'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'educationlevelid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonEducationLevelID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonEducationLevelID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'birthcityid'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'birthcityid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonBirthCityID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonBirthCityID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'addresscityid'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'addresscityid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonAddressCityID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonAddressCityID ='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_PERSON_PREF.'gender'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'gender'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonGender IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonGender ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'soldierid'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'soldierid'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonSoldierID IN ('".implode("','", $tmp_input)."')";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonSoldierID ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'soldieryear'])){
            $tmp_input=$search[self::TBL_PERSON_PREF.'soldieryear'];
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonSoldierYear IN ('".implode("','", $tmp_input)."')";
            }elseif($tmp_input!==""){
                $tmp_cond=$_mainTable.".PersonSoldierYear ='".$tmp_input."'";
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
        if(isset($search[self::TBL_PERSON_PREF.'firstname']) && $search[self::TBL_PERSON_PREF.'firstname']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'firstname'];
            $tmp_cond=$_mainTable.".PersonFirstName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_PREF.'firstname_cond']) && $search[self::TBL_PERSON_PREF.'firstname_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'firstname_cond']){
                    case "eq": 
                        $tmp_cond=$_mainTable.".PersonFirstName = '" .$tmp_input. "'";
                        break;
                    default : 
                        $tmp_cond=$_mainTable.".PersonFirstName like '%" .$tmp_input. "%'";
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
        
        if(isset($search[self::TBL_PERSON_PREF.'soldierpassno']) && $search[self::TBL_PERSON_PREF.'soldierpassno']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'soldierpassno'];
            $tmp_cond=$_mainTable.".PersonSoldierPassNo = '" .$tmp_input. "'";

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
        if(isset($search[self::TBL_PERSON_PREF.'birthdatestart']) && $search[self::TBL_PERSON_PREF.'birthdatestart']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'birthdatestart'];
            $tmp_cond=$_mainTable.".PersonBirthDate >='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_PREF.'birthdateend']) && $search[self::TBL_PERSON_PREF.'birthdateend']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'birthdateend'];
            $tmp_cond=$_mainTable.".PersonBirthDate <='".$tmp_input."'";
            
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
        if(isset($search[self::TBL_PERSON_PREF.'registernumber']) && $search[self::TBL_PERSON_PREF.'registernumber']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'registernumber'];
            $tmp_cond=$_mainTable.".PersonRegisterNumber = '" .$tmp_input. "'";
            if(isset($search[self::TBL_PERSON_PREF.'registernumber_cond']) && $search[self::TBL_PERSON_PREF.'registernumber_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'registernumber_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".PersonRegisterNumber = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".PersonRegisterNumber like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_PREF.'username']) && $search[self::TBL_PERSON_PREF.'username']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'username'];
            $tmp_cond=$_mainTable.".PersonUserName like '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_PREF.'username_cond']) && $search[self::TBL_PERSON_PREF.'username_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'username_cond']){
                    case "eq": 
                        $tmp_cond=$_mainTable.".PersonUserName = '" .$tmp_input. "'";
                        break;
                    default : 
                        $tmp_cond=$_mainTable.".PersonUserName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_PREF.'email']) && $search[self::TBL_PERSON_PREF.'email']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'email'];
            $tmp_cond=$_mainTable.".PersonUserEmail LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_PREF.'email_cond']) && $search[self::TBL_PERSON_PREF.'email_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'email_cond']){
                    case "eq": 
                        $tmp_cond=$_mainTable.".PersonUserEmail = '" .$tmp_input. "'";
                        break;
                    default : 
                        $tmp_cond=$_mainTable.".PersonUserEmail like '%" .$tmp_input. "%'";
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
        
        if(isset($search[self::TBL_PERSON_PREF.'middlename']) && $search[self::TBL_PERSON_PREF.'middlename']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'middlename'];
            $tmp_cond=$_mainTable.".PersonMiddleName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_PREF.'middlename_cond']) && $search[self::TBL_PERSON_PREF.'middlename_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'middlename_cond']){
                    case "eq":
                        $tmp_cond=$_mainTable.".PersonMiddleName = '" .$tmp_input. "'";
                        break;
                    default :
                        $tmp_cond=$_mainTable.".PersonMiddleName like '%" .$tmp_input. "%'";
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
        if(isset($search[self::TBL_PERSON_PREF.'lastname']) && $search[self::TBL_PERSON_PREF.'lastname']!=""){
            $tmp_input=$search[self::TBL_PERSON_PREF.'lastname'];
            $tmp_cond=$_mainTable.".PersonLastName LIKE '%" .$tmp_input. "%'";
            if(isset($search[self::TBL_PERSON_PREF.'lastname_cond']) && $search[self::TBL_PERSON_PREF.'lastname_cond']!=""){
                switch($search[self::TBL_PERSON_PREF.'lastname_cond']){
                    case "eq": 
                        $tmp_cond=$_mainTable.".PersonLastName = '" .$tmp_input. "'";
                        break;
                    default : 
                        $tmp_cond=$_mainTable.".PersonLastName like '%" .$tmp_input. "%'";
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
        
        if(isset($search[self::TBL_PERSON_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_PERSON_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".PersonID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".PersonID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON." T";
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
        $qry = "select COUNT(T.PersonID) as AllCount"; 
        $qry.=$this->getQueryBody($qry_cond,2);
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON." (
                    PersonEducationLevelID,
                    PersonEthnicID,
                    PersonRegisterNumber,
                    PersonLastLetter,
                    PersonLastName,
                    PersonFirstLetter,
                    PersonFirstName,
                    PersonMiddleName,
                    PersonBirthDate,
                    PersonGender,
                    PersonPartyEnterDate,
                    PersonPartyConfirmNumber,
                    PersonBirthCityID,
                    PersonBirthDistrictID,
                    PersonBirthPlace,
                    PersonBasicCityID,
                    PersonBasicDistrictID,
                    PersonBasicPlace,
                    PersonContactMobilePhone,
                    PersonContactWorkPhone,
                    PersonContactEmail,
                    PersonContactEmergencyName,
                    PersonContactEmergencyPhone,
                    PersonAddressCityID,
                    PersonAddressDistrictID,
                    PersonAddressHorooID,
                    PersonAddress,
                    PersonAddressFull,
                    PersonCreatePersonID,
                    PersonCreateEmployeeID,
                    PersonCreateDate
                ) values(
                    ".(isset($_data['PersonEducationLevelID'])?$_data['PersonEducationLevelID']:"NULL").",
                    ".(isset($_data['PersonEthnicID'])?$_data['PersonEthnicID']:"NULL").",
                    UPPER(".\System\Util::getInput($_data['PersonRegisterNumber'])."),
                    UPPER(SUBSTR(".\System\Util::getInput($_data['PersonLastName']).",1,1)),
                    ".\System\Util::getInput($_data['PersonLastName']).",
                    UPPER(SUBSTR(".\System\Util::getInput($_data['PersonFirstName']).",1,1)),
                    ".\System\Util::getInput($_data['PersonFirstName']).",
                    ".\System\Util::getInput($_data['PersonMiddleName']).",
                    ".\System\Util::getInput($_data['PersonBirthDate']).",
                    ".$_data['PersonGender'].",
                    ".\System\Util::getInput(isset($_data['PersonPartyEnterDate'])?$_data['PersonPartyEnterDate']:"").",
                    ".\System\Util::getInput(isset($_data['PersonPartyConfirmNumber'])?\System\Util::uniConvert($_data['PersonPartyConfirmNumber']):"").",
                    ".(isset($_data['PersonBirthCityID'])?$_data['PersonBirthCityID']:"NULL").",
                    ".(isset($_data['PersonBirthDistrictID'])?$_data['PersonBirthDistrictID']:"NULL").",
                    ".\System\Util::getInput(isset($_data['PersonBirthPlace'])?\System\Util::uniConvert($_data['PersonBirthPlace']):"").",
                    ".(isset($_data['PersonBasicCityID'])?$_data['PersonBasicCityID']:"NULL").",
                    ".(isset($_data['PersonBasicDistrictID'])?$_data['PersonBasicDistrictID']:"NULL").",
                    ".\System\Util::getInput(isset($_data['PersonBasicPlace'])?\System\Util::uniConvert($_data['PersonBasicPlace']):"").",
                    ".\System\Util::getInput(isset($_data['PersonContactMobilePhone'])?\System\Util::uniConvert($_data['PersonContactMobilePhone']):"").",
                    ".\System\Util::getInput(isset($_data['PersonContactWorkPhone'])?\System\Util::uniConvert($_data['PersonContactWorkPhone']):"").",
                    ".\System\Util::getInput(isset($_data['PersonContactEmail'])?$_data['PersonContactEmail']:"").",
                    ".\System\Util::getInput(isset($_data['PersonContactEmergencyName'])?\System\Util::uniConvert($_data['PersonContactEmergencyName']):"").",
                    ".\System\Util::getInput(isset($_data['PersonContactEmergencyPhone'])?$_data['PersonContactEmergencyPhone']:"").",
                    ".(isset($_data['PersonAddressCityID'])?$_data['PersonAddressCityID']:"NULL").",
                    ".(isset($_data['PersonAddressDistrictID'])?$_data['PersonAddressDistrictID']:"NULL").",
                    ".(isset($_data['PersonAddressHorooID'])?$_data['PersonAddressHorooID']:"NULL").",
                    ".\System\Util::getInput(isset($_data['PersonAddress'])?\System\Util::uniConvert($_data['PersonAddress']):"").",
                    ".\System\Util::getInput(isset($_data['PersonAddress'])?\System\Util::uniConvert($_data['PersonAddressFull']):"").",
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
//         if(!isset($_data["PersonEmployeeID"]) || $_data["PersonEmployeeID"]===""){
//             $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Бүртгэл хоосон байна');
//         }
        if(!isset($_data["PersonRegisterNumber"]) || $_data["PersonRegisterNumber"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonRegisterNumber]");
        }
        if(!isset($_data["PersonLastName"]) || $_data["PersonLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonLastName]");
        }
        if(!isset($_data["PersonFirstName"]) || $_data["PersonFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonFirstName]");
        }
        if(!isset($_data["PersonMiddleName"]) || $_data["PersonMiddleName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonMiddleName]");
        }
        if(!isset($_data["PersonBirthDate"]) || $_data["PersonBirthDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonBirthDate]");
        }
        if(!isset($_data["PersonGender"]) || $_data["PersonGender"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonGender]");
        }
       
        if($type==1){
            if(!isset($_data["CreatePersonID"]) || $_data["CreatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
            if(!isset($_data["CreatePersonID"]) || $_data["CreatePersonID"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Системийн алдаа. Бүртгэж буй хэрэглэгч олдсонгүй.');
            }
        }
        return !$this->hasError();
    }
    function updateRow($_data){
        $this->clearError();
        if($this->validateUpdateRow($_data)){
            $qry_update=array();
            if(isset($_data['PersonEmployeeID'])){
                $qry_update[]=" PersonEmployeeID=".$_data['PersonEmployeeID'];
            }
            if(isset($_data['PersonEducationLevelID'])){
                $qry_update[]=" PersonEducationLevelID=".$_data['PersonEducationLevelID'];
            }
            if(isset($_data['PersonEthnicID'])){
                $qry_update[]=" PersonEthnicID=".$_data['PersonEthnicID'];
            }
            if(isset($_data['PersonRegisterNumber'])){
                $qry_update[]=" PersonRegisterNumber=UPPER(".\System\Util::getInput($_data['PersonRegisterNumber']).")";
            }
            if(isset($_data['PersonLastName'])){
                $qry_update[]=" PersonLastName=".\System\Util::getInput($_data['PersonLastName']);
                $qry_update[]=" PersonLastLetter=UPPER(SUBSTR(".\System\Util::getInput($_data['PersonLastName']).",1,1))";
            }
            if(isset($_data['PersonFirstName'])){
                $qry_update[]=" PersonFirstName=".\System\Util::getInput($_data['PersonFirstName']);
                $qry_update[]=" PersonFirstLetter=UPPER(SUBSTR(".\System\Util::getInput($_data['PersonFirstName']).",1,1))";
            }
            if(isset($_data['PersonMiddleName'])){
                $qry_update[]=" PersonMiddleName=".\System\Util::getInput($_data['PersonMiddleName']);
            }
            if(isset($_data['PersonBirthDate'])){
                $qry_update[]=" PersonBirthDate=".\System\Util::getInput($_data['PersonBirthDate']);
            }
            if(isset($_data['PersonGender'])){
                $qry_update[]=" PersonGender=".$_data['PersonGender'];
            }
            if(isset($_data['PersonBirthCityID'])){
                $qry_update[]=" PersonBirthCityID=".$_data['PersonBirthCityID'];
            }
            if(isset($_data['PersonBirthDistrictID'])){
                $qry_update[]=" PersonBirthDistrictID=".$_data['PersonBirthDistrictID'];
            }
            if(isset($_data['PersonBirthPlace'])){
                $qry_update[]=" PersonBirthPlace=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonBirthPlace']));
            }
            if(isset($_data['PersonBasicCityID'])){
                $qry_update[]=" PersonBasicCityID=".$_data['PersonBasicCityID'];
            }
            if(isset($_data['PersonBasicDistrictID'])){
                $qry_update[]=" PersonBasicDistrictID=".$_data['PersonBasicDistrictID'];
            }
            if(isset($_data['PersonBasicPlace'])){
                $qry_update[]=" PersonBasicPlace=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonBasicPlace']));
            }
            if(isset($_data['PersonContactMobilePhone'])){
                $qry_update[]=" PersonContactMobilePhone=".\System\Util::getInput($_data['PersonContactMobilePhone']);
            }
            if(isset($_data['PersonContactWorkPhone'])){
                $qry_update[]=" PersonContactWorkPhone=".\System\Util::getInput($_data['PersonContactWorkPhone']);
            }
            if(isset($_data['PersonContactEmail'])){
                $qry_update[]=" PersonContactEmail=".\System\Util::getInput($_data['PersonContactEmail']);
            }
            if(isset($_data['PersonContactWebsite'])){
                $qry_update[]=" PersonContactWebsite=".\System\Util::getInput($_data['PersonContactWebsite']);
            }
            if(isset($_data['PersonContactEmergencyName'])){
                $qry_update[]=" PersonContactEmergencyName=".\System\Util::getInput($_data['PersonContactEmergencyName']);
            }
            if(isset($_data['PersonContactEmergencyPhone'])){
                $qry_update[]=" PersonContactEmergencyPhone=".\System\Util::getInput($_data['PersonContactEmergencyPhone']);
            }
            if(isset($_data['PersonAddressCityID'])){
                $qry_update[]=" PersonAddressCityID=".$_data['PersonAddressCityID'];
            }
            if(isset($_data['PersonAddressDistrictID'])){
                $qry_update[]=" PersonAddressDistrictID=".$_data['PersonAddressDistrictID'];
            }
            if(isset($_data['PersonAddressHorooID'])){
                $qry_update[]=" PersonAddressHorooID=".$_data['PersonAddressHorooID'];
            }
            if(isset($_data['PersonAddress'])){
                $qry_update[]=" PersonAddress=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonAddress']));
            }
            if(isset($_data['PersonAddressFull'])){
                $qry_update[]=" PersonAddressFull=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonAddressFull']));
            }
            $_is_soldier=0;
            if(isset($_data['PersonIsSoldiering'])){
                $_is_soldier=1;
                $qry_update[]=" PersonIsSoldiering=".$_data['PersonIsSoldiering'];
            }
            if(isset($_data['PersonSoldierPassNo'])){
                $_is_soldier=1;
                $qry_update[]=" PersonSoldierPassNo=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonSoldierPassNo']));
            }
            if(isset($_data['PersonSoldierYear'])){
                $_is_soldier=1;
                $qry_update[]=" PersonSoldierYear=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonSoldierYear']));
            }
            if(isset($_data['PersonSoldierID'])){
                $_is_soldier=1;
                $qry_update[]=" PersonSoldierID=".$_data['PersonSoldierID'];
            }
            if(isset($_data['PersonSoldierDescr'])){
                $_is_soldier=1;
                $qry_update[]=" PersonSoldierDescr=".\System\Util::getInput(\System\Util::uniConvert($_data['PersonSoldierDescr']));
            }
            if($_is_soldier){
                $qry_update[]=" PersonSoldierUpdateDate=NOW()";
            }
            if(isset($_data['PersonUserIsCreated'])){
                $qry_update[]=" PersonUserIsCreated=".$_data['PersonUserIsCreated'];
                
                $qry_update[]=" PersonUserUpdateDate=NOW()";
                $qry_update[]=" PersonUserUpdatePersonID=".$_data['UpdatePersonID'];
                $qry_update[]=" PersonUserUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            }
            if(isset($_data['PersonUserName'])){
                $qry_update[]=" PersonUserName=".\System\Util::getInput($_data['PersonUserName']);
            }
            if(isset($_data['PersonUserEmail'])){
                $qry_update[]=" PersonUserEmail=".\System\Util::getInput($_data['PersonUserEmail']);
            }
            if(isset($_data['PersonUserPassword'])){
                if($_data['PersonUserPassword']!=""){
                    $qry_update[]=" PersonUserPassword=MD5('".$_data['PersonUserPassword']."')";
                }else{
                    $qry_update[]=" PersonUserPassword=NULL";
                }
            }
            if(isset($_data['PersonCountEmployee'])){
                $qry_update[]=" PersonCountEmployee=".$_data['PersonCountEmployee'];
            }
            if(isset($_data['PersonCountEducation'])){
                $qry_update[]=" PersonCountEducation=".$_data['PersonCountEducation'];
            }
            if(isset($_data['PersonCountStudy'])){
                $qry_update[]=" PersonCountStudy=".$_data['PersonCountStudy'];
            }
            if(isset($_data['PersonCountLanguage'])){
                $qry_update[]=" PersonCountLanguage=".$_data['PersonCountLanguage'];
            }
            if(isset($_data['PersonCountDegree'])){
                $qry_update[]=" PersonCountDegree=".$_data['PersonCountDegree'];
            }
            if(isset($_data['PersonCountAward'])){
                $qry_update[]=" PersonCountAward=".$_data['PersonCountAward'];
            }
            if(isset($_data['PersonCountJob'])){
                $qry_update[]=" PersonCountJob=".$_data['PersonCountJob'];
            }
            if(isset($_data['PersonCountPunishment'])){
                $qry_update[]=" PersonCountPunishment=".$_data['PersonCountPunishment'];
            }
            if(isset($_data['PersonCountTrip'])){
                $qry_update[]=" PersonCountTrip=".$_data['PersonCountTrip'];
            }
            if(isset($_data['PersonCountSalary'])){
                $qry_update[]=" PersonCountSalary=".$_data['PersonCountSalary'];
            }
            if(isset($_data['PersonCountFamily'])){
                $qry_update[]=" PersonCountFamily=".$_data['PersonCountFamily'];
            }
            if(isset($_data['PersonCountRelate'])){
                $qry_update[]=" PersonCountRelate=".$_data['PersonCountRelate'];
            }
            if(isset($_data['PersonCountPosRank'])){
                $qry_update[]=" PersonCountPosRank=".$_data['PersonCountPosRank'];
            }
            if(isset($_data['PersonCountHoliday'])){
                $qry_update[]=" PersonCountHoliday=".$_data['PersonCountHoliday'];
            }
            if(isset($_data['PersonCountBill'])){
                $qry_update[]=" PersonCountBill=".$_data['PersonCountBill'];
            }
            
            if(isset($_data['PersonWorkYearAll'])){
                $qry_update[]=" PersonWorkYearAll=".$_data['PersonWorkYearAll'];
            }
            if(isset($_data['PersonWorkYearGov'])){
                $qry_update[]=" PersonWorkYearGov=".$_data['PersonWorkYearGov'];
            }
            if(isset($_data['PersonWorkYearMilitary'])){
                $qry_update[]=" PersonWorkYearMilitary=".$_data['PersonWorkYearMilitary'];
            }
            if(isset($_data['PersonWorkYearCompany'])){
                $qry_update[]=" PersonWorkYearCompany=".$_data['PersonWorkYearCompany'];
            }
            if(isset($_data['PersonWorkMonthAll'])){
                $qry_update[]=" PersonWorkMonthAll=".$_data['PersonWorkMonthAll'];
            }
            if(isset($_data['PersonWorkMonthGov'])){
                $qry_update[]=" PersonWorkMonthGov=".$_data['PersonWorkMonthGov'];
            }
            if(isset($_data['PersonWorkMonthMilitary'])){
                $qry_update[]=" PersonWorkMonthMilitary=".$_data['PersonWorkMonthMilitary'];
            }
            if(isset($_data['PersonWorkMonthCompany'])){
                $qry_update[]=" PersonWorkMonthCompany=".$_data['PersonWorkMonthCompany'];
            }
            if(isset($_data['PersonWorkDayAll'])){
                $qry_update[]=" PersonWorkDayAll=".$_data['PersonWorkDayAll'];
            }
            if(isset($_data['PersonWorkDayGov'])){
                $qry_update[]=" PersonWorkDayGov=".$_data['PersonWorkDayGov'];
            }
            if(isset($_data['PersonWorkDayMilitary'])){
                $qry_update[]=" PersonWorkDayMilitary=".$_data['PersonWorkDayMilitary'];
            }
            if(isset($_data['PersonWorkDayCompany'])){
                $qry_update[]=" PersonWorkDayCompany=".$_data['PersonWorkDayCompany'];
            }
            
            if(isset($_data['PersonWorkYearOrgan'])){
                $qry_update[]=" PersonWorkYearOrgan=".$_data['PersonWorkYearOrgan'];
            }
            if(isset($_data['PersonWorkMonthOrgan'])){
                $qry_update[]=" PersonWorkMonthOrgan=".$_data['PersonWorkMonthOrgan'];
            }
            if(isset($_data['PersonWorkDayOrgan'])){
                $qry_update[]=" PersonWorkDayOrgan=".$_data['PersonWorkDayOrgan'];
            }
            if(isset($_data['PersonAbsentYear'])){
                $qry_update[]=" PersonAbsentYear=".$_data['PersonAbsentYear'];
            }
            if(isset($_data['PersonAbsentMonth'])){
                $qry_update[]=" PersonAbsentMonth=".$_data['PersonAbsentMonth'];
            }
            if(isset($_data['PersonAbsentDay'])){
                $qry_update[]=" PersonAbsentDay=".$_data['PersonAbsentDay'];
            }
            if(isset($_data['PersonIsEditable'])){
                $qry_update[]=" PersonIsEditable=".$_data['PersonIsEditable'];
            }
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" PersonUpdateDate=NOW()";
            if(isset($_data['UpdateEmployeeID'])){
                $qry_update[]=" PersonUpdatePersonID=".$_data['UpdatePersonID'];
                $qry_update[]=" PersonUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            }
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where PersonID= '".$this->PersonID."'";
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
        if(isset($_data["PersonRegisterNumber"]) && $_data["PersonRegisterNumber"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonRegisterNumber]");
        }
        if(isset($_data["PersonLastName"]) && $_data["PersonLastName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonLastName]");
        }
        if(isset($_data["PersonFirstName"]) && $_data["PersonFirstName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonFirstName]");
        }
        if(isset($_data["PersonMiddleName"]) && $_data["PersonMiddleName"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonMiddleName]");
        }
        if(isset($_data["PersonBirthDate"]) && $_data["PersonBirthDate"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonBirthDate]");
        }
        if(isset($_data["PersonGender"]) && $_data["PersonGender"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonGender]");
        }
        if(isset($_data["PersonBirthCityID"]) && $_data["PersonBirthCityID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonBirthCityID]");
        }
        if(isset($_data["PersonBirthDistrictID"]) && $_data["PersonBirthDistrictID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonBirthDistrictID]");
        }
        if(isset($_data["PersonBirthPlace"]) && $_data["PersonBirthPlace"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonBirthPlace]");
        }
        if(isset($_data["PersonBasicCityID"]) && $_data["PersonBasicCityID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonBasicCityID]");
        }
        if(isset($_data["PersonBasicDistrictID"]) && $_data["PersonBasicDistrictID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"person[PersonBasicDistrictID]");
        }
        if(isset($_data["PersonBasicPlace"]) && $_data["PersonBasicPlace"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonBasicPlace]");
        }
        if(isset($_data["PersonAddressCityID"]) && $_data["PersonAddressCityID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonAddressCityID]");
        }
        if(isset($_data["PersonAddressDistrictID"]) && $_data["PersonAddressDistrictID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonAddressDistrictID]");
        }
        if(isset($_data["PersonAddressHorooID"]) && $_data["PersonAddressHorooID"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonAddressHorooID]");
        }
        if(isset($_data["PersonAddress"]) && $_data["PersonAddress"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonAddress]");
        }
        if(isset($_data["PersonAddressFull"]) && $_data["PersonAddressFull"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonAddressFull]");
        }
        if(isset($_data["PersonUserIsCreated"]) && $_data["PersonUserIsCreated"]){
            if(isset($_data["PersonUserName"]) && $_data["PersonUserName"]===""){
                $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"person[PersonUserName]");
            }
        }
        if(isset($_data["PersonUserPassword"]) ){
            if(isset($_data["PersonUserIsCreated"]) && $_data["PersonUserIsCreated"]){
                if($_data["PersonUserPassword"]===""){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нууц үг хоосон байна',"person[PersonUserPassword]");
                }elseif(!isset($_data["PersonUserPasswordConfirm"]) && $_data["PersonUserPasswordConfirm"]===""){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нууц үг хоосон байна',"person[PersonUserPasswordConfirm]");
                }elseif($_data["PersonUserPassword"]!=$_data["PersonUserPasswordConfirm"]){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Нууц үгийн баталгаа өөр байна',"person[PersonUserPasswordConfirm]");
                }
            }
        }
        return !$this->hasError();
    }
    function deleteRow($_data=array()){
        $this->clearError();
        if($this->PersonID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $this->removeFile(false);
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where PersonID= '".$this->PersonID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
    
    function uploadFile($file,$_data=array("_field"=>"PersonImageSource"),$_path=""){
        $this->clearError();
        $_id=isset($_data['id'])?$_data['id']:$this->PersonID;
        
        $_path=DRF.self::FILE_PATH."/";
        $_field= isset($_data['_field'])?$_data['_field']:"PersonImageSource";
        $fileext = \System\Util::getFileExt($file["name"]);
        
        $convert_name = md5($file['name']).time().'_'.$_id.".".$fileext;
        $uploadfile = $_path.$convert_name;
        
        if(move_uploaded_file($file["tmp_name"], $uploadfile)){
            $this->_data[$_field] = $convert_name;
            
            if(file_exists($uploadfile)){
                foreach($this->img_size as $isize){
                    \System\Util::cropImage($isize['width'], $isize['height'], $uploadfile, $_path.$isize['fldr'].$convert_name);
                }
            }
            
            $qry = "update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON." set `".$_field."` = '".$convert_name."' where `PersonID` = '".$_id."'";
            $this->con->qryexec($qry);
            return true;
        }else {
            $this->addError(\System\Error::ERROR_DB, 'Файл хуулахад алдаа гарлаа.');
            return false;
        }
        
    }
    function removeFile($update = true,$_data=array("_field"=>"PersonImageSource"),$_path=""){
        $_path=DRF.self::FILE_PATH."/";
        
        $_field= isset($_data['_field'])?$_data['_field']:"PersonImageSource";
        $filepath = $_path.$this->{$_field};
        
        if($this->{$_field}!="" && file_exists($filepath)){
            unlink($filepath);
            foreach($this->img_size as $isize){
                $_tmpfile=$_path.$isize['fldr'].$this->{$_field};
                if(file_exists($_tmpfile)){
                    unlink($_tmpfile);
                }
            }
        }
        $this->_data[$_field] = "";
        if($update){
            $qry = "update ".DB_DATABASE_HUMANRES.".".self::TBL_PERSON." set `".$_field."` = NULL where `PersonID` = '{$this->PersonID}'";
            $res = $this->con->qryexec($qry);
            if($res == \System\Error::ERROR_DB){
                $this->addError(\System\Error::ERROR_DB, 'Файл устгахад алдаа гарлаа.');
                return false;
            }
        }
        return true;
    }
    function updateYears(){
        $rowObj=\Humanres\PersonJobClass::getInstance()->getRow([
            "_select"=>["JobPersonID, 
                SUM(JobWorkedYear) as PersonWorkYearAll, 
                SUM(JobWorkedMonth) as PersonWorkMonthAll, 
                SUM(JobWorkedDay) as PersonWorkDayAll, 
                SUM(IF(JobOrganID=1,JobWorkedYear,0)) as PersonWorkYearGov, 
                SUM(IF(JobOrganID=1,JobWorkedMonth,0)) as PersonWorkMonthGov, 
                SUM(IF(JobOrganID=1,JobWorkedDay,0)) as PersonWorkDayGov, 
                SUM(IF(JobOrganTypeID=1 AND JobOrganSubID>0,JobWorkedYear,0)) as PersonWorkYearMilitary, 
                SUM(IF(JobOrganTypeID=1 AND JobOrganSubID>0,JobWorkedMonth,0)) as PersonWorkMonthMilitary, 
                SUM(IF(JobOrganTypeID=1 AND JobOrganSubID>0,JobWorkedDay,0)) as PersonWorkDayMilitary, 
                SUM(IF(JobOrganSubID=1,JobWorkedYear,0)) as PersonWorkYearOrgan, 
                SUM(IF(JobOrganSubID=1,JobWorkedMonth,0)) as PersonWorkMonthOrgan, 
                SUM(IF(JobOrganSubID=1,JobWorkedDay,0)) as PersonWorkDayOrgan, 
                SUM(IF(JobOrganID>1 AND JobOrganID!=6,JobWorkedYear,0)) as PersonWorkYearCompany, 
                SUM(IF(JobOrganID>1 AND JobOrganID!=6,JobWorkedMonth,0)) as PersonWorkMonthCompany, 
                SUM(IF(JobOrganID>1 AND JobOrganID!=6,JobWorkedDay,0)) as PersonWorkDayCompany"],
            "job_personid"=>$this->PersonID,
            "groupby"=>['JobPersonID']
        ]);
        
        $_num_days=is_numeric($rowObj->PersonWorkDayAll)?$rowObj->PersonWorkDayAll:0;
        $_num_month=is_numeric($rowObj->PersonWorkMonthAll)?$rowObj->PersonWorkMonthAll:0;
        $_num_years=is_numeric($rowObj->PersonWorkYearAll)?$rowObj->PersonWorkYearAll:0;
        $tmpDay=$_num_days%30;
        $tmpMonth=$_num_month+floor($_num_days/30);
        $PersonWorkYearAll=$_num_years+floor($tmpMonth/12);
        $PersonWorkMonthAll=$tmpMonth%12;
        $PersonWorkDayAll=$tmpDay;
        
        $_num_days=is_numeric($rowObj->PersonWorkDayGov)?$rowObj->PersonWorkDayGov:0;
        $_num_month=is_numeric($rowObj->PersonWorkMonthGov)?$rowObj->PersonWorkMonthGov:0;
        $_num_years=is_numeric($rowObj->PersonWorkYearGov)?$rowObj->PersonWorkYearGov:0;
        $tmpDay=$_num_days%30;
        $tmpMonth=$_num_month+floor($_num_days/30);
        $PersonWorkYearGov=$_num_years+floor($tmpMonth/12);
        $PersonWorkMonthGov=$tmpMonth%12;
        $PersonWorkDayGov=$tmpDay;
        
        $_num_days=is_numeric($rowObj->PersonWorkDayMilitary)?$rowObj->PersonWorkDayMilitary:0;
        $_num_month=is_numeric($rowObj->PersonWorkMonthMilitary)?$rowObj->PersonWorkMonthMilitary:0;
        $_num_years=is_numeric($rowObj->PersonWorkYearMilitary)?$rowObj->PersonWorkYearMilitary:0;
        $tmpDay=$_num_days%30;
        $tmpMonth=$_num_month+floor($_num_days/30);
        $PersonWorkYearMilitary=$_num_years+floor($tmpMonth/12);
        $PersonWorkMonthMilitary=$tmpMonth%12;
        $PersonWorkDayMilitary=$tmpDay;
        
        $_num_days=is_numeric($rowObj->PersonWorkDayOrgan)?$rowObj->PersonWorkDayOrgan:0;
        $_num_month=is_numeric($rowObj->PersonWorkMonthOrgan)?$rowObj->PersonWorkMonthOrgan:0;
        $_num_years=is_numeric($rowObj->PersonWorkYearOrgan)?$rowObj->PersonWorkYearOrgan:0;
        $tmpDay=$_num_days%30;
        $tmpMonth=$_num_month+floor($_num_days/30);
        $PersonWorkYearOrgan=$_num_years+floor($tmpMonth/12);
        $PersonWorkMonthOrgan=$tmpMonth%12;
        $PersonWorkDayOrgan=$tmpDay;
        
        $_num_days=is_numeric($rowObj->PersonWorkDayCompany)?$rowObj->PersonWorkDayCompany:0;
        $_num_month=is_numeric($rowObj->PersonWorkMonthCompany)?$rowObj->PersonWorkMonthCompany:0;
        $_num_years=is_numeric($rowObj->PersonWorkYearCompany)?$rowObj->PersonWorkYearCompany:0;
        $tmpDay=$_num_days%30;
        $tmpMonth=$_num_month+floor($_num_days/30);
        $PersonWorkYearCompany=$_num_years+floor($tmpMonth/12);
        $PersonWorkMonthCompany=$tmpMonth%12;
        $PersonWorkDayCompany=$tmpDay;
      
        $res=$this->updateRow([
            "PersonWorkYearAll"=>$PersonWorkYearAll,
            "PersonWorkMonthAll"=>$PersonWorkMonthAll,
            "PersonWorkDayAll"=>$PersonWorkDayAll,
            "PersonWorkYearGov"=>$PersonWorkYearGov,
            "PersonWorkMonthGov"=>$PersonWorkMonthGov,
            "PersonWorkDayGov"=>$PersonWorkDayGov,
            "PersonWorkYearMilitary"=>$PersonWorkYearMilitary,
            "PersonWorkMonthMilitary"=>$PersonWorkMonthMilitary,
            "PersonWorkDayMilitary"=>$PersonWorkDayMilitary,
            "PersonWorkYearOrgan"=>$PersonWorkYearOrgan,
            "PersonWorkMonthOrgan"=>$PersonWorkMonthOrgan,
            "PersonWorkDayOrgan"=>$PersonWorkDayOrgan,
            "PersonWorkYearCompany"=>$PersonWorkYearCompany,
            "PersonWorkMonthCompany"=>$PersonWorkMonthCompany,
            "PersonWorkDayCompany"=>$PersonWorkDayCompany,
            "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
            "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
        ]);
        return $res;
    }
    function updateJobAbsent(){
        $_mainlist=\Humanres\PersonJobClass::getInstance()->getRowList(['job_personid'=>$this->PersonID,"orderby"=>"JobDateStart"]);
        $_prevObj=\Humanres\PersonJobClass::getInstance();
        
        $_num_days=0;
        $_num_month=0;
        $_num_years=0;
        
        foreach ($_mainlist as $r){
            $_tmpObj=\Humanres\PersonJobClass::getInstance($r);
            if($_prevObj->isExist()){
                if(!$_prevObj->JobIsNow){
                    $_isabsent=$_prevObj->JobDateQuit!=$_tmpObj->JobDateStart?1:0;
                    $_time=\System\Util::getDaysDiff($_prevObj->JobDateQuit, $_tmpObj->JobDateStart);
                    $res=$_tmpObj->updateRow([
                        "JobIsAbsent"=>$_isabsent,
                        "JobAbsentDateStart"=>$_isabsent?$_prevObj->JobDateQuit:"",
                        "JobAbsentYear"=>$_time['year'],
                        "JobAbsentMonth"=>$_time['month'],
                        "JobAbsentDay"=>$_time['day'],
                        "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
                        "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID]);
                    
                    $_num_days+=$_time['day'];
                    $_num_month+=$_time['month'];
                    $_num_years+=$_time['year'];
                    
                    if(!$res){
                        return false;
                    }
                }
                $_prevObj=$_tmpObj;
            }else{
                $_prevObj=\Humanres\PersonJobClass::getInstance($r);
            }
        }
        $res=$this->updateRow([
            "PersonAbsentYear"=>$_num_years,
            "PersonAbsentMonth"=>$_num_month,
            "PersonAbsentDay"=>$_num_days,
            "UpdatePersonID"=>$_SESSION[SESSSYSINFO]->PersonID,
            "UpdateEmployeeID"=>$_SESSION[SESSSYSINFO]->EmployeeID,
        ]);
        return $res;
    }
}