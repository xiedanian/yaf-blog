<?php

class Service_Admin_Data_CategoryModel {
    private $objDaoCategory;

    function __construct(){
        $this -> objDaoCategory = new Dao_Db_CategoryModel();
    }

    public function getCategoryByIds($category_ids){

        $result = array();
        $fields = array(
            'id',
            'parent_id',
            'name',
            'is_deleted',
            'create_time',
            'update_time',
        );

        $conds = array(
            'is_deleted' => 0
        );
        $categoryList = $this -> objDaoCategory -> select($fields, $conds);

        if(empty($categoryList)){
            return $result;
        }
        foreach ($categoryList as $category){
            $result[$category['id']] = $category;
        }
        return $result;
    }

    public function getCatgoryAll(){

        $result = array();
        $fields = array(
            'id',
            'parent_id',
            'name',
            'is_deleted',
            'create_time',
            'update_time',
        );

        $conds = array(
            'is_deleted' => 0
        );
        $result = $this -> objDaoCategory -> select($fields, $conds);
        return $result;
    }

} 