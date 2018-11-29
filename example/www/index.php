<?php
if($vendorFile = realpath(__DIR__.'/../vendor/autoload.php')){
    require $vendorFile;
}
//nginx config
//server_name ~^(?<subdomain>.+)\.(?<model>.*)\.domain.com;
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
            $model = "admin";
        }elseif(strpos($_SERVER['HTTP_HOST'], "home") !== false) {
            $model = "home";
        }elseif(strpos($_SERVER['HTTP_HOST'], "api") !== false) {
            $model = "api";
        }else {
            exit();
        }
        \SsdPHP\SsdPHP::setDefaultModel($model);
        //加载配置文件
        SsdPHP\Core\Config::load($appRoot."/Config");
        //加载路由配置
        SsdPHP\Core\Route::set(\SsdPHP\Core\Config::getField('route',$model));

        //加载语言包
        SsdPHP\Core\Language::load($appRoot."/resources/lang/",$model);
        SsdPHP\Session\Session::Start();
    })->Run()) === false){
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    echo "404 error!";
}else{
    echo $r;
}
