<?php

class Ald_Lib_Pass {
    const LOGIN_COOKIE_EXPIRE = 86400; //cookie
    const DECODE = 'DECODE';
    const ENCODE = 'ENCODE';
    private static $hasInitUser = false;
    private static $userInfo = null;

    /**
     * 获取uss
     * @return null
     */
    public static function getUss(){
        $ald_uss = null;
        if(isset($_COOKIE['ALDUSS']) && !empty($_COOKIE['ALDUSS'])){
            $ald_uss = $_COOKIE['ALDUSS'];
        }elseif(isset($_REQUEST['ALDUSS']) && !empty($_REQUEST['ALDUSS'])){
            $ald_uss = $_REQUEST['ALDUSS'];
            if(!IS_APP){
                self::setAldUss($ald_uss);
            }
        }
        if(is_null($ald_uss)){
            return null;
        }
        return $ald_uss;
    }

    /**
     * 检查uss有效性
     * @param $ald_uss
     * @return bool
     */
    public static function checkUss($ald_uss){
        //检查uss是否有效
        $userModuleConfig = Ald_Config::getSysConfig('modules/blog');
        if(empty($userModuleConfig) || !isset($userModuleConfig['host']) || !isset($userModuleConfig['login_uri'])){
            throw new Ald_Exception_AppWarning(Ald_Const_Errno::ERROR, '登录模块配置异常');
        }
        if(empty($userModuleConfig['host'])){
            $userModuleConfig['host'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        }
        $loginCheckUrl = $userModuleConfig['host'] . $userModuleConfig['login_check_uri'];
        $ret = Ald_Lib_Curl::fetch($loginCheckUrl, Ald_Lib_Curl::METHOD_POST, array('ALDUSS' => $ald_uss));
        $ret = json_decode($ret, true);
        if(!isset($ret['errno']) || $ret['errno'] != 0){
            return false;
        }else{
            return $ret['data'];
        }
    }

    /**
     * 获取用户信息
     * @return Ald_Context_User|null
     */
    public static function getUserInfo(){
        if(self::$hasInitUser == false){
            $objUserInfo = new Ald_Context_User();
            $ald_uss = self::getUss();
            if(!empty($ald_uss)){
                $userInfo = self::checkUss($ald_uss);
                if(!empty($userInfo)){
                    $objUserInfo -> setUserInfo($userInfo);
                }

            }
            self::setUserInfo($objUserInfo);
        }
        return self::$userInfo;
    }

    /**
     * @param Ald_Context_User $obj
     */
    public static function setUserInfo(Ald_Context_User $obj){
        self::$userInfo = $obj;
        self::$hasInitUser = true;
    }

    /**
     * 设置登陆cookie
     * @param $uss
     */
    public static function setAldUss($uss, $expire = self::LOGIN_COOKIE_EXPIRE){
        if($expire == 0){
            setcookie('ALDUSS', $uss, 0, '/');
        }else{
            setcookie('ALDUSS', $uss, time() + $expire, '/');
        }
    }

    /**
     * 删除cookie
     * @param $uss
     */
    public static function delAldUss(){
        setcookie('ALDUSS', '', time() - 3600, '/');
    }

    /**
     * 字符串解密加密
     */
    public static function authcode($string, $operation = self::DECODE, $key = '5azhaopin_wap', $expiry = 0) {

        $ckey_length = 4;	// 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥

        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

}