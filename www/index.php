<?php
/* php version >= 5.3.6 */
if(!class_exists('\SsdPHP\SsdPHP'))
    require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."SsdPHP".DIRECTORY_SEPARATOR."SsdPHP.php");
use SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Common\Route,
    SsdPHP\Pulgins\Common\Error,
    SsdPHP\Core\Config,
    SsdPHP\SsdPHP;
if(($r = SsdPHP::Bootstrap(function (){

    date_default_timezone_set('PRC');
    RegShutdownEvent::register();
    #SsdPHP::setDebug(false);
    #SsdPHP::setAppDir("");
    Error::$CONSOLE =SsdPHP::isDebug();
    Config::load(SsdPHP::getRootPath().DIRECTORY_SEPARATOR.'config');
    Route::set(Config::getField('ROUTE','home'));

})->Run()) === false){
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    echo "404 error";
}else{
    echo $r;
}
