<?php

class IndexAction extends Visk_Action {

    protected $_validator = array(
        array('page_num', 'page', 'str|default[1]'),
        array('page_size', 'page_size', 'str|default[10]'),
    );

    public function doExecute($inputData){
        $this-> _tpl = 'mobile/index.html';
        $obj = new Service_Page_Article_ListModel();
        return $obj -> execute($inputData);
    }
}
