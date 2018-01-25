<?php

class Service_Admin_Page_Admin_MenuListModel{

    public function execute($params){
        $conds = [];
        $dsMenu = new Service_Admin_Data_MenuModel();
        $totalRows = $dsMenu->countAll($conds);
        $menus = $dsMenu->listAll($conds, $params['page'], $totalRows, "weight DESC");
        $objTree = new visk_Tree();
        $objTree->setIdField('menu_id');
        $tree = $objTree->buildPlatTree($menus, 0);
        return $tree;
    }
}