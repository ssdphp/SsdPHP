<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SsdPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2015-2020. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.SsdPHP.com                                    |
+-----------------------------------------------------------------------+
}}}*/

namespace Home\controller;

use SsdPHP\Pulgins\DataBase\Factory as MysqlFactory,
    SsdPHP\Pulgins\Cache\Factory as CacheFactory,
    SsdPHP\Pulgins\Common\RegShutdownEvent,
    SsdPHP\Pulgins\Email\Factory as EmailFactory;

class index{


    public function index(){
        /*$res = MysqlFactory::getInstance()->select("ip");
        CacheFactory::getInstance()->set("ip",50);
        RegShutdownEvent::add('Home\controller\index::taskEmail','xzh_tx@163.com','xhh','test','body');*/
        echo (123);
    }

    public static function taskEmail($toEmail,$toUser,$title,$body){
        if(!$toEmail || !$title){
            return ;
        }
        EmailFactory::getInstance()->send_mail($toEmail,$toUser,$title,$body);
    }



}