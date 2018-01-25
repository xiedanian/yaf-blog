<?php

class IndexAction extends Ald_Action_Login{

    protected $_tpl = 'admin/index/index.html';

    public function doGet(){

        $obj = new Service_Admin_Page_Index_IndexModel();
        return $obj -> doGet();
    }

}