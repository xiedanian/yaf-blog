<?php

class UpdateAction extends Ald_Action_Login{


    protected $_validatorGet = array(
        array('id', 'id', 'str|required'),
    );

    protected $_validatorPost = array(
        array('id', 'id', 'str|required'),
        array('category_id', 'category_id', 'str|required'),
        array('title', 'title', 'str|required'),
        array('description', 'desc', 'str|required'),
        array('content', 'content', 'str|required'),
    );


    public function doGet($inputData){
        $this ->_tpl = 'admin/article/update.html';
        $obj = new Service_Admin_Page_Article_DetailModel();
        return $obj -> doGet($inputData);
    }


    public function doPost($inputData){

        $obj = new Service_Admin_Page_Article_UpdateModel();
        return $obj -> doPost($inputData);
    }


}