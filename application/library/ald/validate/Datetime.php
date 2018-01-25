<?php

class Ald_Validate_Datetime extends Ald_Validate_Abstract{

    /**
     * check is date
     * @params
     * @return 
     */
    public function _init(){
        if (strtotime($this -> data) === false) {
            return false;
        }
        return true;
    }

    /**
     * 检查日期格式
     * @param $param eg:Y-m-d
     * @return bool
     */
    public function _format($param){
        if(empty($param)){
            trigger_error('date format param should not empty:' . __METHOD__, E_USER_WARNING);
            return true;
        }
        if($this -> data != date($param, strtotime($this -> data))){
            return false;
        }
        return true;
    }

    /**
     * 转成时间戳
     * @return bool
     */
    public function _strtotime(){
        $this -> res = strtotime($this -> data);
        if($this -> res == false){
            return false;
        }
        return true;
    }
}

