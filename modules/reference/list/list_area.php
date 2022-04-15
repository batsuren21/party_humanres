<?php
    $parent = isset($_REQUEST["parent"])?$_REQUEST["parent"]:"#";
    $data = array();

    
    $srch_parent=$parent == "#"?0:$parent;
    
    $_resultList= \Office\AreaClass::getInstance()->getRowList(["area_parentid"=>$srch_parent,"orderby"=>"AreaName"]);
    foreach ($_resultList as $row){
        $areaObj= \Office\AreaClass::getInstance($row);
        $is_child=!in_array($areaObj->AreaGlobalID, [6,7]);
        $data[] = array(
            "id" => $areaObj->AreaID,
            "text" => $areaObj->AreaName,
            "icon" => "fa fa-file icon-lg",
            "children" => $is_child,
            "type" => "root",
            "data"=>array("isaddable"=>$is_child)
        );
    }
    header('Content-type: text/json');
    header('Content-type: application/json');
    echo json_encode($data);