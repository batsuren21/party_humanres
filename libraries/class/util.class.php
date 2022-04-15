<?php
namespace System;
class Util {
        const INPUT_TEXT=1;
        const INPUT_NONTEXT=2;
        const CHARS="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        const CHARS_LOWER="abcdefghijklmnopqrstuvwxyz0123456789";
        static public $charset = "utf-8";
	static public $letters_unicode = array (184 => "ё", 
		168 => "Ё", 186 => "ө", 170 => "Ө", 175 => "Ү", /*tom Y useg*/
		191 => "ү", /* jijig v useg*/ 192 => "А", 193 => "Б", 194 => "В", 195 => "Г", 196 => "Д", 197 => "Е", 198 => "Ж", 199 => "З", 
		200 => "И", 201 => "Й", 202 => "К", 203 => "Л", 204 => "М", 205 => "Н", 206 => "О", 207 => "П", 208 => "Р", 209 => "С", 
		210 => "Т", 211 => "У", 212 => "Ф", 213 => "Х", 214 => "Ц", 215 => "Ч", 216 => "Ш", 217 => "Щ", 218 => "Ъ", 219 => "Ы", 
		220 => "Ь", 221 => "Э", 222 => "Ю", 223 => "Я", 224 => "а", 225 => "б", 226 => "в", 227 => "г", 228 => "д", 229 => "е", 
		230 => "ж", 231 => "з", 232 => "и", 233 => "й", 234 => "к", 235 => "л", 236 => "м", 237 => "н", 238 => "о", 239 => "п", 
		240 => "р", 241 => "с", 242 => "т", 243 => "у", 244 => "ф", 245 => "х", 246 => "ц", 247 => "ч", 248 => "ш", 249 => "щ", 
		250 => "ъ", 251 => "ы", 252 => "ь", 253 => "э", 254 => "ю", 255 => "я" );
	static public $letters_nonunicode = array (184 => "¸", /*ё*/
		168 => "¨", /*Ё*/ 186 => "º", /*ө*/ 170 => "ª", /*Ө*/ 175 => "¯", /*Ү*/ 191 => "¿", /*ү*/ 192 => "À", /*А*/ 193 => "Á", /*Б*/ 194 => "Â", /*В*/
		195 => "Ã", /*Г*/ 196 => "Ä", /*Д*/ 197 => "Å", /*Е*/ 198 => "Æ", /*Ж*/ 199 => "Ç", /*З*/ 200 => "È", /*И*/ 201 => "É", /*Й*/ 202 => "Ê", /*К*/
		203 => "Ë", /*Л*/ 204 => "Ì", /*М*/ 205 => "Í", /*Н*/ 206 => "Î", /*О*/ 207 => "Ï", /*П*/ 208 => "Ð", /*Р*/ 209 => "Ñ", /*С*/ 210 => "Ò", /*Т*/
		211 => "Ó", /*У*/ 212 => "Ô", /*Ф*/ 213 => "Õ", /*Х*/ 214 => "Ö", /*Ц*/ 215 => "×", /*Ч*/ 216 => "Ø", /*Ш*/ 217 => "Ö", /*Щ*/ 218 => "Ú", /*Ь*/
		219 => "Û", /*Ы*/ 220 => "Ü", /*Ь*/ 221 => "Ý", /*Э*/ 222 => "Þ", /*Ю*/ 223 => "ß", /*Я*/ 224 => "à", /*а*/ 225 => "á", /*б*/ 226 => "â", /*в*/
		227 => "ã", /*г*/ 228 => "ä", /*д*/ 229 => "å", /*е*/ 230 => "æ", /*ж*/ 231 => "ç", /*з*/ 232 => "è", /*и*/ 233 => "é", /*й*/ 234 => "ê", /*к*/
		235 => "ë", /*л*/ 236 => "ì", /*м*/ 237 => "í", /*н*/ 238 => "î", /*о*/ 239 => "ï", /*п*/ 240 => "ð", /*р*/ 241 => "ñ", /*с*/ 242 => "ò", /*т*/
		243 => "ó", /*у*/ 244 => "ô", /*ф*/ 245 => "õ", /*х*/ 246 => "ö", /*ц*/ 247 => "÷", /*ч*/ 248 => "ø", /*ш*/ 249 => "ù", /*щ*/ 250 => "ú", /*ъ*/
		251 => "û", /*ы*/ 252 => "ü", /*ь*/ 253 => "ý", /*э*/ 254 => "þ", /*ю*/ 255 => "ÿ" ); /*я*/
	
