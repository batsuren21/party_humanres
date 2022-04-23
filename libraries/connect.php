<?php
session_start();

header("Content-type: text/html; charset=utf-8");
header("Cache-control: max-age=3600, must-revalidate");

date_default_timezone_set('Asia/Ulaanbaatar');

require_once "config.lib.php";

require_once "config.text.php";
require_once "requireclass.php";
if (DISPLAY_ALL_ERRORS){
    ini_set("display_errors", "on");
}else{
    ini_set("display_errors", "off");
}

class Database{
    private $hConn;
    public static $driver="mysqli";
    private static $instance=[];
    public function __construct($database_name=DB_DATABASE, $username=DB_USER, $password=DB_PASSWORD, $host =DB_HOST){
        self::$instance[$host] = $this;
        $this->hConn = new mysqli($host, $username, $password, $database_name);
        if ($this->hConn->connect_error && MYSQL_SHOW_EXCEPTION) {
            die("'".$host."' дээрх мэдээллийн баазын сервертэй холбогдох боломжгүй байна!\n".$this->hConn->connect_error);
        }
        $this->hConn->query("SET SESSION interactive_timeout=120");
        $this->hConn->query("SET NAMES 'utf8'");
        return $this->hConn;
    }
    final public static function instance($database_name=DB_DATABASE, $username=DB_USER, $password=DB_PASSWORD, $host =DB_HOST){
        if (!isset(self::$instance[$host])) {
            self::$instance[$host]= new Database($database_name, $username, $password, $host);
        }
        return self::$instance[$host];
    }
    public function __destruct(){
        if(is_resource(($this->hConn))){
            $this->hConn->close();
            $this->hConn=null;
        }
    }
    public function getConnection(){
        return $this->hConn;
    }
    public function getError(){
        return mysqli_error($this->hConn);
    }
    public function select($p_qry){
        $hRes = $this->hConn->query($p_qry);
        if(!$hRes){
            $this->errorhandler1();
            return \System\Error::ERROR_DB;
        }
        return $hRes;
    }
    public function insert($p_qry) {
        $hRes = $this->hConn->query($p_qry);
        if(!$hRes){
            $this->errorhandler($p_qry);
            return -1;
        }
        return $this->hConn->insert_id;
    }
    public function qryexec($p_qry){
        $hRes =$this->hConn->query($p_qry);
        if(!$hRes){
            $this->errorhandler($p_qry);
            return -1;
        }
        return $this->hConn->affected_rows;
    }
    public function multi_qryexec($p_qry){
        $hRes =$this->hConn->multi_query($p_qry);
        if(!$hRes){
            $this->errorhandler($p_qry);
            return -1;
        }
        return $this->hConn->affected_rows;
    }
    public function resetFetch($result){
        if (mysqli_num_rows($result) > 0)mysqli_data_seek($result, 0);
    }
    public function seek($result,$num){
        if (mysqli_num_rows($result) > 0)mysqli_data_seek($result, $num);
    }
    public function errorhandler1(){
        if(MYSQL_SHOW_EXCEPTION){
            $err = mysqli_error($this->hConn);
        }
    }
    public function errorhandler($p_qry){
        if(MYSQL_SHOW_EXCEPTION){
            $err = mysqli_error($this->hConn)."\n Query: ".$p_qry;
        }
    }
    
    public function beginCommit(){
        $this->hConn->query("set autocommit=0");
        $this->hConn->query("begin");
    }
    public function closeCommit(){
        $this->hConn->query("commit");
        $this->hConn->query("set autocommit=1");
    }
    static public function getRow($result=""){
        if($result=="") return array();
        $list=self::getList($result);
        return count($list)>0?reset($list):array();
    }
    static public function getRowCell($result="", $type=array("colname"=>"AllCount","default"=>0)){
        if($result=="") return $type["default"];
        if($row = $result->fetch_object()){
            return isset($row->{$type['colname']})?$row->{$type['colname']}:$type["default"];
        }
        return $type["default"];
    }
    static public function getParam($_list=[],$_param=""){
        if(isset($_list[$_param])) return $_list[$_param];
        return [];
    }
    static public function getList($result="", $search=array()){
        $list=Array();
        if($result=="") return $list;
        
        if(isset($search['_column'])){
            while($row = $result->fetch_array(MYSQLI_ASSOC)){
                if(isset($row[$search['_column']]) && $row[$search['_column']]!="")
                    $list[]=$row[$search['_column']];
            }
        }else{
            if(!isset($search['_getparams'])){
                if(!isset($search['_mainindex']) && !isset($search['_mainindexs'])){
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        $list[] = $row;
                    }
                }elseif(!isset($search['_mainindexs']) && isset($search['_mainindex'])){
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        $list[$row[$search['_mainindex']]] = $row;
                    }
                }elseif(isset($search['_mainindexs']) && !isset($search['_mainindex'])){
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        if(isset($list[$row[$search['_mainindexs']]])){
                            $list[$row[$search['_mainindexs']]][] = $row;
                        }else{
                            $list[$row[$search['_mainindexs']]]=array(0=>$row);
                        }
                    }
                }elseif(isset($search['_mainindexs']) && isset($search['_mainindex'])){
                    while($row = $result->fetch_array(MYSQLI_ASSOC)){
                        if(isset($list[$row[$search['_mainindexs']]])){
                            $list[$row[$search['_mainindexs']]][$row[$search['_mainindex']]] = $row;
                        }else{
                            $list[$row[$search['_mainindexs']]]=array($row[$search['_mainindex']]=>$row);
                        }
                    }
                }
            }else{
                if(count($search['_getparams'])>0){
                    $list_main=array();
                    $list_params=array();
                    if(!isset($search['_mainindex'])){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                            $list_main[] = $row;
                            foreach($search['_getparams'] as $param){
                                $list_params[$param][]= isset($row[$param]) && $row[$param]!=""?$row[$param]:"-1";
                            }
                        }
                    }elseif(!isset($search['_mainindexs']) && !isset($search['_mainindexs'])){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                            $list_main[$row[$search['_mainindex']]] = $row;
                            foreach($search['_getparams'] as $param){
                                $list_params[$param][]= isset($row[$param]) && $row[$param]!=""?$row[$param]:"-1";
                            }
                        }
                    }elseif(isset($search['_mainindexs'])){
                        while($row = $result->fetch_array(MYSQLI_ASSOC)){
                            if(isset($list_main[$row[$search['_mainindexs']]])){
                                $list_main[$row[$search['_mainindexs']]][] = $row;
                            }else{
                                $list_main[$row[$search['_mainindexs']]]=array(0=>$row);
                            }
                            foreach($search['_getparams'] as $param){
                                $list_params[$param][]= isset($row[$param]) && $row[$param]!=""?$row[$param]:"-1";
                            }
                        }
                    }
                    $list['_list']=$list_main;
                    foreach($search['_getparams'] as $param){
                        $list[$param]= isset($list_params[$param])?array_unique($list_params[$param]):array();
                    }
                }
            }
        }
        return $list;
    }
}