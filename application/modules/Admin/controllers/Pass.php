<?php

class PassController extends Yaf_Controller_Abstract {

    public $actions = array(
        'login' => 'modules/Admin/actions/pass/Login.php',
        'logout' => 'modules/Admin/actions/pass/Logout.php',
        'checklogin' => 'modules/Admin/actions/pass/CheckLogin.php',
    );

}
