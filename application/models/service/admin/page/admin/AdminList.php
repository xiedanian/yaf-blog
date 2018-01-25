<?php

class Service_Admin_Page_Admin_AdminListModel{

    public function execute($params){
        $conds = [];
        if(isset($params['keywords']) && !empty($params['keywords'])){
            $conds[] = "email LIKE '%{$params['keywords']}%'";
        }
        $dsAdmin = new Service_Admin_Data_AdminModel();
        $admins = $dsAdmin->listAll($conds, $params['page'], $params['page_size']);
        $totalRows = $dsAdmin->countAll($conds);
        if(is_array($admins)){
            $roleIds = array_column($admins, 'role_id');
            $dsRole = new Service_Admin_Data_RoleModel();
            $roles = $dsRole->listByRoleIds($roleIds);
            $roleMap = Ald_Lib_Arr::buildMap($roles, 'role_id');
            foreach($admins as $k=>$v){
                $admins[$k]['role'] = $roleMap[$v['role_id']];
            }
        }
        return [
            'list' => $admins,
            'total' => $totalRows,
            'page_size' => $params['page_size'],
        ];
    }
}