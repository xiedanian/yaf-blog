<?php

class Service_Admin_Page_User_IndexModel {

    public function execute($inputData){

        $result = array();
        $objDSUser = new Service_Admin_Data_UserModel();
        $userCount = $objDSUser -> getUserListCount();
        $userList = $objDSUser -> getUserList($inputData['page_num'], $inputData['page_size']);

        $result = array(
            "userList" => $userList,
            'user_active' => 'user_list',
            'page_total' => ceil($userCount / $inputData['page_size']),
            'page_num' => $inputData['page_num'],
            'page_size' => $inputData['page_size'],
        );
        return $result;
    }

}
