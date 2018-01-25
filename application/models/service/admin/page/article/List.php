<?php

class Service_Admin_Page_Article_ListModel {

    public function doGet($inputData){

        $result = array();
        $objDSArticle = new Service_Admin_Data_ArticleModel();
        $articleCount = $objDSArticle -> getArticleListCount();
        $articleList = $objDSArticle -> getArticleList($inputData['page_num'], $inputData['page_size']);

        foreach ($articleList as $category){
            $category_ids[] = $category['category_id'];
        }
        $objDSCategory = new Service_Admin_Data_CategoryModel();
        $articleInfo = $objDSCategory -> getCategoryByIds($category_ids);

        foreach ($articleList as &$category){
            $category['category_name'] =  $articleInfo[$category['category_id']]['name'];
        }
        $result = array(
            "articleList" => $articleList,
            'user_active' => 'article_list',
            'page_total' => ceil($articleCount / $inputData['page_size']),
            'page_num' => $inputData['page_num'],
            'page_size' => $inputData['page_size'],
        );
        return $result;
    }
}
