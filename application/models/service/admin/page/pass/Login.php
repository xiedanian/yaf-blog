<?php

class Service_Admin_Page_Pass_LoginModel {

    public function doGet($inputData){

        return true;
    }

    public function login($params){
        $dsAdmin = new Service_Admin_Data_AdminModel();
        $admin = $dsAdmin->findByEmailAndPassword($params['email'], visk_Pass::strEncode($params['password']));
        if(!isset($admin['admin_id']) || empty($admin['admin_id'])){
            throw new Ald_Exception_AppWarning(visk_Const_Errno::ERR_INVALID_UNAME_OR_PASSWD);
        }
        unset($admin['password']);
        visk_Session::save($admin);
        return true;
    }

}