<?php

class Ald_Lib_Arr{

    public static function buildMap($arr, $keyField, $valField = null){
        if(!is_array($arr)){
            return array();
        }
        $result = array();
        foreach($arr as $v){
            $key = $v[$keyField];
            if(is_array($valField)){
                $val = array();
                foreach($valField as $vf){
                    $val[$vf] = isset($v[$vf]) ? $v[$vf] : null;
                }
            }else{
                $val = is_null($valField) ? $v : $v[$valField];
            }
            $result[$key] = $val;
        }
        return $result;
    }

    public static function fetch($data, $fields){
        if(!is_array($fields) || !is_array($data)){
            return array();
        }
        $ret = array();
        foreach($fields as $field){
            if(isset($data[$field])){
                $ret[$field] = $data[$field];
            }
        }
        return $ret;
    }

    public static function get($arr, $key, $default = null){
        if(!is_array($arr)){
            return $default;
        }
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
}