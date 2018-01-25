<?php

class RoleAddAction extends Ald_Action_Login{

    protected $_validatorPost = [
        ['role_name', 'role_name', 'str|required|notempty'],
        ['remark', 'remark', 'str'],
    ];

    public function doGet($params){
        $this->_tpl = 'admin/role/add.html';
        $objPs = new Service_Admin_Page_Admin_RoleAddModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_RoleAddModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/rolelist');
    }
}
