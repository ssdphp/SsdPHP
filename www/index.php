<?php
if(!class_exists('\SsdPHP\SsdPHP',false))
{
    require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."SsdPHP".DIRECTORY_SEPARATOR."SsdPHP.php");
}
echo SsdPHP\SsdPHP::bootstrap(function ($rootPath=""){


    SsdPHP\Pulgins\Common\Error::$LOG     =SsdPHP\SsdPHP::isDebug();
    SsdPHP\Pulgins\Common\Error::$CONSOLE =SsdPHP\SsdPHP::isDebug();
    SsdPHP\Core\Config::load(SsdPHP\SsdPHP::getRootPath().DIRECTORY_SEPARATOR.'config');
    SsdPHP\Pulgins\Common\Route::set(SsdPHP\Core\Config::getField('ROUTE','home'));

})->run() === false ? "404 error!" : '' ;