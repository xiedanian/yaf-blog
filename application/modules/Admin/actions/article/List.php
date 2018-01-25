<?php

class ListAction extends Ald_Action_Login{

    protected $_tpl = 'admin/article/list.html';

    protected $_validator = array(
        array('page_num', 'page', 'str|default[1]'),
        array('page_size', 'page_size', 'str|default[10]'),
    );

    public function doGet($inputData){

        $obj = new Service_Admin_Page_Article_ListModel();
        return $obj -> doGet($inputData);
    }


}