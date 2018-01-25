<?php

class AdminController extends Yaf_Controller_Abstract {

    public $actions = array(

        'adminlist' => 'modules/Admin/actions/admin/AdminList.php',
        'adminadd' => 'modules/Admin/actions/admin/AdminAdd.php',
        'adminedit' => 'modules/Admin/actions/admin/AdminEdit.php',
        'admindel' => 'modules/Admin/actions/admin/AdminDel.php',

        'rolelist' => 'modules/Admin/actions/admin/RoleList.php',
        'roleadd' => 'modules/Admin/actions/admin/RoleAdd.php',
        'roleedit' => 'modules/Admin/actions/admin/RoleEdit.php',
        'roledel' => 'modules/Admin/actions/admin/RoleDel.php',

        'menulist' => 'modules/Admin/actions/admin/MenuList.php',
        'menuadd' => 'modules/Admin/actions/admin/MenuAdd.php',
        'menuedit' => 'modules/Admin/actions/admin/MenuEdit.php',
        'menudel' => 'modules/Admin/actions/admin/MenuDel.php',

        'rolemenuset' => 'modules/Admin/actions/admin/RoleMenuSet.php',

    );
}
