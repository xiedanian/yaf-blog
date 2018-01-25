<?php

class RoleEditAction extends Ald_Action_Login{

    protected $_validatorGet = [
        ['role_id', 'role_id', 'int|required|notempty'],
    ];

    protected $_validatorPost = [
        ['role_id', 'role_id', 'int|required|notempty'],
        ['role_name', 'role_name', 'str|required|notempty'],
        ['remark', 'remark', 'str'],
    ];

    protected $_tpl = 'admin/role/edit.html';

    public function doGet($params){
        $objPs = new Service_Admin_Page_Admin_RoleEditModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_RoleEditModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/rolelist');
    }
}
