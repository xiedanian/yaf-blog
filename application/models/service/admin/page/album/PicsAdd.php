<?php

class Service_Admin_Page_Album_PicsAddModel {

    public function doPost($inputData){

        $objDSAlubm = new Service_Admin_Data_AlbumPicsModel();
        $ret = $objDSAlubm -> Add($inputData);
        if($ret === false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ERR);
        }
        return true;
    }

}
