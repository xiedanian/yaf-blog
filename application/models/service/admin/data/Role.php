<?php

class Service_Admin_Data_RoleModel {

    public function add($data){
        $currTime = Ald_Lib_Time::getCurrTime();
        $data['create_time'] = $currTime;
        $data['update_time'] = $currTime;
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->insert($data);
    }

    public function listAll($conds = [], $page = 1, $pageSize = 20, $order = 'role_id DESC'){
        $daoRole = new Dao_Db_AdminRoleModel();
        $start = ($page - 1) * $pageSize;
        $append = "ORDER BY {$order} LIMIT {$start},{$pageSize}";
        return $daoRole->select(['*'], $conds, $append);
    }

    public function countAll($conds){
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->selectCount($conds);
    }

    public function listByRoleIds($roleIds){
        $conds = ["role_id IN('" . implode("', '", $roleIds) . "')"];
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->select(['*'], $conds);
    }

    public function findByRoleId($roleId){
        $fields = ['*'];
        $conds = [
            'role_id' => $roleId,
        ];
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->selectOne($fields, $conds);
    }

    public function updateByRoleId($data, $roleId){
        $conds = [
            'role_id' => $roleId,
        ];
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->update($data, $conds);
    }

    public function deleteByRoleId($roleId){
        $conds = [
            'role_id' => $roleId,
        ];
        $daoRole = new Dao_Db_AdminRoleModel();
        return $daoRole->delete($conds);
    }

    public function findByRoleIdAndMenuId($roleId, $menuId){
        $conds = [
            'role_id' => $roleId,
            'menu_id' => $menuId,
        ];
        $daoRelRoleMenu = new Dao_Db_AdminRoleMenuModel();
        return $daoRelRoleMenu->selectOne(['*'], $conds);
    }
}
