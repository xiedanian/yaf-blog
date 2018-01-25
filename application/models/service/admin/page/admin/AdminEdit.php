<?php

class Service_Admin_Page_Admin_AdminEditModel{

    public function executeGet($params){
        $dsAdmin = new Service_Admin_Data_AdminModel();
        $admin = $dsAdmin->findByAdminId($params['admin_id']);
        $dsRole = new Service_Admin_Data_RoleModel();
        $roles = $dsRole->listAll();
        return [
            'admin' => $admin,
            'roles' => $roles,
        ];
    }

    public function executePost($params){
        $dsAdmin = new Service_Admin_Data_AdminModel();
        $data = [
            'email' => $params['email'],
            'role_id' => $params['role_id'],
            'is_supper' => $params['is_supper'],
        ];
        if(isset($params['password']) && !empty($params['password'])){
            $data['password'] = visk_Pass::strEncode($params['password']);
        }
        return $dsAdmin->updateByAdminId($data, $params['admin_id']);
    }
}