<?php

class LogoutAction extends Ald_Action_Login{

    public function doExecute(){
        visk_Session::delete();
        $this->success('/admin/pass/login');
    }

}