<?php

class visk_Const_Error {
    public static $MAP = array(
        Visk_Const_Errno::ERR => '系统错误',
        Visk_Const_Errno::NO_LOGIN => '未登陆',
        Visk_Const_Errno::PARAMS_ERR => '参数错误',
        Visk_Const_Errno::NO_AUTH => '没有权限',
        Visk_Const_Errno::ERR_INVALID_UNAME_OR_PASSWD => '帐号或密码错误',
        Visk_Const_Errno::ERR_USER_NOT_EXIST => '帐号不存在',
        Visk_Const_Errno::USER_ACCOUNT_NOT_EXIST => '用户账户不存在',
        Visk_Const_Errno::SET_USER_SESSION_FAILD => '设置用户session失败',
        Visk_Const_Errno::ERR_INVALID_USS => 'USS无效',
        Visk_Const_Errno::ARTICLE_ADD_FAIL => '发布失败',
        Visk_Const_Errno::ARTICLE_UPDATE_FAIL => '更新失败',
        Visk_Const_Errno::ARTICLE_DELETE_FAIL => '删除失败',
        Visk_Const_Errno::INVALID_LINK => '无效链接',
    );

    public static function error($errno){
        if(isset(self::$MAP[$errno])){
            return self::$MAP[$errno];
        }
        return '';
    }
} 