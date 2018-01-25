<?php

class Service_Admin_Page_Admin_AdminDelModel{

    public function execute($params){
        $dsAdmin = new Service_Admin_Data_AdminModel();
        return $dsAdmin->deleteByAdminId($params['admin_id']);
    }
}