<?php

class Service_Admin_Page_Article_DetailModel {

    public function doGet($inputData){

        $result = array();
        $objDSArticle = new Service_Admin_Data_ArticleModel();
        $conds = array(
            //'uid' => Ald_Lib_Pass::getUserInfo()->getUserId(),
            'id' => $inputData['id'],
        );
        $result = $objDSArticle -> getArticleDetail($conds);


        $objDSCategory = new Service_Admin_Data_CategoryModel();
        $data = $objDSCategory -> getCatgoryAll();

        if($result === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::PARAMS_ERR);
        }
        $result['category_list'] = visk_Util::tree($data);
        return $result;
    }
}
