<?php

class AdminAddAction extends Ald_Action_Login{

    protected $_validatorPost = [
        ['email', 'email', 'str|required|notempty'],
        ['password', 'password', 'str|required|notempty'],
        ['role_id', 'role_id', 'int|required|notempty'],
        ['is_supper', 'is_supper', 'int|required|notempty|inArr[0,1]'],
    ];

    public function doGet($params){
        $this->_tpl = 'admin/admin/add.html';
        $objPs = new Service_Admin_Page_Admin_AdminAddModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_AdminAddModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/adminlist');
    }





}
