<?php


class Service_Admin_Page_Album_DelModel {

    public function doGet($inputData){

        $objDSAlubm = new Service_Admin_Data_AlbumModel();
        $ret = $objDSAlubm -> delAlbum($inputData['album_id']);
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ERR);
        }
        return true;
    }

}
