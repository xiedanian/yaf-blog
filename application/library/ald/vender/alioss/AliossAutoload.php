<?php

class Ald_Vender_Alioss_AliossAutoload{

    public static function init(){
        spl_autoload_register('Ald_Vender_Alioss_AliossAutoload::classLoader');
    }

    public static function classLoader($class)
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}