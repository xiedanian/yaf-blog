<?php

class IndexAction extends Visk_Action {

    public function doExecute(){
       $this-> _tpl = 'link/index.html';
       return true;
    }
}
