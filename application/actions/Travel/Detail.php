<?php

class DetailAction extends Ald_Action{

    protected $_validatorGet = array(
        array('album_id', 'album_id', 'str|required'),
    );

    public function doGet($inputData){
        $this ->_tpl = 'travel/detail.html';
        $obj = new Service_Page_Travel_DetailModel();
        return $obj -> doGet($inputData);
    }

}