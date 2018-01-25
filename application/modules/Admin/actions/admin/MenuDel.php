<?php

class MenuDelAction extends Ald_Action_Login{

    protected $_validator = [
        ['menu_id', 'menu_id', 'int|required|notempty'],
    ];

    public function doExecute($params){

        $objPs = new Service_Admin_Page_Admin_MenuDelModel();
        return $objPs->execute($params);
    }
}
