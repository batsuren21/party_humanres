<?php
namespace Office;
class System {
    static public $custom_css = array ();
    static public $custom_js = array ();

    static function getPage($page_url='',$_param=array(),$rf=DRF) {
        $page_url=$rf."/modules/".$page_url.".php";
        $page_content = '';
        ob_start();
        include($page_url);
        $page_content = ob_get_contents();
        ob_end_clean();
        return $page_content;
    }
    static function getPageContent($page_url='',$_param=array(),$rf=DRF) {
        $page_url=$rf.$page_url;
        $page_content = '';
        ob_start();
        include($page_url);
        $page_content = ob_get_contents();
        ob_end_clean();
        return $page_content;
    }
}
