<?php

class Service_Admin_Data_UserModel {

    private $objDaoUser;

    function __construct(){
        $this -> objDaoUser = new Dao_Db_UserModel();
    }

    public  function getUserListCount(){
        $conds = array(
            'id >=1',
        );
        return $this -> objDaoUser -> selectCount($conds);
    }

    public function getUserList($page_num, $page_size){
        $fields = array(
            'id',
            'nick',
            'user_name',
            'email',
            'phone',
            'valid',
            'create_time',
        );

        $conds = array(
            'id >=1',
        );
        $appends = sprintf("order by create_time desc limit %s,%s", ($page_num - 1) * $page_size, $page_size);
        return $this -> objDaoUser -> select($fields, $conds, $appends);
    }


} 