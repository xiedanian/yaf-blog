<?php
if(isset($_GET['debug']) && 1 == $_GET['debug']){
    ini_set('display_errors', true);
    error_reporting(E_ALL);
}
date_default_timezone_set('Asia/Shanghai');

define('SERVER',$_SERVER);
define('HTTP_HOST',SERVER['HTTP_HOST']);

define('DS', DIRECTORY_SEPARATOR);
define('APP_DIR', dirname(__FILE__));
//define('APP_NAME', basename(__DIR__));
define('APP_NAME', 'visk');
define('ROOT_DIR', dirname(dirname(__DIR__)));
define('CONF_DIR', APP_DIR . DS . 'conf');
define('PUBLIC_DIR', DS . 'public');
define('LOG_DIR', APP_DIR . DS . 'logs');
define('APP_CONF_DIR', CONF_DIR . DS . APP_NAME);
define('IS_DEBUG', 1);

define('PUBLIC_CSS',  PUBLIC_DIR . DS . 'css');
define('PUBLIC_JS',  PUBLIC_DIR . DS . 'js');
define('PLUGIN',  PUBLIC_DIR . DS . 'plugin');
define('PUBLIC_IMG', PUBLIC_DIR . DS . 'img');
define('PUBLIC_IMAGES', PUBLIC_DIR . DS . 'images');

$application = new Yaf_Application( APP_DIR . "/conf/application.ini");
$application->bootstrap()->run();
?>
