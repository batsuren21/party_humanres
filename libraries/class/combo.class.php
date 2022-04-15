<?php
    namespace System;
    class Combo {
        const SELECT_ALL=0;
        const SELECT_SINGLE=1;
        const SELECT_FIRST=2;
        const SELECT_NONE=3;
        const SELECT_CUSTOM=4;

        public function __construct() {}
        static function getCombo($param=array()){
            $_flag=!isset($param['flag'])?self::SELECT_ALL:$param['flag'];
            $_selected=!isset($param['selected'])?"":$param['selected'];
            $_value=!isset($param['value'])?0:$param['value'];
            $_title=!isset($param['title'])?1:is_array($param['title'])?$param['title']:$param['title'];
            $_selected_array=explode(",",$_selected);
            if($_selected!="" && count($_selected_array)<1){
                $_selected_array=array($_selected);
            }
            if($_flag=='' || $_flag==self::SELECT_ALL){
                print('<option '.(is_array($_selected) && in_array("", $_selected) || !is_array($_selected) && $_selected==""?"selected":"").' value="">--- Бүгд ---</option>');
            }elseif($_flag==self::SELECT_SINGLE){
                print('<option '.(is_array($_selected) && in_array("", $_selected) || !is_array($_selected) && $_selected==""?"selected":"").' value="">--- Сонгох ---</option>');
            }elseif ($_flag==self::SELECT_FIRST) {
                print('<option '.(is_array($_selected) && in_array("", $_selected) || !is_array($_selected) && $_selected==""?"selected":"").' value=""></option>');
            }elseif ($_flag==self::SELECT_CUSTOM) {
                $_flagtext=!isset($param['flagtext'])?"":$param['flagtext'];
                $_flagvalue=!isset($param['flagvalue'])?"":$param['flagvalue'];
                print('<option '.(is_array($_selected) && in_array("", $_selected) || !is_array($_selected) && $_selected==""?"selected":"").' value="'.$_flagvalue.'">'.$_flagtext.'</option>');
            }
            if(count($param['data'])>0)
            foreach ($param['data'] as $rowdata){
                if(is_array($_title)){
                    $sub_title="";
                    $j=0;
                    foreach($_title as $stitle){
                        $sub_title.=($j==1 && $j>0?" ":" ").$rowdata[$stitle];
                        $j++;
                    }
                }else{
                    $sub_title=$rowdata[$_title];
                }
                print('<option '.(in_array($rowdata[$_value],$_selected_array)?"selected":"" ).' value="'.$rowdata[$_value].'" '.(isset($rowdata["data"]) && $rowdata["data"]!=""?$rowdata["data"]:"").'>'.$sub_title.'</option>');
            }
            
        }
        static function getComboGroup($param=array()){
            $_flag=!isset($param['flag'])?self::SELECT_ALL:$param['flag'];
            $_selected=!isset($param['selected'])?"":$param['selected'];
            $_value=!isset($param['value'])?0:$param['value'];
            $_title=!isset($param['title'])?1:$param['title'];
            $_group=!isset($param['group'])?2:$param['group'];
            
            $_selected_array=explode(",",$_selected);
            if($_selected!="" && count($_selected_array)<1){
                $_selected_array=array($_selected);
            }
            if($_flag=='' || $_flag==self::SELECT_ALL){
                print('<option '.(count($_selected_array)<1?"selected":"").' value="">--- Бүгд ---</option>');
            }elseif($_flag==self::SELECT_SINGLE){
                print('<option '.(count($_selected_array)<1?"selected":"").' value="">--- Сонгох ---</option>');
            }elseif ($_flag==self::SELECT_FIRST) {
                print('<option '.(count($_selected_array)<1?"selected":"").' value=""></option>');
            }elseif ($_flag==self::SELECT_CUSTOM) {
                $_flagtext=!isset($param['flagtext'])?"":$param['flagtext'];
                $_flagvalue=!isset($param['flagvalue'])?"":$param['flagvalue'];
                print('<option '.(count($_selected_array)<1?"selected":"").' value="'.$_flagvalue.'">'.$_flagtext.'</option>');
            }
            if(count($param['data'])>0){
                $group="";
                foreach ($param['data'] as $rowdata){
                    if($group!=$rowdata[$_group]){
                        if($group!="") print('</optgroup>');
                        print('<optgroup label="'.$rowdata[$_group].'">');
                        $group=$rowdata[$_group];
                    }
                    if(is_array($_title)){
                        $sub_title="";
                        $j=0;
                        foreach($_title as $stitle){
                            $sub_title.=($j==1 && $j>0?". ":" ").$rowdata[$_title];
                            $j++;
                        }
                    }else{
                        $sub_title=$rowdata[$_title];
                    }
                    print('<option '.(in_array($rowdata[$_value],$_selected_array)?"selected":"").' value="'.$rowdata[$_value].'" '.(isset($rowdata["data"]) && $rowdata["data"]!=""?$rowdata["data"]:"").'>'.$sub_title.'</option>');
                }
                print('</optgroup>');
            }
        }
    }