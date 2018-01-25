<?php

class Ald_Sington{

    private static $instances = array();

    private function __construct(){}

    private function __clone(){}
    

    public static function getInstance($className){
        if(!isset(self::$instances[$className])){
            self::$instances[$className] = new $className();
        }
        return self::$instances[$className];
    }

    public static function getPs($psName){
        $className = 'Service_Page_' . $psName . 'Model';
        return self::getInstance($className);
    }

    public static function getDs($dsName){
        $className = 'Service_Data_' . $dsName . 'Model';
        return self::getInstance($className);
    }

    public static function getDao($daoName, $namespace = 'db'){
        if(!empty($namespace)){
            $className = 'Dao_' . strtoupper($namespace) . '_' . $daoName . 'Model';
        }else{
            $className = 'Dao_' . $daoName . 'Model';
        }
        return self::getInstance($className);
    }
}