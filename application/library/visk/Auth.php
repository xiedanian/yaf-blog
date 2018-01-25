<?php
class Visk_Auth{

    public static function getAdminId(){
        $sessionInfo = visk_Session::get();
        return isset($sessionInfo['admin_id']) ? $sessionInfo['admin_id'] : false;
    }

    public static function getAdminInfo(){
        $adminId = self::getAdminId();
        $admin = null;
        if($adminId > 0){
            $dsAdmin = new Service_Admin_Data_AdminModel();
            $admin = $dsAdmin->findByAdminId($adminId);
            if(isset($admin['password'])){
                unset($admin['password']);
            }
        }
        return $admin;
    }

    public static function checkAuth($uri){
        $whiteList = [
            'index/index',
            'pass/logout',
        ];
        if(in_array($uri, $whiteList)){
            return true;
        }
        if(0 === strpos($uri, 'index/')){
            $uri = substr($uri, strlen('index/'));
        }
        $admin = self::getAdminInfo();
        if(1 == $admin['is_supper']){
            return true;
        }
        $dsRole = new Service_Admin_Data_RoleModel();
        $role = $dsRole->findByRoleId($admin['role_id']);
        $dsMenu = new Service_Admin_Data_MenuModel();
        $menu = $dsMenu->findByUri($uri);
        if(empty($menu)){
            return false;
        }
        $rel = $dsRole->findByRoleIdAndMenuId($role['role_id'], $menu['menu_id']);
        if(is_array($rel) && !empty($rel)){
            return true;
        }
        return false;
    }
}