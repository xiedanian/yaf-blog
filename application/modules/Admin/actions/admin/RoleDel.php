<?php

class RoleDelAction extends Ald_Action_Login{

    protected $_validator = [
        ['role_id', 'role_id', 'int|required|notempty'],
    ];

    public function doExecute($params){

        $objPs = new Service_Admin_Page_Admin_RoleDelModel();
        return $objPs->execute($params);
    }

}
