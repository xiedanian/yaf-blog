<?php


class Service_Admin_Page_Album_ListModel {

    public function doGet($inputData){

        $result = array();
        $objDSAlubm = new Service_Admin_Data_AlbumModel();
        $albumCount = $objDSAlubm -> getAlbumListCount();
        $albumList = $objDSAlubm -> getAlbumList($inputData['page_num'], $inputData['page_size']);
        //throw new Ald_Exception_AppWarning(Visk_Const_Errno::NO_AUTH);
        $result = array(
            "albumList" => $albumList,
            'user_active' => 'album_list',
            'page_total' => ceil($albumCount / $inputData['page_size']),
            'page_num' => $inputData['page_num'],
            'page_size' => $inputData['page_size'],
        );
        return $result;
    }

}
