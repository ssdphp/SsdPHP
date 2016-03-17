<?php
namespace App\Home\controller;

use SsdPHP\Pulgins\DataBase\Factory as MysqlFactory,
    SsdPHP\Pulgins\Cache\Factory as CacheFactory,
    SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Http\Input,
    SsdPHP\Pulgins\View\Factory as View,
    SsdPHP\Pulgins\Session\Factory as Session,
    SsdPHP\Pulgins\Email\Factory as EmailFactory;

class index{

    public function __construct()
    {
        Session::Start();
    }

    public function index(){
        #$res = MysqlFactory::getInstance()->select("ip");
        #CacheFactory::getInstance()->set("ip",50);
        #RegShutdownEvent::add('Home\controller\index::taskEmail','xzh_tx@163.com','xhh','test','body');
        /*echo Input::get("ni",0,"intval");
        Session::set('nihao',123);
        echo Session::get('nihao');*/
        // 创建日志频道
        View::getInstance()->assign(array('a'=>"ni","b"=>"<br>123"))->display();
    }

    public static function taskEmail($toEmail,$toUser,$title,$body){
        if(!$toEmail || !$title){
            return ;
        }
        EmailFactory::getInstance()->send_mail($toEmail,$toUser,$title,$body);
    }



}