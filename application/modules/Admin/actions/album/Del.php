<?php

class DelAction extends Ald_Action_Login{


    protected $_validator = array(
        array('album_id', 'album_id', 'str|required|notempty'),
    );

    public function doGet($inputData){

        $obj = new Service_Admin_Page_Album_DelModel();
        return $obj -> doGet($inputData);
    }


}