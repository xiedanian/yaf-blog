<?php

class Service_Page_Article_DetailModel {

    public function execute($inputData){

        $result = array();
        $objDSArticle = new Service_Data_ArticleModel();
        $conds = array(
            'id' => $inputData['id'],
        );
        $ret = $objDSArticle -> getArticleDetail($conds);
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::PARAMS_ERR);
        }
        $result['articleInfo'] = $ret;
        return $result;
    }

}
