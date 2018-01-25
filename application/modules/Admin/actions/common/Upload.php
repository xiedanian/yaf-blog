<?php

class UploadAction extends Visk_Action{

    public function doExecute($params){
        return Ald_Vender_Kindeditor_FileUpload::upload();
    }
}
