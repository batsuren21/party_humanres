<?php
namespace Office;
class OfficeConfig  extends \Office\CommonMain{
    use \OfficeTrait\Common;
    const TBL_OFFICE="office";
    const TBL_OFFICE_ORGAN="office_organ";
    const TBL_OFFICE_CONFIG="office_config";
    
    private static $_officeid;
    static $paramconfig;
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
    
    final public static function getOfficeID(){
        
        if (isset(self::$_officeid)) {
            return self::$_officeid;
        }
        
        if(!isset($_SESSION[SESSUSERID])) return 0;
        
        
        $con=\Database::instance();
        
        $qry="select T.*";
        $qry.=" from ".DB_DATABASE.".".self::TBL_OFFICE_ORGAN." T";
        $result=$con->select($qry);
        if($row = $result->fetch_array(MYSQLI_ASSOC)){
            self::$_officeid=$row['RowOfficeID'];
            return self::$_officeid;
        }
        return 0;
    }
    public static function getOffice(){
        $_officeid=self::getOfficeID();
        $con=\Database::instance();
        
        $qry="select T.*";
        $qry.=" from ".DB_DATABASE.".".self::TBL_OFFICE." T";
        $qry.=" where T.OfficeID = ".$_officeid;
        $result=$con->select($qry);
        if($row = $result->fetch_array(MYSQLI_ASSOC)){
            return self::getInstance($row);
        }
        return self::getInstance($row);
    }
    function isExist(){
        return isset($this->_data["OfficeID"]) && $this->_data["OfficeID"]!=""?true:false;
    }
    static public function get($name){
        if (!isset(self::$paramconfig) || count(self::$paramconfig)<1) {
            self::getInstance()->init(self::$_officeid);
        }
        return isset(self::$paramconfig[$name])?self::$paramconfig[$name]:"";
    }
    public function init($_officeid=""){
        $_officeid=($_officeid=="")?self::getOfficeID():$_officeid;
        $qry="select T.*";
        $qry.=" from ".DB_DATABASE.".".self::TBL_OFFICE_CONFIG." T";
        $qry.=" where T.ConfigOfficeID=".$_officeid;
        $result=$this->con->select($qry);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            self::$paramconfig[$row['ConfigName']]=$row['ConfigValue'];
        }
        return $this;
    }
}