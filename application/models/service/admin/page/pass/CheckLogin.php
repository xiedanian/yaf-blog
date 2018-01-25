<?php

class Service_Admin_Page_Pass_CheckLoginModel {

    public function checklogin($inputData){
        $cookie_info = visk_Pass::decrypt_token($inputData['ALDUSS']);
        $objDSSession = new Service_Admin_Data_SessionModel($cookie_info['platform']);
        $check = $objDSSession -> checkSession($cookie_info['user_id'], $inputData['ALDUSS']);
        if($check == false){
            throw new Ald_Exception_AppNotice(visk_Const_Errno::ERR_INVALID_USS);
        }
        //重新设置时间
        $objDSSession -> setSession($cookie_info['user_id'], $inputData['ALDUSS']);
        $result['user_id'] = $cookie_info['user_id'];
        $result['type'] = $cookie_info['type'];
        $result['platform'] = $cookie_info['platform'];
        if($cookie_info['type'] == visk_Const_Define::USER_ADMIN){
            $data = $this -> getUserLoginInfo($cookie_info['user_id']);
        }else{
            $data = $this -> getCompanyLoginInfo($cookie_info['user_id']);
        }
        $result = array_merge($result, $data);
        return $result;
    }

    /**
     * 个人账号
     * @param $user_id
     * @return mixed
     */
    public function getUserLoginInfo($user_id){
        //账号信息
        $objDSUser = new Service_Admin_Data_UserPassModel();
        $userInfo = $objDSUser -> getInfoByUid($user_id);
        $result['phone'] = $userInfo['phone'];
        $result['nick'] = $userInfo['nick'];
        $result['user_name'] = $userInfo['user_name'];
        $result['valid'] = $userInfo['valid'];
        return $result;
    }

    /**
     * 企业账号
     * @param $user_id
     * @return mixed
     */
    public function getCompanyLoginInfo($user_id){
        //账号信息
        $objDSUser = new Service_Admin_Data_UserPassModel();
        $userInfo = $objDSUser -> getInfoByUid($user_id);
        $result['phone'] = $userInfo['phone'];
        $result['nick'] = $userInfo['nick'];
        $result['user_name'] = $userInfo['user_name'];
        $result['valid'] = $userInfo['valid'];
        return $result;
    }

} 