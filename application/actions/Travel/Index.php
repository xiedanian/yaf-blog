<?php

class IndexAction extends Visk_Action {

    protected $_tpl = 'travel/index.html';

    protected $_validator = array(
        array('page_num', 'page', 'str|default[1]'),
        array('page_size', 'page_size', 'str|default[10]'),
    );

    public function doGet($inputData){

        $obj = new Service_Page_travel_ListModel();
        return $obj -> doGet($inputData);
    }
}
