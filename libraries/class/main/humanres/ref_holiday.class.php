<?php
namespace Humanres;
class RefHolidayClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_REF_HOLIDAY="ref_holiday";
    const TBL_REF_HOLIDAY_PREF="refholiday_";
    
    const TYPE_SINGLE=1;
    const TYPE_ALWAYS=2;
    
    static $_type=array(
        1=>["id"=>1,"title"=>"Нэг удаагийн"],
        2=>["id"=>2,"title"=>"Давтагддаг"],
    );
    
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
        return isset($this->_data["RefHolidayID"]) && $this->_data["RefHolidayID"]!=""?true:false;
    }
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        if(isset($search[self::TBL_REF_HOLIDAY_PREF.'year'])){
            $tmp_input=self::getParam($search[self::TBL_REF_HOLIDAY_PREF.'year']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RefHolidayYear IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RefHolidayYear ='".$tmp_input."'";
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
        if(isset($search[self::TBL_REF_HOLIDAY_PREF.'type'])){
            $tmp_input=self::getParam($search[self::TBL_REF_HOLIDAY_PREF.'type']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RefHolidayType IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RefHolidayType ='".$tmp_input."'";
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
        
        
        if(isset($search[self::TBL_REF_HOLIDAY_PREF.'notid'])){
            $tmp_input=self::getParam($search[self::TBL_REF_HOLIDAY_PREF.'notid']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RefHolidayID NOT IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RefHolidayID !='".$tmp_input."'";
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
        
        if(isset($search[self::TBL_REF_HOLIDAY_PREF.'id'])){
            $tmp_input=self::getParam($search[self::TBL_REF_HOLIDAY_PREF.'id']);
            $tmp_cond="";
            if(is_array($tmp_input)){
                $tmp_cond=$_mainTable.".RefHolidayID IN (".implode(",", $tmp_input).")";
            }elseif($tmp_input!==""){
                if($tmp_input>-1)
                    $tmp_cond=$_mainTable.".RefHolidayID ='".$tmp_input."'";
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
        $qry.=" from ".DB_DATABASE_HUMANRES.".".self::TBL_REF_HOLIDAY." T";
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
        $qry = "select COUNT(T.RefHolidayID) as AllCount";
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
                insert into ".DB_DATABASE_HUMANRES.".".self::TBL_REF_HOLIDAY." (
                    RefHolidayYear,
                    RefHolidayType,
                    RefHolidayTitle,
                    RefHolidayDateStart,
                    RefHolidayDateEnd,
                    RefHolidayCreatePersonID,
                    RefHolidayCreateEmployeeID,
                    RefHolidayCreateDate
                ) values(
                    ".\System\Util::getInput(\System\Util::uniConvert($_data['RefHolidayYear'])).",
                    ".$_data['RefHolidayType'].",
                    ".\System\Util::getInput(isset($_data['RefHolidayTitle'])?\System\Util::uniConvert($_data['RefHolidayTitle']):"").",
                    ".\System\Util::getInput($_data['RefHolidayDateStart']).",
                    ".\System\Util::getInput($_data['RefHolidayDateEnd']).",
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
        if(!isset($_data["RefHolidayType"]) || $_data["RefHolidayType"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Сонгоогүй байна',"ref[RefHolidayType]");
        }
        if(!isset($_data["RefHolidayTitle"]) || $_data["RefHolidayTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Гарчиг хоосон байна',"ref[RefHolidayTitle]");
        }
        if(!isset($_data["RefHolidayDateStart"]) || $_data["RefHolidayDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"ref[RefHolidayDateStart]");
        }
        if(!isset($_data["RefHolidayDateEnd"]) || $_data["RefHolidayDateEnd"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"ref[RefHolidayDateEnd]");
        }
        if(isset($_data["RefHolidayType"]) && $_data["RefHolidayType"]==self::TYPE_SINGLE){
            if(isset($_data["RefHolidayDateStart"]) && $_data["RefHolidayDateStart"]!=="" && isset($_data["RefHolidayDateEnd"]) && $_data["RefHolidayDateEnd"]!==""){
                $_er=0;
                if(\System\Util::checkDate($_data["RefHolidayDateStart"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateStart]");
                    $_er=1;
                }
                if(\System\Util::checkDate($_data["RefHolidayDateEnd"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateEnd]");
                    $_er=1;
                }
                if(!$_er){
                    if($_data["RefHolidayDateStart"]>$_data["RefHolidayDateEnd"]){
                        $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эхлэх огноо дуусах огнооноос хойш байна');
                    }
                }
            }
        }else{
            if(isset($_data["RefHolidayDateStart"]) && $_data["RefHolidayDateStart"]!=="" && isset($_data["RefHolidayDateEnd"]) && $_data["RefHolidayDateEnd"]!==""){
                $_er=0;
                $now=new \DateTime();
                $_year=$now->format("Y");
                if(\System\Util::checkDate($_year."-".$_data["RefHolidayDateStart"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateStart]");
                    $_er=1;
                }
                if(\System\Util::checkDate($_year."-".$_data["RefHolidayDateEnd"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateEnd]");
                    $_er=1;
                }
                if(!$_er){
                    if($_year."-".$_data["RefHolidayDateStart"]>$_year."-".$_data["RefHolidayDateEnd"]){
                        $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эхлэх огноо дуусах огнооноос хойш байна');
                    }
                }
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
           
            if(isset($_data['RefHolidayYear'])){
                $qry_update[]=" RefHolidayYear=".$_data['RefHolidayYear'];
            }
            if(isset($_data['RefHolidayTitle'])){
                $qry_update[]=" RefHolidayTitle=".\System\Util::getInput(\System\Util::uniConvert($_data['RefHolidayTitle']));
            }
            if(isset($_data['RefHolidayDateStart'])){
                $qry_update[]=" RefHolidayDateStart=".\System\Util::getInput($_data['RefHolidayDateStart']);
            }
            if(isset($_data['RefHolidayDateEnd'])){
                $qry_update[]=" RefHolidayDateEnd=".\System\Util::getInput($_data['RefHolidayDateEnd']);
            }
            
            if(count($qry_update)<1){
                $this->addError(\System\Error::ERROR_DB, 'Засварлах өгөгдөл олдсонгүй.');
                return false;
            }
            $qry_update[]=" RefHolidayUpdateDate=NOW()";
            $qry_update[]=" RefHolidayUpdatePersonID=".$_data['UpdatePersonID'];
            $qry_update[]=" RefHolidayUpdateEmployeeID=".$_data['UpdateEmployeeID'];
            
            
            $qry=" update ".DB_DATABASE_HUMANRES.".".self::TBL_REF_HOLIDAY." set ";
            $qry.= implode(", ", $qry_update);
            if(!isset($_data['cond']) || count($_data['cond'])<1)
                $qry.=" where RefHolidayID= '".$this->RefHolidayID."'";
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
        if(isset($_data["RefHolidayTitle"]) && $_data["RefHolidayTitle"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Гарчиг хоосон байна',"ref[RefHolidayTitle]");
        }
        if(isset($_data["RefHolidayDateStart"]) && $_data["RefHolidayDateStart"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"ref[RefHolidayDateStart]");
        }
        if(isset($_data["RefHolidayDateEnd"]) && $_data["RefHolidayDateEnd"]===""){
            $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Хоосон байна',"ref[RefHolidayDateEnd]");
        }
        if(isset($_data["RefHolidayType"]) && $_data["RefHolidayType"]==self::TYPE_SINGLE){
            if(isset($_data["RefHolidayDateStart"]) && $_data["RefHolidayDateStart"]!=="" && isset($_data["RefHolidayDateEnd"]) && $_data["RefHolidayDateEnd"]!==""){
                $_er=0;
                if(\System\Util::checkDate($_data["RefHolidayDateStart"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateStart]");
                    $_er=1;
                }
                if(\System\Util::checkDate($_data["RefHolidayDateEnd"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateEnd]");
                    $_er=1;
                }
                if(!$_er){
                    if($_data["RefHolidayDateStart"]>$_data["RefHolidayDateEnd"]){
                        $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эхлэх огноо дуусах огнооноос хойш байна');
                    }
                }
            }
        }else{
            if(isset($_data["RefHolidayDateStart"]) && $_data["RefHolidayDateStart"]!=="" && isset($_data["RefHolidayDateEnd"]) && $_data["RefHolidayDateEnd"]!==""){
                $_er=0;
                $now=new \DateTime();
                $_year=$now->format("Y");
                if(\System\Util::checkDate($_year."-".$_data["RefHolidayDateStart"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateStart]");
                    $_er=1;
                }
                if(\System\Util::checkDate($_year."-".$_data["RefHolidayDateEnd"])){
                    $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Өдрийн формат буруу байна',"ref[RefHolidayDateEnd]");
                    $_er=1;
                }
                if(!$_er){
                    if($_year."-".$_data["RefHolidayDateStart"]>$_year."-".$_data["RefHolidayDateEnd"]){
                        $this->addError(\System\Error::ERROR_REQUIRED_EMPTY, 'Эхлэх огноо дуусах огнооноос хойш байна');
                    }
                }
            }
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
        if($this->RefHolidayID==="" && !isset($_data['cond'])){
            $this->addError(\System\Error::ERROR_DB, 'Устгах өгөгдөл олдсонгүй.');
            return false;
        }
        $qry=" delete from ".DB_DATABASE_HUMANRES.".".self::TBL_REF_HOLIDAY;
        if(!isset($_data['cond']) || count($_data['cond'])<1)
            $qry.=" where RefHolidayID= '".$this->RefHolidayID."'";
            else $qry.=" where ". implode(" and ", $_data['cond']);
            $res = $this->con->qryexec($qry);
            if($res<0){
                $this->addError(\System\Error::ERROR_DB, 'Өгөгдлийг устгаж чадсангүй. Info:: '.$this->con->getError());
                return false;
            }
            return true;
    }
    static function getStartWorkDateHoliday($_tmpdate='',$j=0){
        if(\System\Util::isWeekend($_tmpdate->format('Y-m-d'))){
            $_tmpdate->add(new \DateInterval('P1D'));
            if(\System\Util::isWeekend($_tmpdate->format('Y-m-d'))){
                $_tmpdate->add(new \DateInterval('P1D'));
            }
        }
        
        $_listDate=\Humanres\RefHolidayDayClass::getInstance()->getRowList([
            '_column'=>"RefDayDate",
            'holiday_datestart'=>$_tmpdate->format('Y-m-d'),
            "orderby"=>['RefDayDate']
        ]);
        $_add_date=0;
        $__j=0;
        foreach (array_unique($_listDate) as $r){
            $_tmpd= new \DateTime($r);
            
            $interval = $_tmpdate->diff($_tmpd);
            $a=$interval->format('%R%a');
            
            if($a==$__j){
                $_add_date++;
            }else{
                break;
            }
            $__j++;
        }
        if($__j>0){
            $_tmpdate->add(new \DateInterval('P'.($__j).'D'));
        }
        
        /****Ажилдаа орох өдөр ***/
        if(!\System\Util::isWeekend($_tmpdate->format('Y-m-d'))){
            return $_tmpdate->format('Y-m-d');
        }
        return self::getStartWorkDateHoliday($_tmpdate,++$j);
    }
}