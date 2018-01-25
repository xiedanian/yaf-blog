<?php

class Ald_Config{
    protected static $config = array();

    public static function getAppConfigByKey($key, $file = 'application', $tag = YAF_ENVIRON){
        $dbConfig = self::getAppConfig($file, $tag);
        return $dbConfig[$key];
    }

    public static function getAppConfig($file = 'application', $tag = YAF_ENVIRON){
        if(false === strrpos($file, '.ini')){
            $file .= '.ini';
        }
        $file_path = APP_CONF_DIR . DS . $file;
        $dbConfig = self::getConfig($file_path, $tag);
        return $dbConfig -> toArray();
    }

    public static function getSysConfig($file, $tag = YAF_ENVIRON){
        if(false === strrpos($file, '.ini')){
            $file .= '.ini';
        }
        $file_path = CONF_DIR . DS . $file;
        $dbConfig = self::getConfig($file_path, $tag);
        return $dbConfig -> toArray();
    }

    private static function getConfig($file_path, $tag){
        if(!isset(self::$config[md5($file_path.$tag)])){
            if($tag){
                $objConfig = new Yaf_Config_Ini($file_path, $tag);
            }else{
                $objConfig = new Yaf_Config_Ini($file_path);
            }
            self::$config[md5($file_path.$tag)] = $objConfig;
        }

        return self::$config[md5($file_path.$tag)];
    }

}