	static function GotoPage($p_alertstr = '', $p_gotopage = '') {
		$p_alertstr = trim($p_alertstr);
		if($p_alertstr!="")
			echo "<script type='text/javascript'>alert(\"$p_alertstr\");</script>";
		if($p_gotopage==""){
			if($p_alertstr!="") echo "<script type='text/javascript'>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>";
			else echo "<script type='text/javascript'>document.location.href='".$_SERVER['HTTP_REFERER']."';</script>"; //header("location: ".$_SERVER['HTTP_REFERER']);
		} else if($p_gotopage=="back"){
			echo "<script type='text/javascript'>history.go(-1);</script>";
		} else {
			if($p_alertstr!="") echo "<script type='text/javascript'>document.location.href='$p_gotopage';</script>";
			else echo "<script type='text/javascript'>document.location.href='$p_gotopage';</script>"; //header("location: $p_gotopage");
		}
        exit;
	}
	static function getErrorPage($type = 1){
		if($type==1) $lnk="modulenotfound.php";
		else $lnk="usernotfound.php";
		return DRF."/error/".$lnk;
	}
	static function GetStrBr($p_str, $p_len){
		if(mb_strlen($p_str, "utf-8")<=$p_len) return $p_str;
		$v_pos=mb_strpos($p_str, " ", $p_len, "utf-8");
		if($v_pos==0) $res=$p_str;
		else $res=mb_substr($p_str, 0, $v_pos, "utf-8");
		return trim($res)." ...";
	}
	static function GetStr($p_str, $p_len){
		$p_str=strip_tags($p_str);
		if(mb_strlen($p_str, "utf-8")<=$p_len) return $p_str;
		$v_pos=mb_strpos($p_str, " ", $p_len, "utf-8");
		if($v_pos==0) $res=$p_str;
		else $res=mb_substr($p_str, 0, $v_pos, "utf-8");
		return trim($res);
	}
	static function getFileExt($filename){
		return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
	}
	static function getFileName($filename){
		return strtolower(pathinfo($filename, PATHINFO_FILENAME));
	}
	static function UniConvert($txt, $type=1, $ishtmlentity=1){
		$txt=trim($txt);
		switch($type){
			case 0: // unicode iig windows cryllic ruu hurvuulne
				$txt = str_replace(Util::$letters_unicode,Util::$letters_nonunicode,trim($txt)); 
				break;
			case 1: // windows cryllic iig unicode ruu hurvuulne
				$txt = str_replace(Util::$letters_nonunicode,Util::$letters_unicode,trim($txt));
				break;
		}
		if($ishtmlentity==1) $txt=htmlentities($txt,ENT_QUOTES,Util::$charset);
		if(get_magic_quotes_gpc()) $txt=stripslashes($txt);
		return $txt; 
	}
	
