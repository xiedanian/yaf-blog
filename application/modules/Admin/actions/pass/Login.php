<?php

class LoginAction extends Visk_Action{

    protected $_validatorPost = array(
        array('email', 'username', 'str|required'),
        array('password', 'password', 'str|required'),
    );

    public function doGet($inputData){

        $this->_tpl = 'admin/login/login.html';
        return true;
    }


    public function doPost($inputData){
        $obj = new Service_Admin_Page_Pass_LoginModel();
        return $obj -> login($inputData);
    }

}