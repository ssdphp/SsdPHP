<?php
namespace Home\controller;

use SsdPHP\Pulgins\DataBase\Factory as MysqlFactory,
    SsdPHP\Pulgins\Cache\Factory as CacheFactory,
    SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Email\Factory as EmailFactory;

class index{


    public function index(){
        $res = MysqlFactory::getInstance()->select("ip");
        CacheFactory::getInstance()->set("ip",50);
        #RegShutdownEvent::add('Home\controller\index::taskEmail','xzh_tx@163.com','xhh','test','body');
        echo (123);
    }

    public static function taskEmail($toEmail,$toUser,$title,$body){
        if(!$toEmail || !$title){
            return ;
        }
        EmailFactory::getInstance()->send_mail($toEmail,$toUser,$title,$body);
    }



}