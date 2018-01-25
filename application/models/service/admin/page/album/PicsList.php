<?php

class Service_Admin_Page_Album_PicsListModel {

    public function doGet($inputData){

        $result = array();
        $objDSAlubmPics = new Service_Admin_Data_AlbumPicsModel();
        $albumPicsList = $objDSAlubmPics -> getAlbumPicsList($inputData['album_id']);

        $result = array(
            "albumPicsList" => $albumPicsList,
            'user_active' => 'album_picsadd',
            'album_id' => $inputData['album_id'],
        );
        return $result;
    }

}
