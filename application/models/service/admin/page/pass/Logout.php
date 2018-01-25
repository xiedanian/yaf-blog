<?php

class Service_Admin_Page_Pass_LogoutModel {

    public function logout(){
        visk_Session::delete();
        return true;
    }
} 