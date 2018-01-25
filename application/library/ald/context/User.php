<?php

class Ald_Context_User {
    protected $userInfo = null;

    public function setUserInfo($userInfo){
        $this -> userInfo = $userInfo;
    }

    public function getUserInfo(){
        return $this -> userInfo;
    }

    public function getUserId(){
        return isset($this -> userInfo['user_id']) ? $this -> userInfo['user_id'] : null;
    }

    public function getTelPhone(){
        return isset($this -> userInfo['telphone']) ? $this -> userInfo['telphone'] : null;
    }

    public function getHead(){
        return isset($this -> userInfo['head']) ? $this -> userInfo['head'] : Ald_Const_Define::USER_DEFAULT_HEAD;
    }

    public function getNick(){
        return isset($this -> userInfo['nick']) ? $this -> userInfo['nick'] : null;
    }

    public function getName(){
        return isset($this -> userInfo['name']) ? $this -> userInfo['name'] : null;
    }

    public function getSex(){
        return isset($this -> userInfo['sex']) ? $this -> userInfo['sex'] : null;
    }

} 