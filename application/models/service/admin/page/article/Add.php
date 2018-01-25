<?php

class Service_Admin_Page_Article_AddModel {

    public function doGet(){
        $result = array();
        $objDSCategory = new Service_Admin_Data_CategoryModel();
        $data = $objDSCategory -> getCatgoryAll();
        if($data === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ERR);
        }
        $result['category_list'] = visk_Util::tree($data);
        return $result;
    }

    public function doPost($inputData){

        $objDSArticle = new Service_Admin_Data_ArticleModel();
        $adminInfo= visk_Session::get('Admin');
        $inputData['uid']  = $adminInfo['admin_id'];
        $ret = $objDSArticle -> Add($inputData);
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ARTICLE_ADD_FAIL);
        }
        return true;
    }

}
