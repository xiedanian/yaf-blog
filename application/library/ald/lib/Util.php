<?php

class Ald_Lib_Util{

    public static function fileSizeFormat($size){
        $size = intval($size);
        $oneKb = 1024;
        if($size < $oneKb){
            return $size . 'B';
        }
        $oneMb = $oneKb * 1024;
        if($size < $oneMb){
            return round($size / $oneKb, 2) . 'KB';
        }
        $oneGb = $oneMb * 1024;
        if($size < $oneGb){
            return round($size / $oneMb, 2) . 'MB';
        }
        return round($size / $oneGb, 2) . 'GB';
    }

    public static function moneyFormat($money){
        $money = floatval($money);
        $money = number_format($money, 0, '.', ',');
        return $money;
    }

    public static function phoneMask($phone){
        return substr($phone, 0, 3) . '****' . substr($phone, -4);
    }

    public static function getRemoteFileSize($url){
        return self::getRemoteFileInfo($url, 'Content-Length');
    }

    public static function getRemoteFileType($url){
        $contentType = self::getRemoteFileInfo($url, 'Content-Type');
        $contentType = trim($contentType);
        preg_match('/\w+\/\w+/i', $contentType, $ret);
        if(isset($ret[0])){
            return $ret[0];
        }
        return true;
    }

    public static function getRemoteFileInfo($url, $headerName = null){
        static $resultMap = array();
        $urlHash = md5($url);
        if(!isset($resultMap[$urlHash])){
            $httpDefaultOptions = array(
                'http' => array(
                    'timeout' => 5,
                ),
            );
            stream_context_set_default($httpDefaultOptions);
            $resultMap[$urlHash] = get_headers($url, true);
        }
        $headers = $resultMap[$urlHash];
        if(empty($headerName)){
            return $headers;
        }
        return isset($headers[$headerName]) ? $headers[$headerName] : false;
    }

    public static function getIp(){
        $realip = '';
        $unknown = 'unknown';
        if (isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach($arr as $ip){
                    $ip = trim($ip);
                    if ($ip != 'unknown'){
                        $realip = $ip;
                        break;
                    }
                }
            }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
                $realip = $_SERVER['REMOTE_ADDR'];
            }else{
                $realip = $unknown;
            }
        }else{
            if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
                $realip = getenv("HTTP_CLIENT_IP");
            }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
                $realip = getenv("REMOTE_ADDR");
            }else{
                $realip = $unknown;
            }
        }
        $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
        return $realip;
    }

    public static function getIpLookup($ip = ''){
        if(empty($ip)){
            $ip = self::GetIp();
        }
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){ return false; }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        return $json;
    }
} 