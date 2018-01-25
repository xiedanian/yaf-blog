<?php

class Service_Admin_Page_Admin_RoleEditModel{

    public function executeGet($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        $role = $dsRole->findByRoleId($params['role_id']);
        return $role;
    }

    public function executePost($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        $data = [
            'role_name' => $params['role_name'],
            'remark' => $params['remark'],
        ];
        return $dsRole->updateByRoleId($data, $params['role_id']);
    }
}