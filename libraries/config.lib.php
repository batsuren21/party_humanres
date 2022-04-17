<?php
    define("DB_HOST", 'localhost');
    define("DB_USER", 'root');
    define("DB_PASSWORD", 'rtpw');

    define("DB_DATABASE", 'party_office');
    define("DB_DATABASE_HUMANRES", 'party_humanres');

    define("MYSQL_SHOW_EXCEPTION", 1);
    define("DISPLAY_ALL_ERRORS", 1);

    define("RF", str_replace($_SERVER['DOCUMENT_ROOT'], "", str_replace("\\", "/", dirname(dirname(__FILE__)))));
    define("DRF", $_SERVER['DOCUMENT_ROOT'] . RF);

    define("CHARSET", "utf-8");
    
    $sub=explode("/", RF);
    $subcount=count($sub);
    define("RF_SUB",($subcount>0)?str_replace($sub[$subcount-1],"",RF):"");
    define("SYS_HOST", "http://".$_SERVER['SERVER_NAME']);
    
    define("RF_LOGIN",SYS_HOST."/github/party_humanres/login");
    define("RF_ERROR",SYS_HOST.RF."/error");
    
    if (!isset($_SESSION[RF . 'randnum'])){
        $_SESSION[RF . 'randnum'] = RF . "_" . rand(10000, 100000);
    }
    
    define("SESSRANDNUM", "_" . $_SESSION[RF . 'randnum']);
    define("SESSUSERID", SESSRANDNUM . "userid");
    define("SESSSYSINFO", SESSRANDNUM ."SYSINFO");
    
    define("COOKIENAME", "GLOBCVAL_SG");
    define("COOKIE_EXPIRE_TIME", time() + 60 * 60 * 24 * 30);
    define("COOKIE_PATH", "/");
    define("COOKIE_DOMAIN", null);