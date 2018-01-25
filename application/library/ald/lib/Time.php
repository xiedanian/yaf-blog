<?php

class Ald_Lib_Time{

    public static function getCurrTime(){
        //static $time = isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time();
        static $time = 0;
        if(empty($time)){
            $time = time();
        }
        return $time;
    }

    public static function between($start, $end, $time = null){
        if($start > $end){
            return false;
        }
        if(empty($time)){
            $time = self::getCurrTime();
        }
        if($time <= $start){
            return false;
        }
        if($time > $end){
            return false;
        }
        return true;
    }
}