<?php

class Service_Page_User_IndexModel {

    public function execute(){

        $objDSUser = new Service_Data_UserModel();
        $userList = $objDSUser -> getCollectListByUidPerPage(1,1,10);
        return $userList;
    }
}
