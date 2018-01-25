<?php
/**
 * @name CommonController
 * @author xiejinxiang
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class CommonController extends Yaf_Controller_Abstract {


    public $actions = array(
        //阿里云Oss
        'autograph' => 'modules/Admin/actions/common/Autograph.php', //阿里云上传签名
        'upload' => 'modules/Admin/actions/common/Upload.php', //文件上传
        'uploadmanager' => 'modules/Admin/actions/common/UploadManager.php', //文件上传
    );

}
