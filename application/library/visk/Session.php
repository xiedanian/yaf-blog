<?php

class Visk_Session{

    private static $sessionKey = 'Admin';

    public static function save($info, $key = null){
        if(is_null($key)){
            $key = self::$sessionKey;
        }
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION[$key] = $info;
        return true;
    }

    public static function get($key = null){
        if(is_null($key)){
            $key = self::$sessionKey;
        }
        if(empty($_SESSION)){
            return true;
        }
        return $_SESSION[$key];
    }

    public static function delete($key = null){
        if(is_null($key)){
            $key = self::$sessionKey;
        }
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
        return true;
    }
}