<?php
    require_once("../../libraries/connect.php");
    $__con = new Database();

    $action = isset($_GET['action']) ? $_GET['action'] : "";
    
    switch ($action) {
        case "login":
            $_errors=array();
            if(!isset($_POST['user']['name']) || $_POST['user']['name']==""){
                $_errors['field'][] = \System\Error::getError(\System\Error::ERROR_DB,"Хэрэглэгчийн нэр хоосон байна","user[name]");
            }
            if(!isset($_POST['user']['password']) || $_POST['user']['password']==""){
                $_errors['field'][] = \System\Error::getError(\System\Error::ERROR_DB,"Хэрэглэгчийн нууц үг хоосон байна","user[password]");
            }
            if(count($_errors)>0){
                $_result =array(
                    "_state" => false,
                    "_errors"=>$_errors 
                );
                header("Content-type: application/json");
                echo json_encode($_result);
                exit;
            }
            $username = mysqli_real_escape_string($__con->getConnection(), trim($_POST['user']['name']));
            $userpassword = mysqli_real_escape_string($__con->getConnection(), $_POST['user']['password']);
            
            $userObj =Humanres\PersonClass::getInstance()->getRow(array("person_username"=>$username,"person_username_cond"=>"eq"));
            if($userObj->isExist()){
                if($userObj->PersonUserPassword==md5($userpassword)){
                    
                    $employeeObj=\Humanres\EmployeeClass::getInstance()->getRow(['employee_id'=>$userObj->PersonEmployeeID]);
                    
                    if($employeeObj->isExist() && $employeeObj->EmployeeIsActive){
                        $encrypt_userid = Office\PasswordClass::encrypt($userObj->PersonID);
                        setcookie(COOKIENAME."[uid]", $encrypt_userid, COOKIE_EXPIRE_TIME, COOKIE_PATH, COOKIE_DOMAIN);
                        setcookie(COOKIENAME."[ltime]", time(), COOKIE_EXPIRE_TIME, COOKIE_PATH, COOKIE_DOMAIN);
                        /*
                        setcookie(COOKIENAME."[uid]", $encrypt_userid, [
                            'expires' => COOKIE_EXPIRE_TIME,
                            'path' => COOKIE_PATH,
                            'domain' => COOKIE_DOMAIN,
                            'secure' => false,
                            'httponly' => false,
                            'samesite' => 'Lax',
                        ]);
                        setcookie(COOKIENAME."[ltime]", time(), [
                            'expires' => COOKIE_EXPIRE_TIME,
                            'path' => COOKIE_PATH,
                            'domain' => COOKIE_DOMAIN,
                            'secure' => false,
                            'httponly' => false,
                            'samesite' => 'Lax',
                        ]);
                        */
                        
                        
                        $_result =array(
                            "_state" => true,
                            "_text"=>"Амжилттай нэвтэрлээ.",
                            "_url"=>SYS_HOST.RF."/home"
                        );
                        header("Content-type: application/json");
                        echo json_encode($_result);
                        exit;
                    }else{
                        $_result =array(
                            "_state" => false,
                            "_errors"=>array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Нэвтрэх боломжгүй байна")))
                        );
                        header("Content-type: application/json");
                        echo json_encode($_result);
                        exit;
                    }
                    
                }
            }
            $_result =array(
                "_state" => false,
                "_errors"=>array("general"=>array(\System\Error::getError(\System\Error::ERROR_DB,"Хэрэглэгчийн нэр эсвэл нууц үг буруу байна"))) 
            );
            header("Content-type: application/json");
            echo json_encode($_result);
            exit;
        case "logout":
            setcookie(COOKIENAME."[uid]", "", -1, COOKIE_PATH, COOKIE_DOMAIN);
            setcookie(COOKIENAME."[ltime]", "", -1, COOKIE_PATH, COOKIE_DOMAIN);
            session_destroy();
            header("Location: ".RF_LOGIN);
            exit;
        default :
            $_SESSION['alert_msg'] = "Буруу хандалт хийсэн байна!|1";
            
            break;
    }
