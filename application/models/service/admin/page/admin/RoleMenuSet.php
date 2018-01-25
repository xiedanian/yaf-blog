<?php

class Service_Admin_Page_Admin_RoleMenuSetModel{

    public function executeGet1($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        $menus = $dsMenu->listAll([], 1, 9999);
        if(empty($menus) || !is_array($menus)){
            return [];
        }
        $roleMenuIds = $dsMenu->listMenuIdsByRoleId($params['role_id']);
        $tree = [];
        $map = [];
        foreach($menus as $k=>$v){
            $menuId = $v['menu_id'];
            $v['is_checked'] = in_array($menuId, $roleMenuIds) ? 1 : 0;
            if(0 == $v['pid']){
                $tree[$menuId] = $v;
            }else{
                $map[$menuId] = $v;
            }
        }
        foreach($map as $k=>$v){
            $menuId = $v['menu_id'];
            $pid = $v['pid'];
            $tree[$pid]['sub_list'][] = $v;
        }
        return [
            'role_id' => $params['role_id'],
            'list' => $tree,
        ];
    }
    
    public function executeGet($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        $menus = $dsMenu->listAll([], 1, 9999, "weight DESC, menu_id ASC");
        $objTree = new visk_Tree();
        $objTree->setIdField('menu_id');
        $tree = $objTree->buildPlatTree($menus, 0);
        $roleMenuIds = $dsMenu->listMenuIdsByRoleId($params['role_id']);
        if(is_array($tree)){
            foreach($tree as $k=>$v){
                $tree[$k]['is_checked'] = in_array($v['menu_id'], $roleMenuIds) ? 1 : 0;
            }
        }
        return [
            'role_id' => $params['role_id'],
            'list' => $tree,
        ];
    }
    
    public function executePost($params){
        $dsRole = new Service_Admin_Data_RoleModel();
        $role = $dsRole->findByRoleId($params['role_id']);
        if(empty($role)){
            throw new Ald_Exception_AppWarning(Admin_Const_Errno::ERROR, '角色不存在');
        }
        $dsMenu = new Service_Admin_Data_MenuModel();
        return $dsMenu->saveRoleMenus($params['role_id'], $params['menu_ids']);
    }
}