<?php

class Service_Page_Index_IndexModel {

    public function execute($inputData){

        $result = array();
        $objDSArticle = new Service_Data_ArticleModel();
        $article_total = $objDSArticle->getArticleListCount();
        $article_list = $objDSArticle -> getArticleList($inputData['page_num'], $inputData['page_size']);
        if(!empty($article_list)){
            foreach ($article_list as $key => $value){
                $article_list[$key]['cover'] = visk_Const_Define::COVER;
                $pattern_src = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
                if(preg_match($pattern_src, $value['content'], $result)){
                    $article_list[$key]['cover'] = $result[1];
                }
                $article_list[$key]['content'] = mb_substr(strip_tags($value['content']), 0, 200);
            }
        }
        $result = array(
            'active' => '',
            'article_list' => $article_list,
            'page_total' => ceil($article_total / $inputData['page_size']),
            'page_num' => $inputData['page_num'],
            'page_size' => $inputData['page_size'],
        );
        return $result;
    }
}
