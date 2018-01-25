<?php

class DetailAction extends Visk_Action {

    protected $_validator = array(
        array('id', 'id', 'str|required'),
    );

    public function doExecute($inputData){

        $this-> _tpl = 'blog/detail.html';
        $obj = new Service_Page_Article_DetailModel();
        return $obj -> execute($inputData);
    }
}
