<?php

class DelAction extends Ald_Action_Login{

    protected $_validatorGet = array(
        array('id', 'id', 'str|required'),
    );

    public function doGet($inputData){
        $obj = new Service_Admin_Page_Article_DelModel();
        return $obj -> doGet($inputData);
    }


}