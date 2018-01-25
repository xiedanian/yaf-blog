<?php

class Ald_Exception_SysNotice extends Ald_Exception_Abstract {

    protected function errorLog($inner){
        Ald_Log::notice(sprintf('file[%s] line[%s] errno[%s] error[%s]',
            $this->getFile(), $this->getLine(), $this->getCode(), $inner));
    }

    protected function getErrMsg($errno){
        return Ald_Const_Error::error($errno);
    }

}