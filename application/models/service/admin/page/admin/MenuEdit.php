<?php

class Service_Admin_Page_Admin_MenuEditModel{

    public function executeGet($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        $menu = $dsMenu->findByMenuId($params['menu_id']);
        $menus = $dsMenu->listAll([], 1, 9999, "weight DESC, menu_id ASC");
        $objTree = new visk_Tree();
        $objTree->setIdField('menu_id');
        $tree = $objTree->buildPlatTree($menus);
        return [
            'menu' => $menu,
            'menus' => $tree,
        ];
    }

    public function executePost($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        $data = [
            'pid' => $params['pid'],
            'name' => $params['name'],
            'weight' => $params['weight'],
            'is_menu' => $params['is_menu'],
            'uri' => $params['uri'],
            'remark' => $params['remark'],
        ];
        return $dsMenu->updateByMenuId($data, $params['menu_id']);
    }
}