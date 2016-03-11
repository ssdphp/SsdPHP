<?php
/* php version >= 5.3.5 */
define("start",microtime(true));
if(!class_exists('\SsdPHP\SsdPHP',false))
{
    require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."SsdPHP".DIRECTORY_SEPARATOR."SsdPHP.php");
}
echo SsdPHP\SsdPHP::bootstrap(function (){
    date_default_timezone_set('PRC');
    SsdPHP\Pulgins\Common\RegShutdownEvent::register();
    #SsdPHP\SsdPHP::setDebug(false);
    SsdPHP\Pulgins\Common\Error::$LOG     =SsdPHP\SsdPHP::isDebug();
    SsdPHP\Pulgins\Common\Error::$CONSOLE =SsdPHP\SsdPHP::isDebug();
    SsdPHP\Core\Config::load(SsdPHP\SsdPHP::getRootPath().DIRECTORY_SEPARATOR.'config');
    SsdPHP\Pulgins\Common\Route::set(SsdPHP\Core\Config::getField('ROUTE','home'));
})->run() === false ? "404 error!" : '' ;

define("end",microtime(true));
echo "<br>".(end-start);