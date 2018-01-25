<?php

class AddAction extends Ald_Action_Login{

    protected $_validatorPost = array(
        array('category_id', 'category_id', 'str|required'),
        array('title', 'title', 'str|required'),
        array('description', 'desc', 'str|required'),
        array('content', 'content', 'str|required'),
    );


    public function doGet(){
        $this ->_tpl = 'admin/article/add.html';
        $obj = new Service_Admin_Page_Article_AddModel();
        return $obj -> doGet();
    }


    public function doPost($inputData){

        $obj = new Service_Admin_Page_Article_AddModel();
        return $obj -> doPost($inputData);
    }


}