	static function cropImage($nw, $nh, $source, $dest){
        require_once dirname(dirname(__FILE__))."/thumbnail.inc.php";
        $thumb = new \Thumbnail($source);
        if(empty($nw) || empty($nh)){
            if(empty($nw)) $thumb->resize("",$nh);
            elseif(empty($nh)) $thumb->resize($nw,"");
        } else {
            $size = getimagesize($source);
            $imgw = $size[0];
            $imgh = $size[1];

            if($imgw/$nw<$imgh/$nh && $imgw>=$nw) $thumb->resize($nw,"");
            elseif($imgw/$nw>$imgh/$nh && $imgh>=$nh) $thumb->resize("",$nh);
            elseif($imgw/$nw==$imgh/$nh) $thumb->resize($nw,$nh);
            elseif($nw/$imgw<$nh/$imgh) $thumb->resize('',$nh);
            else $thumb->resize($nw,'');

            if($nw==$nh) $thumb->cropFromCenter($nw);
            else $thumb->crop(0,0,$nw,$nh);
        }
        $thumb->save($dest,90);
	}
	static function asuCropImageCoord($nx,$ny,$nw,$nh,$source,$dest){
            require_once dirname(dirname(__FILE__))."/thumbnail.inc.php";
            $thumb = new Thumbnail($source);
            $thumb->crop($nx,$ny,$nw,$nh);
            $thumb->save($dest,90);
	}
	static function getDaysDiff($_datestart, $_dateend){
	    $date1 = new \DateTime($_datestart);
	    $date2 = new \DateTime($_dateend);
	    if($date1>$date2){
	        return ['year'=>0,'month'=>0, 'day'=>0];
	    }
	    $_con=\Database::instance();
	    $qry="
            SELECT 
            TIMESTAMPDIFF(YEAR,'".$_datestart."','".$_dateend."') AS WorkYear, 
            (TIMESTAMPDIFF(MONTH,'".$_datestart."','".$_dateend."') - TIMESTAMPDIFF(YEAR,'".$_datestart."','".$_dateend."')*12) AS WorkMonth,
            TIMESTAMPDIFF(DAY, '".$_datestart."'+ INTERVAL TIMESTAMPDIFF(YEAR, '".$_datestart."','".$_dateend."') YEAR + INTERVAL (TIMESTAMPDIFF(MONTH,'".$_datestart."','".$_dateend."') - TIMESTAMPDIFF(YEAR,'".$_datestart."','".$_dateend."')*12) MONTH,'".$_dateend."'
            ) AS WorkDays
            ;
        ";
	    $result = $_con->select($qry);
	    $_list = \Database::getList($result, []);
	    if(!isset($_list[0])){
	        return ['year'=>0,'month'=>0, 'day'=>0];
	    }
	    return ['year'=>(isset($_list[0]['WorkYear'])?$_list[0]['WorkYear']:0),
	        'month'=>(isset($_list[0]['WorkMonth'])?$_list[0]['WorkMonth']:0), 
	        'day'=>(isset($_list[0]['WorkDays'])?$_list[0]['WorkDays']:0)];
// 	    $date1 = new \DateTime($_datestart);
// 	    $date2 = new \DateTime($_dateend);
// 	    if($date1>$date2){
// 	        return ['year'=>0,'month'=>0, 'day'=>0];
// 	    }
// 	    $interval = $date1->diff($date2);
// 	    return ['year'=>$interval->y,'month'=>$interval->m, 'day'=>$interval->d];	    
	}
	static function formatDays($year=0, $month=0, $day=0){
	    return ($year>0?$year.'ж ':"").($month>0?$month.'с ':"").($day>0?$day.'ө ':"");
	}
	static function formatDaysMonth($year=0, $month=0, $day=0){
	    $_allm=(is_numeric($year)?$year*12:0)+$month;
	    return ($_allm>0?($_allm.'с '):"").($day>0?$day.'ө ':"");
	}
	static function formatDate($date, $format = 'Y оны m сарын d'){
		return gmdate($format, $date);
	}
	static function checkDate($date, $format = 'Y-m-d H:i:s'){
	    $version = explode('.', phpversion());
	    $arr  = explode('-', $date);
	    if(count($arr)==3){
	        return checkdate($arr[0], $arr[1], $arr[2]);
	    }
	    return false;
	}
	static function isWeekend($date) {
	    return (date('N', strtotime($date)) >= 6);
	}
    static function showNum($num=0,$class="text_gray"){
        if($num==0)
            $str="<span class='text_gray'>-</span>";
        else $str=$num;
        return $str;
    }

    static function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }
    static function letterD_T($letter){
        $n_g = "д";
        $letter = mb_substr($letter, mb_strlen($letter, 'UTF-8') - 1, 1, "utf-8");
        if ($letter == 'г' || $letter == 'с' || $letter == 'б' || $letter == 'в' || $letter == 'р'){
            $n_g = 'т';
        }
        return $n_g;
    }
    static function getInput($val, $type=self::INPUT_TEXT){
        if($type==self::INPUT_TEXT){
            return $val!=""?"'{$val}'":"NULL";
        }else{
            return $val!=""?"{$val}":"NULL";
        }
    }
    static function passwordGenerate( $length = 8, $chars = self::CHARS ) {
        return substr( str_shuffle( $chars ), 0, $length );
    }
    static function getDatesFromRange($start, $end, $format = 'Y-m-d') {
        $array = array();
        $interval = new \DateInterval('P1D');
        
        $realEnd = new \DateTime($end);
        $realEnd->add($interval);
        
        $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);
        
        foreach($period as $date) {
            $array[] = $date->format($format);
        }
        
        return $array;
    }
    static function number_of_working_days($from, $to) {
        $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
        $holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays
        
        $from = new DateTime($from);
        $to = new DateTime($to);
        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);
        
        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) continue;
            if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
            if (in_array($period->format('*-m-d'), $holidayDays)) continue;
            $days++;
        }
        return $days;
    }
}
