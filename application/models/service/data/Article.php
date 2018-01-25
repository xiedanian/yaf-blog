<?php

class Service_Data_ArticleModel {
    private $objDaoArticle;

    function __construct(){
        $this -> objDaoArticle = new Dao_Db_ArticleModel();
    }

    public  function getArticleListCount(){
        $conds = array(
            'is_deleted' => 0
        );
        return $this -> objDaoArticle -> selectCount($conds);
    }

    public function getArticleList($page_num, $page_size){
        $fields = array(
            'id',
            'category_id',
            'title',
            'description',
            'content',
            'is_deleted',
            'create_time',
            'update_time',
        );

        $conds = array(
            'is_deleted' => 0
        );
        $appends = sprintf("order by create_time desc limit %s,%s", ($page_num - 1) * $page_size, $page_size);
        return $this -> objDaoArticle -> select($fields, $conds, $appends);
    }

    public function getArticleDetail($conds){
        $fields = array(
            'id',
            'category_id',
            'title',
            'description',
            'content',
            'is_deleted',
            'create_time',
            'update_time',
        );

        return $this -> objDaoArticle -> selectOne($fields, $conds);
    }
} 