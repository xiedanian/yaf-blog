<?php

class RoleListAction extends Ald_Action_Login{

    protected $_validator = [
        ['page', 'page', 'int|default[1]'],
    ];

    protected $_tpl = 'admin/role/list.html';

    public function doExecute($params){
        $params['page_size'] = 20;
        $objPs = new Service_Admin_Page_Admin_RoleListModel();
        return $objPs->execute($params);
    }
}
