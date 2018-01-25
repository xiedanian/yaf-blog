<?php

class Service_Admin_Page_Admin_RoleDelModel{

    public function execute($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        return $dsRole->deleteByRoleId($params['role_id']);
    }
}