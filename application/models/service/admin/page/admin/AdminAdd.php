<?php

class Service_Admin_Page_Admin_AdminAddModel{

    public function executeGet($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        $roles = $dsRole->listAll([], 1, 9999);
        return [
            'roles' => $roles,
        ];
    }

    public function executePost($params){
        $dsAdmin = new Service_Admin_Data_AdminModel();
        $data = [
            'email' => $params['email'],
            'password' => Visk_Pass::strEncode($params['password']),
            'role_id' => $params['role_id'],
            'is_supper' => $params['is_supper'],
        ];
        return $dsAdmin->add($data);
    }
}