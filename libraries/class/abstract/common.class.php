<?php
namespace Office;
abstract class CommonMain{
    protected $con;
    protected $_error=array();
    protected $_data=array();
    
    public function __construct(){
    }
    public function setData($row=array()){
        $this->_data=$row;
    }
    public function getData(){
        return $this->_data;
    }
    public function addError($_type=\System\Error::ERROR_DB,$_text="",$_field=""){
        if($_field!="")
            $this->_error['field'][] = \System\Error::getError($_type, $_text, $_field);
        else $this->_error['general'][] = \System\Error::getError($_type, $_text);
    }
    public function hasError(){
        return count($this->_error)>0;
    }
    public function clearError() {
        $this->_error = array();
    }
    public function getError() {
        return $this->_error;
    }
}