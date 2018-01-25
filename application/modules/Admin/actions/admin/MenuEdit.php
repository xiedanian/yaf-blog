<?php

class MenuEditAction extends Ald_Action_Login{

    protected $_validatorGet = [
        ['menu_id', 'menu_id', 'int|required|notempty'],
    ];

    protected $_validatorPost = [
        ['menu_id', 'menu_id', 'int|required|notempty'],
        ['name', 'name', 'str'],
        ['pid', 'pid', 'int|required|notempty'],
        ['uri', 'uri', 'str|required|notempty'],
        ['weight', 'weight', 'int|default[0]'],
        ['is_menu', 'is_menu', 'int|default[0]'],
        ['remark', 'remark', 'str'],
    ];

    public function doGet($params){
        $this->_tpl = 'admin/menu/edit.html';
        $objPs = new Service_Admin_Page_Admin_MenuEditModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_MenuEditModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/menulist');
    }
}
