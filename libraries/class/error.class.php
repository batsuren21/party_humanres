<?php
    namespace System;
    class Error {
        private $errorType;
        private $errorText;
    	
        const ERROR_DB = 'ERROR_DB';
        const ERROR_UPLOAD_FILE = 'ERROR_UPLOAD_FILE';
        const ERROR_REQUIRED_EMPTY = 'ERROR_REQUIRED_EMPTY';
      	const ERROR_NUMBER = 'ERROR_NUMBER';
        const ERROR_UNKNOWN = 'ERROR_UNKNOWN';
        const ERROR_SYSTEM = 'ERROR_SYSTEM';
        const ERROR_SYSTEM_NO = '';

        public function __construct($errorType = '', $errorText = ''){
            $this->errorType = $errorType;
            $this->errorText = $errorText;
        }
        static public function getError($errortype="",$errortext="",$errorfield=""){
            return array("_type"=>$errortype,"_text"=>$errortext,"_field"=>$errorfield);
        }
    }