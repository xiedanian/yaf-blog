<?php

class Service_Page_Travel_DetailModel {

    public function doGet($inputData){

        $result = array();
        $objDSAlubm = new Service_Data_AlbumModel();
        $AlbumInfo = $objDSAlubm -> getAlbumDetail($inputData['album_id']);
        if($AlbumInfo === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::INVALID_LINK);
        }
        $objDSAlubmPics = new Service_Data_AlbumPicsModel();
        $albumPicsList = $objDSAlubmPics -> getAlbumPicsList($inputData['album_id']);
        if(!empty($albumPicsList)){
            foreach ($albumPicsList as &$piclist){
                $piclist['pic_url'] = $piclist['url'].visk_Const_Define::TRAVEL_DETAIL;
            }
        }
        $result = array(
            "albumInfo" => $AlbumInfo,
            "albumPicsList" => $albumPicsList,
            'user_active' => 'album_picsadd',
            'album_id' => $inputData['album_id'],
        );
        return $result;
    }
}
