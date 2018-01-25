<?php

class Service_Page_travel_ListModel {

    public function doGet($inputData){

        $result = array();
        $objDSAlubm = new Service_Data_AlbumModel();
        $albumCount = $objDSAlubm -> getAlbumListCount();
        $albumList = $objDSAlubm -> getAlbumList($inputData['page_num'], $inputData['page_size']);

        if(!empty($albumList)){
            foreach ($albumList as &$piclist){
                $piclist['icon_thumbnail'] = empty($piclist['icon']) ? visk_Const_Define::COVER : $piclist['icon'].visk_Const_Define::TRAVEL_LIST;
            }
        }
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
