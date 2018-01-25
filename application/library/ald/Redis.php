<?php

class Ald_Redis{

    private static $instances = array();

    private function __construct(){}

    private function __clone(){}

    private static function getConfigByClusterName($clusterName){
        try{
            $configFile = DS . 'redis' . DS . $clusterName . '.ini';
            $config = Ald_Config::getSysConfig($configFile, YAF_ENVIRON);
            return $config;
        }catch(Exception $e){
            Ald_Log::warning(sprintf('%s: %s', __METHOD__, $e->getMessage()));
        }
        return false;
    }

    public static function getInstance($clusterName){
        $config = self::getConfigByClusterName($clusterName);
        if(empty($config)){
            throw new Exception(sprintf('%s: redis config empty, cluster[%s]', __METHOD__, $clusterName));
        }
        $hash = md5(json_encode($config));
        if(!isset(self::$instances[$hash])){
            self::$instances[$hash] = new Ald_Redis_Driver($config);
        }
        return self::$instances[$hash];
    }
}
