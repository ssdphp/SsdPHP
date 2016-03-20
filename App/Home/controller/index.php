<?php
namespace App\Home\controller;

use SsdPHP\Pulgins\DataBase\Factory as MysqlFactory,
    SsdPHP\Pulgins\Cache\Factory as CacheFactory,
    SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Http\Input,
    SsdPHP\Core\Factory as SFactory,
    SsdPHP\Pulgins\View\Factory as View,
    SsdPHP\Pulgins\Session\Factory as Session,
    SsdPHP\Pulgins\Email\Factory as EmailFactory;

class index{

    public function __construct()
    {
        //Session::Start();
    }

    public function index(){
        //数据库操作
        //$res = MysqlFactory::getInstance()->select("ip");
        //CacheFactory::getInstance()->set("ip",50);
        //RegShutdownEvent::add('\App\Task\Email::sendEmail','xzh_tx@163.com','xhh','test','body','\App\Task\Email::callback');
        //RegShutdownEvent::add('\App\Task\Sms::sendTextMessage','1888xxxxxxx','你的验证码是xxx','\App\Task\Sms::callback');
        /*echo Input::get("ni",0,"intval");
        Session::set('nihao',123);
        echo Session::get('nihao');*/
        // 创建日志频道
        //$a = new Db();
        //$a->test();
        $s = SFactory::getInstance('App\Home\model\Student')->Login("a","b");
        print_r($s);
        #View::getInstance()->assign(array('a'=>"ni","b"=>"<br>123"))->display();
    }



}