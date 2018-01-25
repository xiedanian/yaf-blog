<?php

class Ald_Validate_Float extends Ald_Validate_Abstract{
    /**
     * 初始化检查
     */
    public function _init(){
        if(!is_numeric($this -> data)) return false;
        $this->result = floatval($this -> data);
    }
    /**
     * 最小值检查
     */
    public function _min($param){
        if($this -> data < $param) return false;
        return true;
    }
    /**
     * 最大值检查
     */
    public function _max($param){
        if($this -> data > $param) return false;
        return true;
    }
}
