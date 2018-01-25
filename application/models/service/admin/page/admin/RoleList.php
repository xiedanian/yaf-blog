<?php

class Service_Admin_Page_Admin_RoleListModel{

    public function execute($params){
        $conds = [];
        $dsRole = new Service_Admin_Data_RoleModel();
        $roles = $dsRole->listAll($conds, $params['page'], $params['page_size']);
        $totalRows = $dsRole->countAll($conds);
        return [
            'list' => $roles,
            'total' => $totalRows,
            'page_size' => $params['page_size'],
        ];
    }
}