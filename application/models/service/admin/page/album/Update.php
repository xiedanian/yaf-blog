<?php

class Service_Admin_Page_Album_UpdateModel {

    public function doPost($inputData){

        $objDSAlubm = new Service_Admin_Data_AlbumModel();
        $data = array(
            'title' => $inputData['title'],
            'icon' => $inputData['icon'],
            'intro' => $inputData['intro'],
            'update_time' => time(),
        );

        $ret = $objDSAlubm -> updateAlbum($data, $inputData['album_id']);
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ERR);
        }
        return true;
    }

}
