<?php
namespace Office_API;
class ApiPrivClass extends \Office_API\API{
    protected $con;
    
    public function __construct($request) {
        $this->con=\Database::instance();
        parent::__construct($request);
    }
    protected function getModulePrivList() {
        $search=isset($this->parameters['search'])?$this->parameters['search']:array();
        if ($this->method == 'GET') {
            $tmpObj = new \System\SystemPrivClass($this->con);
            $tmpObj->searchTerm=$search;
            return $tmpObj->getList(2);
        } else {
            return "Only accepts GET requests";
        }
    }
    protected function getModulePriv() {
        $search=isset($this->parameters['search'])?$this->parameters['search']:array();
        if ($this->method == 'GET') {
            $tmpObj = new \System\SystemPrivClass($this->con);
            return $tmpObj->getPrivList($search);
        } else {
            return "Only accepts GET requests";
        }
    }
    protected function save() {
        $user_GET=  isset($this->parameters['user'])?unserialize($this->parameters["user"]):array();
        if(!isset($user_GET['networkid']) || $user_GET['networkid']==""){
            $result['state'] = false;
            $result['errorType'] = Error::ERROR_DB;
            $result['errorText'] = "Дэд бүтцийн дугаар хоосон байна.";
            return $result;
        }
        if(!isset($user_GET['systemid']) || $user_GET['systemid']==""){
            $result['state'] = false;
            $result['errorType'] = Error::ERROR_DB;
            $result['errorText'] = "Дэд системийн дугаар хоосон байна.";
            return $result;
        }
        if(!isset($user_GET['moduleid']) || $user_GET['moduleid']==""){
            $result['state'] = false;
            $result['errorType'] = Error::ERROR_DB;
            $result['errorText'] = "Дэд системийн модулийн дугаар хоосон байна.";
            return $result;
        }
        if(!isset($user_GET['personid']) || $user_GET['personid']==""){
            $result['state'] = false;
            $result['errorType'] = Error::ERROR_DB;
            $result['errorText'] = "Хэрэглэгчийн дугаар хоосон байна.";
            return $result;
        }
        
        $privObj=new \System\SystemPrivClass($this->con);
        $res=$privObj->savePriv($user_GET);
        if(!$res){
            $result['state'] = false;
            $result['errorType'] = Error::ERROR_DB;
            $result['errorText'] = $privObj->error->getErrorText();
            return $result;
        }

        $result['state'] = true;
        $result['text'] = "Эрх амжилттай солигдлоо.";
        return $result;
    }
}