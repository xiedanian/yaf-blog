<?php

class AdminListAction extends Ald_Action_Login{

    protected $_validator = [
        ['page', 'page', 'int|default[1]'],
        ['keywords', 'keywords', 'str'],
    ];

    protected $_tpl = 'admin/admin/list.html';

    public function doExecute($params){
        $params['page_size'] = 20;
        $objPs = new Service_Admin_Page_Admin_AdminListModel();
        return $objPs->execute($params);
    }
}
