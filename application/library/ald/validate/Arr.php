<?php

class Ald_Validate_Arr extends Ald_Validate_Abstract{

    /**
     * 初始化检查
     */
    public function _init(){
        if(!is_array($this -> data)) return false;
    }

    /**
     * 最小值检查
     */
    public function _in($param){
        $param = explode(',', $param);
        if(!in_array($this -> data, $param)) return false;
        return true;
    }

}
