<?php
    namespace Office;
    class Permission {
        public static $hasBackLink = true;
        
        private static $loggodUser;
 
        static function isLoginPerson(){
           
            if (isset($_COOKIE[COOKIENAME]['uid']) && $_COOKIE[COOKIENAME]['uid']!="") {
                $decrypt_userid = (int)PasswordClass::decrypt($_COOKIE[COOKIENAME]['uid']);
                
                $refreshTime=new \DateTime();
                if(isset($_SESSION['refreshtime'])){
                    $refreshTime=new \DateTime($_SESSION['refreshtime']);
                }
                $now  = new \DateTime();
                $dDiff = $refreshTime->diff($now);
                if(1 ||
                    !isset($_SESSION[SESSSYSINFO]) ||
                    !isset($_SESSION[SESSUSERID]) ||
                    !isset($_SESSION['refreshtime']) ||
                    $dDiff->days>0 ||
                    empty($_SESSION[SESSUSERID]) ||
                    $_SESSION[SESSUSERID] != $decrypt_userid ||
                    $_SESSION['ltime'] != $_COOKIE[COOKIENAME]['ltime']
                    ){
                        $_SESSION[SESSUSERID] = $decrypt_userid;
                        $_SESSION['ltime'] = $_COOKIE[COOKIENAME]['ltime'];
                        $_SESSION['refreshtime']=$now->format("Y-m-d");
                        $obj = "";
                        
                        $personObj=\Humanres\PersonClass::getInstance()->getRow(['person_id'=>$_SESSION[SESSUSERID]]);
                        if(!$personObj->isExist()) return false;
                       
                        $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$personObj->PersonEmployeeID]);
                        if(!$employeeObj->isExist() || !$employeeObj->EmployeeIsActive) return false;
                        
                        $_officeid=\Office\OfficeConfig::getOfficeID();
                        if($_officeid<1) return false;
                        $priv= json_decode ("{}");
                        $priv->EmployeeID=$personObj->PersonEmployeeID;
                        $priv->PersonID=$personObj->PersonID;
                        $priv->OrganID=$employeeObj->EmployeeOrganID;
                        $priv->DepartmentID=$employeeObj->EmployeeDepartmentID;
                        $priv->PositionID=$employeeObj->EmployeePositionID;
                        $priv->PeriodID=$employeeObj->EmployeePeriodID;
                        $priv->OfficeID=$_officeid;
                        $priv->userpriv=\Office\PrivClass::getInstance()->getRowList(array("_mainindex"=>"ModuleID","_select"=>array("T.ModuleID","IF(T1.`Priv` IS NULL, T.`ModulePrivDefault`, T1.`Priv`) AS Priv"),"priv_officeid"=>$_officeid,"priv_personid"=>$_SESSION[SESSUSERID]));
                        $_SESSION[SESSSYSINFO] = $priv;
                }
                return true;
            }else{
                return false;
            }
        }
        final public static function getLoggedUser(){
            if (!isset(self::$loggodUser)) {
                self::$loggodUser = \Humanres\PersonClass::getInstance()->getRow(array("person_id"=>isset($_SESSION[SESSUSERID])?$_SESSION[SESSUSERID]:""));
            }
            return self::$loggodUser;
        }
        static function getPriv($moduleid = ""){
            if($moduleid != "") {
                if(!isset($_SESSION[SESSSYSINFO]) || empty($_SESSION[SESSSYSINFO])){
                    return 0;
                }
                if(isset($_SESSION[SESSSYSINFO]->userpriv) && isset($_SESSION[SESSSYSINFO]->userpriv[$moduleid]['Priv'])) {
                    return $_SESSION[SESSSYSINFO]->userpriv[$moduleid]['Priv'];
                }
            }
            return 0;
        }

       
    }

    class PasswordClass {
        
        static $key = "smartgovernment";
        static $cipher = "aes-128-gcm";
        
        static function salt($length = 10) {
            $chars = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM[]~!@#*_+=|?";
            $size = strlen($chars);
            $salt = '';
            for ($i = 0; $i < $length; $i++) {
                $salt .= $chars[rand(0, $size - 1)];
            }
            return $salt;
        }
        
        static function hash($password) {
            $salt = PasswordClass::salt();
            $strongPass = $password . $salt;
            $hash = hash('sha256', $strongPass) . $salt;
            return $hash;
        }
        
        static function check($password, $hash) {
            if (empty($password) || empty($hash)) {
                return false;
            }
            $baseHash = substr($hash, 0, 64);
            $salt = substr($hash, 64);
            if (hash('sha256', $password . $salt) == $baseHash) {
                return true;
            }
            return false;
        }
        
        static function encrypt($value) {
            $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
            $iv = openssl_random_pseudo_bytes($ivlen);
            $ciphertext_raw = openssl_encrypt($value, $cipher, PasswordClass::$key, $options = OPENSSL_RAW_DATA, $iv);
            $hmac = hash_hmac('sha256', $ciphertext_raw, PasswordClass::$key, $as_binary = true);
            return base64_encode($iv . $hmac . $ciphertext_raw);
        }
        
        static function decrypt($value) {
            $c = base64_decode($value);
            $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
            $iv = substr($c, 0, $ivlen);
            $hmac = substr($c, $ivlen, $sha2len = 32);
            $ciphertext_raw = substr($c, $ivlen + $sha2len);
            return openssl_decrypt($ciphertext_raw, $cipher, PasswordClass::$key, $options = OPENSSL_RAW_DATA, $iv);
        }
        
    }