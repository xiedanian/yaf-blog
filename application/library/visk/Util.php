<?php

class Visk_Util {

    /**
     * 判断是否手机号
     * @param $phone
     * @return int
     */
    public static function isTelphone($phone){
        return preg_match("/^1[34578]{1}\d{9}$/",$phone);
    }

    /**
     * 是否邮箱
     * @param $email
     * @return int
     */
    public static function isEmail($email){
        return preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $email);
    }

    /**
     * 无限级分类
     * @param $data       //数据库里获取的结果集
     * @param int $pid
     * @param int $count  //第几级分类
     * @return array
     */
     public static function tree(&$data,$pid = 0,$count = 1) {
        foreach ($data as $key => $value){
            if($value['parent_id']==$pid){
                $value['Count'] = $count;
                self::$treeList []=$value;
                unset($data[$key]);
                self::tree($data,$value['id'],$count+1);
            }
        }
        return self::$treeList ;
    }

    /**
     * @name 存放无限分类结果如果一页面有多个无限分类可以使用
     * Tool::$treeList = array();
     * @var array
     */
    public static  $treeList = array();//

}