<?php

class AdminEditAction extends Ald_Action_Login{

    protected $_validatorGet = [
        ['admin_id', 'admin_id', 'int|required|notempty'],
    ];

    protected $_validatorPost = [
        ['admin_id', 'admin_id', 'int|required|notempty'],
        ['email', 'email', 'str|required|notempty'],
        ['role_id', 'role_id', 'int'],
        ['password', 'password', 'str|required|notempty'],
        ['is_supper', 'is_supper', 'int|default[0]'],
    ];


    public function doGet($params){
        $this->_tpl = 'admin/admin/edit.html';
        $objPs = new Service_Admin_Page_Admin_AdminEditModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_AdminEditModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/adminlist');
    }
}
