<?php

class RoleMenuSetAction extends Ald_Action_Login{

    protected $_validatorGet = [
        ['role_id', 'role_id', 'int|required|notempty'],
    ];

    protected $_validatorPost = [
        ['role_id', 'role_id', 'int|required|notempty'],
        ['menu_ids', 'menu_ids', 'str|required|notempty'],
    ];

    public function doGet($params){
        $this->_tpl = 'admin/role/menuset.html';
        $objPs = new Service_Admin_Page_Admin_RoleMenuSetModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        $params['menu_ids'] = explode(',', $params['menu_ids']);
        $objPs = new Service_Admin_Page_Admin_RoleMenuSetModel();
        return $objPs->executePost($params);
    }
}
