<?php

class Ald_Lib_Uuid{

    public static function gen(){
        if(function_exists('uuid_create')){
            return uuid_create();
        }else{
            $chars = md5(uniqid(mt_rand(), true));
            $uuid  = substr($chars, 0, 8) . '-';
            $uuid .= substr($chars, 8, 4) . '-';
            $uuid .= substr($chars, 12, 4) . '-';
            $uuid .= substr($chars, 16, 4) . '-';
            $uuid .= substr($chars, 20, 12);
            return $uuid;
        }
    }
}
