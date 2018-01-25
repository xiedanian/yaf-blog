<?php

class Service_Admin_Data_MenuModel{

    public function add($data){
        $currTime = Ald_Lib_Time::getCurrTime();
        $data['create_time'] = $currTime;
        $data['update_time'] = $currTime;
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->insert($data);
    }

    public function listAll($conds = [], $page = 1, $pageSize = 20, $order = 'menu_id DESC'){
        $daoMenu = new Dao_Db_MenuModel();
        $start = ($page - 1) * $pageSize;
        $append = "ORDER BY {$order} LIMIT {$start},{$pageSize}";
        return $daoMenu->select(['*'], $conds, $append);
    }
    

    public function countAll($conds){
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->selectCount($conds);
    }

    public function listByMenuIds($menuIds){
        $conds = ["menu_id IN('" . implode("', '", $menuIds) . "')"];
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->select(['*'], $conds);
    }

    public function findByMenuId($menuId){
        $fields = ['*'];
        $conds = [
            'menu_id' => $menuId,
        ];
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->selectOne($fields, $conds);
    }

    public function findByUri($uri){
        $fields = ['*'];
        $conds = [
            'uri' => $uri,
        ];
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->selectOne($fields, $conds);
    }

    public function updateByMenuId($data, $menuId){
        $conds = [
            'menu_id' => $menuId,
        ];
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->update($data, $conds);
    }

    public function deleteByMenuId($menuId){
        $conds = [
            'menu_id' => $menuId,
        ];
        $daoMenu = new Dao_Db_MenuModel();
        return $daoMenu->delete($conds);
    }

    public function listMenuIdsByRoleId($roleId){
        $conds = [
            'role_id' => $roleId,
        ];
        $dsAdminRoleMenu = new Dao_Db_AdminRoleMenuModel();
        $ret = $dsAdminRoleMenu->select(['menu_id'], $conds);
        if(is_array($ret)){
            return array_column($ret, 'menu_id');
        }
        return [];
    }

    public function saveRoleMenus($roleId, $menuIds){
        $dsAdminRoleMenu = new Dao_Db_AdminRoleMenuModel();
        $conds = [
            'role_id' => $roleId,
        ];
        $dsAdminRoleMenu->delete($conds);
        if(!empty($menuIds)){
            $data = [];
            foreach($menuIds as $menuId){
                $data[] = [
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                ];
            }
            return $dsAdminRoleMenu->multiInsert(array_keys($data[0]), $data);
        }
        return true;
    }
}
