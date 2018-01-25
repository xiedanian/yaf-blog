<?php

class PicsAddAction extends Ald_Action_Login{


    protected $_validatorGet = array(
        array('album_id', 'album_id', 'str|required'),
    );

    protected $_validatorPost = array(
        array('album_id', 'album_id', 'str|required'),
        array('pics_str', 'pics_str', 'str|required'),
    );


    public function doGet($inputData){

        $this ->_tpl = 'admin/album/pics_add.html';
        $obj = new Service_Admin_Page_Album_PicsListModel();
        return $obj -> doGet($inputData);
    }


    public function doPost($inputData){

        $obj = new Service_Admin_Page_Album_PicsAddModel();
        return $obj -> doPost($inputData);
    }


}