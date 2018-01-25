<?php

class Service_Admin_Page_Admin_RoleAddModel{

    public function executeGet($params){
        return true;
    }

    public function executePost($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        $data = [
            'role_name' => $params['role_name'],
            'remark' => $params['remark'],
        ];
        return $dsRole->add($data);
    }
}