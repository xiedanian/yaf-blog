<?php

class Service_Admin_Page_Admin_MenuAddModel{

    public function executeGet($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        $menus = $dsMenu->listAll([], 1, 9999, "weight DESC, menu_id ASC");
        $objTree = new visk_Tree();
        $objTree->setIdField('menu_id');
        $tree = $objTree->buildPlatTree($menus);
        return [
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
        return $dsMenu->add($data);
    }
}