<?php

class CheckLoginAction extends Ald_Action_Api{

    protected $_validator = array(
        array('ALDUSS', 'ALDUSS', 'str|required'),
    );

    public function doExecute($inputData){
        $obj = new Service_Admin_Page_Pass_CheckLoginModel();
        return $obj -> checklogin($inputData);
    }

}