<?php

abstract class Ald_Exception_Abstract extends Exception{

    private $data;
    private $inner;

    public function __construct($errno, $error='', $inner='', $data = null){
        $this->inner = $inner;
        if(empty($error)){
            $error = $this -> getErrMsg($errno);
        }
        if(empty($inner)){
            $inner = $error;
        }
        parent::__construct($error, $errno);
        $this -> errorLog($inner);
        $this->data = $data;
    }

    protected abstract function errorLog($inner);

    protected abstract function getErrMsg($errno);
    
    public function getInternalError(){
        return $this->inner;
    }
    public function getExceptionData(){
        return $this->data;
    }

} 