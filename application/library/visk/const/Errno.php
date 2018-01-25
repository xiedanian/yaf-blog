<?php

class visk_Const_Errno {
    const ERR = 1; //系统错误
    const NO_LOGIN = 2001;  //未登陆
    const PARAMS_ERR = 2002;  //参数错误
    const NO_AUTH = 2003;  //没有权限
    const ERR_INVALID_UNAME_OR_PASSWD = 2004; //账号或密码错误
    const ERR_USER_NOT_EXIST = 2005; //账号不存在
    const USER_ACCOUNT_NOT_EXIST = 2006; //用户账户不存在
    const SET_USER_SESSION_FAILD = 2007; //设置用户session失败
    const ERR_INVALID_USS = 2008; //无效USS
    const ARTICLE_ADD_FAIL = 2009; //文章发布失败
    const ARTICLE_UPDATE_FAIL = 2010; //文章更新失败
    const ARTICLE_DELETE_FAIL = 2011; //文章删除失败
    const INVALID_LINK = 2012; //无效链接
}