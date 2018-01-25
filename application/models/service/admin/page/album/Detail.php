<?php


class Service_Admin_Page_Album_DetailModel {

    public function doGet($inputData){

        $result = array();
        $objDSAlubm = new Service_Admin_Data_AlbumModel();
        return $objDSAlubm -> getAlbumDetail($inputData['album_id']);
        //throw new Ald_Exception_AppWarning(Visk_Const_Errno::NO_AUTH);
    }

}
