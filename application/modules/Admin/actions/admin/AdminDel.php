<?php

class AdminDelAction extends Ald_Action_Login{

    protected $_validator = [
        ['admin_id', 'admin_id', 'int|required|notempty'],
    ];

    public function doExecute($params){

        $objPs = new Service_Admin_Page_Admin_AdminDelModel();
        return $objPs->execute($params);

    }
}
