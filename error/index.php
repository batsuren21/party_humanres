<?php
if(isset($module) || isset($_GET['page'])){
    require_once 'main.php';
}else{
    require_once '../libraries/connect.php';
    require_once 'mainbody.php';
}