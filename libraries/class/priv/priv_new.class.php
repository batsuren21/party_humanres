<?php
namespace Office;
class PrivClass extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_SYS_PACKAGE_MODULE="sys_packagesystemmodule";
    const TBL_SYS_PACKAGE_PRIV="sys_packagesystempriv";
    
    const PRIV_LETTER_ACCESS="1";
    const PRIV_LETTER_REG_ACCESS="2";
    const PRIV_LETTER_REG="3";
    const PRIV_LETTER_REG_SUPER="4";
    const PRIV_LETTER_MY_DEP="5";
    const PRIV_LETTER_MY_DEP_LOW="6";
    const PRIV_LETTER_MY_DEP_ALL="7";
    const PRIV_LETTER_MY_ORG="8";
    const PRIV_LETTER_STAT_DETAIL="9";
    const PRIV_LETTER_REPORT="10";
    const PRIV_LETTER_SETTINGS="11";
    const PRIV_LETTER_DECIDE="59";
    const PRIV_LETTER_ADDTIME="100";
    
    const PRIV_SENTLETTER_ACCESS="12";
    const PRIV_SENTLETTER_REG_ACCESS="13";
    const PRIV_SENTLETTER_REG="14";
    const PRIV_SENTLETTER_REG_SUPER="15";
    const PRIV_SENTLETTER_MY_DEP="16";
    const PRIV_SENTLETTER_MY_DEP_LOW="17";
    const PRIV_SENTLETTER_MY_DEP_ALL="18";
    const PRIV_SENTLETTER_MY_ORG="19";
    const PRIV_SENTLETTER_STAT_DETAIL="20";
    const PRIV_SENTLETTER_REPORT="21";
    
    const PRIV_PETITION_ACCESS="22";
    const PRIV_PETITION_REG_ACCESS="23";
    const PRIV_PETITION_REG="24";
    const PRIV_PETITION_REG_SUPER="25"; 
    const PRIV_PETITION_MY_DEP="26";
    const PRIV_PETITION_MY_DEP_LOW="27";
    const PRIV_PETITION_MY_DEP_ALL="28";
    const PRIV_PETITION_MY_ORG="29";
    const PRIV_PETITION_CLOSE="46";
    const PRIV_PETITION_SHIFT="47";
    const PRIV_PETITION_STAT_DETAIL="30";
    const PRIV_PETITION_REPORT="31";
    const PRIV_PETITION_SETTINGS="32";
    const PRIV_PETITION_ADDTIME="57";
    const PRIV_PETITION_SUSPEND="58";
    const PRIV_PETITION_DECIDE="60";
    const PRIV_PETITION_DECIDE_SUSPEND="63";
    const PRIV_PETITION_RETURN="64";
    const PRIV_PETITION_FELONY="65";
    const PRIV_PETITION_RESEARCH="70";
    
    const PRIV_PETONLINE_ACCESS="42";
    const PRIV_PETONLINE_REG_ACCESS="43";
    const PRIV_PETONLINE_REG="44";
    const PRIV_PETONLINE_REG_SUPER="45"; 
    
    const PRIV_FELONY_ACCESS="48";
    const PRIV_FELONY_REG_ACCESS="49";
    const PRIV_FELONY_REG="50";
    const PRIV_FELONY_RESTORE="51";
    const PRIV_FELONY_SUSPEND="52";
    const PRIV_FELONY_TIME="53";
    const PRIV_FELONY_MY_DEP="54";
    const PRIV_FELONY_MY_DEP_ALL="55";
    const PRIV_FELONY_MY_ОRG="56";
    const PRIV_FELONY_REPORT="61";
    const PRIV_FELONY_REPORT_LIST="62";
    const PRIV_FELONY_REPORT_LIST_SUPER="101";
    const PRIV_FELONY_CONFIRM="74";
    const PRIV_FELONY_GRAPHIC="75";
    const PRIV_FELONY_NOTICE="98";
    const PRIV_FELONY_COURT="99";
    
    const PRIV_HUMANRES_ACCESS="66";
    const PRIV_HUMANRES_REG_ACCESS="67";
    const PRIV_HUMANRES_REG="68";
    const PRIV_HUMANRES_LIST="76";
    const PRIV_HUMANRES_REPORT="102";
    const PRIV_HUMANRES_HOLIDAY="106";
    
    const PRIV_ADMIN_ACCESS="69";
    const PRIV_ADMIN_USER_PRIV="71";
    
    const PRIV_REFERENCE_ACCESS="72";
    const PRIV_REFERENCE_ORGANLIST="73";
    const PRIV_REFERENCE_PETORGANLIST="86";
    const PRIV_REFERENCE_PETDIRECTIONLIST="87";
    const PRIV_REFERENCE_PETSECTORLIST="88";
    const PRIV_REFERENCE_AREA="97";
    
    const PRIV_VIOLATION_ACCESS="77";
    const PRIV_VIOLATION_REG_ACCESS="78";
    const PRIV_VIOLATION_REG="79";
    const PRIV_VIOLATION_MY_DEP="80";
    const PRIV_VIOLATION_MY_DEP_ALL="81";
    const PRIV_VIOLATION_MY_ОRG="82";
    const PRIV_VIOLATION_REPORT="83";
    
    const PRIV_DISCIPLINE_ACCESS="89";
    const PRIV_DISCIPLINE_REG_ACCESS="90";
    const PRIV_DISCIPLINE_REG="91";
    const PRIV_DISCIPLINE_REG_SUPER="96";
    
    const PRIV_INVESTIGATION_ACCESS="92";
    const PRIV_INVESTIGATION_REG_ACCESS="93";
    const PRIV_INVESTIGATION_REG="94";
    const PRIV_INVESTIGATION_TIME="95";
    
    const PRIV_BANK_ACCESS="103";
    const PRIV_BANK_REG="104";
    const PRIV_BANK_SUPER="105";
    
    const PRIV_FELONYTICKET_ACCESS="107";
    const PRIV_FELONYTICKET_REG="108";
    const PRIV_FELONYTICKET_SUPER="109";
    
    public function __construct(){
        $this->con=\Database::instance();
        parent::__construct();
    }
    
    public static function getInstanceByArray($row){
        $instance = new self();
        $instance->initArray($row);
        return $instance;
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
    
    static function getQueryCondition($search=array()){
        $_mainTable= isset($search['maintable'])?$search['maintable']:"T";
        
        $qryselect=array();
        $join_inner=array();
        $join_inner_cond="";
        $join_left=array();
        $where_cond="";
        $qrycond=$_mainTable=="T"?" where":" and";
        
        $join_left[self::TBL_SYS_PACKAGE_PRIV]=" left join ".DB_DATABASE.".". self::TBL_SYS_PACKAGE_PRIV." T1 on T.ModuleID=T1.PrivModuleID";
        $join_left[self::TBL_SYS_PACKAGE_PRIV].=" and T1.`PrivOfficeID` = '".(isset($search['priv_officeid'])?$search['priv_officeid']:-1)."'";
        $join_left[self::TBL_SYS_PACKAGE_PRIV].=" and T1.`PrivPersonID` = '".(isset($search['priv_personid'])?$search['priv_personid']:-1)."'";
        
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
        $qry.=" from ".DB_DATABASE.".".self::TBL_SYS_PACKAGE_MODULE." T";
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
        $qry = "select COUNT(ModuleID) as AllCount";
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
}
