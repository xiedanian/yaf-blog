<?php

class visk_Const_Define {

    const USER_ADMIN = 1; //后台用户
    const USER_HOME = 2;  //前台用户

    const INTRO = '';  //描述默认值
    const SORT = '0';  //排序默认值
    const IS_DELETED = '0';  //删除默认值

    const COVER = "/public/images/cover.jpg";

    //阿里云OSS图片处理域名规则
    //const TRAVEL_LIST = "?x-oss-process=image/resize,m_lfit,w_560,limit_0/auto-orient,1/quality,q_90";
    const TRAVEL_LIST =   "?x-oss-process=image/resize,m_fill,w_560,h_400,limit_0/auto-orient,1/quality,q_90";
    const TRAVEL_DETAIL = "?x-oss-process=image/resize,m_lfit,w_1050,limit_0/auto-orient,1/quality,q_90/watermark,image_YmxvZy9sb2dvLnBuZz94LW9zcy1wcm9jZXNzPWltYWdlL3Jlc2l6ZSxwXzEwMC9icmlnaHQsMC9jb250cmFzdCww,t_100,g_se,y_50,x_50";

} 