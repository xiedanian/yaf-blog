<?php

class Service_Admin_Data_AdminModel {

    public function add($data){
        $currTime = Ald_Lib_Time::getCurrTime();
        $data['create_time'] = $currTime;
        $data['update_time'] = $currTime;
        $daoAdmin = new Dao_Db_AdminModel();
        return $daoAdmin->insert($data);
    }

    public function listAll($conds, $page = 1, $pageSize = 20, $order = 'admin_id DESC'){

        $daoAdmin = new Dao_Db_AdminModel();
        $start = ($page - 1) * $pageSize;
        $append = "ORDER BY {$order} LIMIT {$start},{$pageSize}";
        return $daoAdmin->select(['*'], $conds, $append);

    }

    public function countAll($conds){
        $daoAdmin = new Dao_Db_AdminModel();
        return $daoAdmin->selectCount($conds);
    }

    public function findByAdminId($adminId){
        $fields = ['*'];
        $conds = [
            'admin_id' => $adminId,
        ];
        $daoAdmin = new Dao_Db_AdminModel();
        return $daoAdmin->selectOne($fields, $conds);
    }

    public function findByEmailAndPassword($email, $password){
        $conds = [
            'email' => $email,
            'password' => $password,
        ];
        $fields = [
            'admin_id',
            'role_id',
            'email',
        ];
        $daoAdmin = new Dao_Db_AdminModel();
        return $daoAdmin->selectOne($fields, $conds);
    }

    public function updateByAdminId($data, $adminId){
        $daoAdmin = new Dao_Db_AdminModel();
        $data['update_time'] = Ald_Lib_Time::getCurrTime();
        $conds = [
            'admin_id' => $adminId,
        ];

        return $daoAdmin->update($data, $conds);
    }

    public function deleteByAdminId($adminId){
        $daoAdmin = new Dao_Db_AdminModel();
        $conds = [
            'admin_id' => $adminId,
        ];
        return $daoAdmin->delete($conds);
    }
}
