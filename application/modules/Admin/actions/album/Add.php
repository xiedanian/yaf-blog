<?php

class AddAction extends Ald_Action_Login{

    protected $_validatorPost = array(
        array('icon', 'icon', 'str|required'),
        array('title', 'title', 'str|required'),
        array('intro', 'intro', 'str|required'),
    );


    public function doGet($inputData){
        $this ->_tpl = 'admin/album/add.html';
        return true;
    }


    public function doPost($inputData){
        $obj = new Service_Admin_Page_Album_AddModel();
        return $obj -> doPost($inputData);
    }


}