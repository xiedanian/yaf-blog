<?php

class Ald_Action_Result {
    public $errno = 0;
    public $error = '操作成功';
    public $data = null;

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        if(!empty($data)){
            $this->data = $data;
        }
    }

    public function getErrno()
    {
        return $this->errno;
    }

    public function setErrno($errno)
    {
        $this->errno = $errno;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }
}