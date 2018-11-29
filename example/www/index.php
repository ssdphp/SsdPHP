<?php
if($vendorFile = realpath(__DIR__.'/../vendor/autoload.php')){
    require $vendorFile;
}
//~^(?<subdomain>.+)\.(?<model>.*)\.domain.com;
//http://ssdphp.admin.xx.com
//http://ssdphp.api.xx.com
//http://ssdphp.home.xx.com
if(($r = SsdPHP\SsdPHP::Bootstrap(function (){
        date_default_timezone_set('PRC');
        $appRoot = dirname(__DIR__);
        SsdPHP\SsdPHP::setAppDir($appRoot);
        SsdPHP\SsdPHP::setDebug(true);
        SsdPHP\Core\Error::$CONSOLE =SsdPHP\SsdPHP::getDebug();
        if (strpos($_SERVER['HTTP_HOST'], "admin") !== false) {
            \SsdPHP\SsdPHP::setDefaultModel("Admin");
        }elseif(strpos($_SERVER['HTTP_HOST'], "home") !== false) {
            \SsdPHP\SsdPHP::setDefaultModel("Home");
        }elseif(strpos($_SERVER['HTTP_HOST'], "api") !== false) {
            \SsdPHP\SsdPHP::setDefaultModel("Api");
        }else {
            exit();
        }
        //加载配置文件
        SsdPHP\Core\Config::load($appRoot."/Config");
        SsdPHP\Core\Route::set(\SsdPHP\Core\Config::getField('route','api'));

        //加载语言包
        SsdPHP\Core\Language::load($appRoot."/resources/lang/",SsdPHP\SsdPHP::getModel());
        SsdPHP\Session\Session::Start();
    })->Run()) === false){
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    echo "404 error!";
}else{
    echo $r;
}
