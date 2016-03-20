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
namespace SsdPHP\Pulgins\Cache\Adaptor;

class Redis
{
    private static $_redis;
    public function __construct($config=array()){
        if(!empty($config)){
            foreach($config as $host){
                $hosts[]=$host['host'].":".$host['port'];
            }
            self::$_redis = new \RedisArray($hosts);
        }else{
            throw new \Exception("Redis Config Unspecify!");
        }

    }

    public function __call($name,$args){
        return  call_user_func_array (array(self::$_redis,$name),$args);
    }
}