<?php

class UpdateAction extends Ald_Action_Login{

    protected $_validatorGet = array(
        array('album_id', 'album_id', 'str|required'),
    );

    protected $_validatorPost = array(
        array('album_id', 'album_id', 'str|required'),
        array('icon', 'icon', 'str|required'),
        array('title', 'title', 'str|required'),
        array('intro', 'intro', 'str|required'),
    );

    public function doGet($inputData){

        $this -> _tpl = 'admin/album/update.html';
        $obj = new Service_Admin_Page_Album_DetailModel();
        return $obj -> doGet($inputData);
    }

    public function doPost($inputData){

        $obj = new Service_Admin_Page_Album_UpdateModel();
        return $obj -> doPost($inputData);
    }


}