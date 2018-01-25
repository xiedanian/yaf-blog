<?php

class Ald_Action_Login extends Ald_Action{

    protected function _before(&$inputData){

        session_start();
        parent::_before($inputData);
        $req = $this->getRequest();
        $moduleName = $req->getModuleName();
        $controllerName = $req->getControllerName();
        $actionName = $req->getActionName();
        //$uri = $moduleName . '/' . $controllerName . '/' . $actionName;
        $uri = $controllerName . '/' . $actionName;
        $uri = strtolower($uri);
        $whiteList = [
            'admin/pass/login',
        ];

        $adminId = visk_Auth::getAdminId();
        if(!in_array($uri, $whiteList)){
            if(!is_numeric($adminId) || $adminId <= 0){
                $this->error('请先登录~', '/admin/pass/login');
            }
            if(!visk_Auth::checkAuth($uri)){
                $this->error('您没有此操作权限，请联系管理员开通');
            }
        }
    }

    protected function _after(&$retData){

        if(!empty($adminInfo = visk_Auth::getAdminInfo()) && $this->_outputType == self::OUTPUT_TYPE_HTML){

            $retData['userInfo'] = $adminInfo;
        }
    }

    protected function success($url){
        $this->_redirect($url, $msg = '操作成功！');
    }

    protected function error($msg, $url = null){
        $this->_redirect($url, $msg);
    }

    protected function _redirect($url, $msg){
        if(empty($url)){
            $url = 'javascript:history.back(-1);';
        }
        $html = <<<EOF
        
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>跳转提示</title>
    <style type="text/css">
        *{ padding: 0; margin: 0; }
        body{ background: #fff; font-family: "Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif; color: #333; font-size: 16px; }
        .system-message{ padding: 24px 48px; }
        .system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
        .system-message .jump{ padding-top: 10px; }
        .system-message .jump a{ color: #333; }
        .system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px; }
        .system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display: none; }
    </style>
</head>
<body>
    <div class="system-message">
                    <h1>:(</h1>
            <p class="error">{$msg}</p>
                    <p class="detail"></p>
        <p class="jump">
            页面自动 <a id="href" href="{$url}">跳转</a> 等待时间： <b id="wait">2</b>
        </p>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>
</body>
</html>
EOF;
    echo $html;
    exit(0);
}

}