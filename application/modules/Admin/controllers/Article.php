<?php

class ArticleController extends Yaf_Controller_Abstract {

    public $actions = array(
        'list' => 'modules/Admin/actions/article/List.php',         //文章列表
        'add' => 'modules/Admin/actions/article/Add.php',           //添加文章
        'update' => 'modules/Admin/actions/article/Update.php',     //编辑文章
        'del' => 'modules/Admin/actions/article/Del.php',           //删除文章
    );
}
