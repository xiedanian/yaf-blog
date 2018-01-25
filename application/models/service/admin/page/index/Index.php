<?php

class Service_Admin_Page_Index_IndexModel {

    public function doGet(){
        $admin = Visk_Auth::getAdminInfo();
        return [
            'admin' => $admin,
        ];
    }

}
