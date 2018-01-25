<?php

class AlbumController extends Yaf_Controller_Abstract {

    public $actions = array(
        'list' => 'modules/Admin/actions/album/List.php',         //相册列表
        'add' => 'modules/Admin/actions/album/Add.php',           //添加相册
        'update' => 'modules/Admin/actions/album/Update.php',     //更新相册
        'del' => 'modules/Admin/actions/album/Del.php',           //删除相册
        'picslist' => 'modules/Admin/actions/album/PicsList.php', //相册详情
        'picsadd' => 'modules/Admin/actions/album/PicsAdd.php',   //添加照片
    );
}
