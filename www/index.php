<?php
/* php version >= 5.3.6 */

$start = microtime(true);

if(is_file(__DIR__.'/../../../../vendor/autoload.php')){
    require __DIR__.'/../../../../vendor/autoload.php';
}elseif (!class_exists('\SsdPHP\SsdPHP')){
    require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."SsdPHP".DIRECTORY_SEPARATOR."SsdPHP.php");
}
use SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Common\Route,
    SsdPHP\Pulgins\Common\Error,
    SsdPHP\Core\Config,
    SsdPHP\SsdPHP;
if(($r = SsdPHP::Bootstrap(function (){
    date_default_timezone_set('PRC');
    RegShutdownEvent::register();
    SsdPHP::setRootPath(__DIR__.'/../../../../');
    #SsdPHP::setAppDir("App");
    SsdPHP::setDebug();
    Error::$CONSOLE =SsdPHP::isDebug();
    Config::load(SsdPHP::getRootPath().DIRECTORY_SEPARATOR.'config');
    Route::set(Config::getField('ROUTE','home',array()));

})->Run()) === false){
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    echo "404 error";
}else{
    echo $r;
}
$end = microtime(true);
echo "<!--","SsdPHP","Framwork runtime=",($end - $start),"秒","-->";