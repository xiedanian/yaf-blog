<?php

class Ald_Log{

    const LEVEL_FATAL = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_NOTICE = 4;
    const LEVEL_DEBUG = 8;

    private static $logStore = array();
    private static $LEVELS = array(
        self::LEVEL_FATAL => 'fatal',
        self::LEVEL_WARNING => 'warning',
        self::LEVEL_NOTICE => 'notice',
        self::LEVEL_DEBUG => 'debug',
    );

    public static function write($logLevel, $logStr, $logStore = false, $logType = ''){
        $logLevel = self::$LEVELS[$logLevel];
        $logDir = '';
        if(defined('LOG_DIR')){
            $logDir = LOG_DIR;
        }else{
            $logDir = __DIR__ . '/../../../../../../logs';
        }
        $appName = 'undefined';
        if(defined('APP_NAME')){
            $appName = APP_NAME;
        }
        $logDir .= '/' . $appName;
        if(!empty($logType)){
            $logDir .= '/' . $logType;
        }
        if(!is_dir($logDir)){
            mkdir($logDir, 0777, true);
        }
        $currTime = time();
        $logId = self::genLogId();
        $logFile = $logDir . '/' . $logLevel . '.' . date('YmdH', $currTime);
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $finalLogStr = '[' . date('Y-m-d H:i:s', $currTime) . '] logid[' . $logId . '] method[' . $method . '] uri[' . $uri . ']';
        if(!empty(self::$logStore) && $logStore){
            $_info = array();
            foreach(self::$logStore as $k=>$v){
                if(!is_scalar($v)){
                    $v = json_encode($v);
                }
                $_info[] = $k . '[' . $v . ']';
            }
            $finalLogStr .= implode(' ', $_info);
        }
        $finalLogStr .= ' ' . $logStr;
        file_put_contents($logFile, $finalLogStr . PHP_EOL, FILE_APPEND);
    }

    public static function addNotice($name, $value){
        self::$logStore[$name] = $value;
    }

    public static function notice($logStr, $logStore = false){
        self::write(self::LEVEL_NOTICE, $logStr, $logStore);
    }

    public static function warning($logStr){
        self::write(self::LEVEL_WARNING, $logStr);
    }

    public static function fatal($logStr){
        self::write(self::LEVEL_FATAL, $logStr);
    }

    public static function debug($logStr){
        self::write(self::LEVEL_DEBUG, $logStr);
    }

    public static function genLogId(){
        if(!defined('LOG_ID')){
            $logId = '';
            if(isset($_SERVER['X-ALD-LOGID'])){
                $logId = $_SERVER['X-ALD-LOGID'];
            }
            if(empty($logId)){
                $logId = Ald_Lib_Uuid::gen();
            }
            define('LOG_ID', $logId);
        }
        return LOG_ID;
    }
}
