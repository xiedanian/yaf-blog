<?php

class AutographAction extends Visk_Action{

    public function doExecute($params){
        $obj = new Ald_Vender_AliOss();
        $ret = $obj ->getAutograph();
        return $ret;
    }
}
