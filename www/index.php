<?php
/* php version >= 5.3.6 */
if(!class_exists('\SsdPHP\SsdPHP'))
    require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."SsdPHP".DIRECTORY_SEPARATOR."SsdPHP.php");
use SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Session\Factory as Session,
    SsdPHP\Pulgins\Common\Route,
    SsdPHP\Pulgins\Common\Error,
    SsdPHP\Core\Config,
    SsdPHP\SsdPHP;
echo
SsdPHP::Bootstrap(function (){
    date_default_timezone_set('PRC');
    RegShutdownEvent::register();
    #SsdPHP::setDebug(false);
    Error::$CONSOLE =SsdPHP::isDebug();
    Config::load(SsdPHP::getRootPath().DIRECTORY_SEPARATOR.'config');
    Route::set(Config::getField('ROUTE','home'));
})->Run() === false ? "404 error!" : ''
;