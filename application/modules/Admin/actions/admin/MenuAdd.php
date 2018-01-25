<?php

class MenuAddAction extends Ald_Action_Login{

    protected $_validatorPost = [
        ['name', 'name', 'str'],
        ['pid', 'pid', 'int|required|notempty'],
        ['uri', 'uri', 'str|required|notempty'],
        ['is_menu', 'is_menu', 'int|default[0]'],
        ['weight', 'weight', 'int|default[0]'],
        ['remark', 'remark', 'str'],
    ];

    public function doGet($params){
        $this->_tpl = 'admin/menu/add.html';
        $objPs = new Service_Admin_Page_Admin_MenuAddModel();
        return $objPs->executeGet($params);
    }

    public function doPost($params){
        try{
            $objPs = new Service_Admin_Page_Admin_MenuAddModel();
            $objPs->executePost($params);
        }catch(Exception $e){
            $this->error($e->getMessage());
        }
        $this->success('/admin/admin/menulist');
    }
}
