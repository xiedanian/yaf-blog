<?php

class MenuListAction extends Ald_Action_Login{

    protected $_validator = [
        ['page', 'page', 'int|default[1]'],
    ];

    protected $_tpl = 'admin/menu/list.html';

    public function doExecute($params){
        $params['page_size'] = 20;
        $objPs = new Service_Admin_Page_Admin_MenuListModel();
        return $objPs->execute($params);
    }
}
