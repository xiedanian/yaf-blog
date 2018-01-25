<?php

class Visk_Pass {

    /**
     * 密码加密-简单模式
     * @param $passwd
     * @return string
     */
    public static function strEncode($str){
        return md5($str);
    }

    /**
     * 密码加密-安全模式
     * @param $passwd
     * @return string
     */
    public static function encrypt_passwd($passwd){
        return md5(strrev(md5($passwd)));
    }

    /**
     * token加密
     * @param $user_name
     * @param $remote_addr
     * @return string
     */
    public static function encrypt_token($data){
        return strrev(base64_encode(json_encode($data)));
    }

    /**
     * token解密
     * @param $token
     * @return string
     */
    public static function decrypt_token($token){
        return json_decode(base64_decode(strrev($token)), true);
    }

} 