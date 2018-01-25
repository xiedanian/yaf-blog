<?php

class Service_Admin_Page_Article_DelModel {

    public function doGet($inputData){

        $objDSArticle = new Service_Admin_Data_ArticleModel();

        $ret = $objDSArticle -> delArticle($inputData['id']);

        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ARTICLE_DELETE_FAIL);
        }
        return $ret;
    }

}
