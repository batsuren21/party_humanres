<?php
namespace OfficeTrait;
trait Common {
    public static function getInstance($row=array()){
        $instance = new self();
        $instance->setData($row);
        return $instance;
    }
    function init($row=array()){
        $this->_data=$row;
        return $this;
    }
    function getRow($search=array()){
        $list=$this->getRowList($search);
        return self::getInstance(isset($list[0])?$list[0]:array());
    }
    function getRowRef($search=array(),$_table){
        $list=$this->getRowList($search,$_table);
        return self::getInstance(isset($list[0])?$list[0]:array());
    }
    function initRow($search=array()){
        $list=$this->getRowList($search);
        $this->setData(isset($list[0])?$list[0]:array());
        return $this;
    }
    public static function getParam($param){
        if($param==="") $param=-1;
        elseif(is_array($param) && count($param)<1) $param=[-1];
        return $param;
    }
    
}
