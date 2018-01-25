<?php

class Service_Admin_Page_Admin_MenuDelModel{

    public function execute($params){
        $dsMenu = new Service_Admin_Data_MenuModel();
        return $dsMenu->deleteByMenuId($params['menu_id']);
    }
}