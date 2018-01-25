<?php

class Service_Admin_Page_Article_UpdateModel {

    public function doPost($inputData){

        $objDSArticle = new Service_Admin_Data_ArticleModel();
        $ret = $objDSArticle -> updateArticle($inputData, Ald_Lib_Pass::getUserInfo()->getUserId());
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ARTICLE_UPDATE_FAIL);
        }
        return true;
    }
}
