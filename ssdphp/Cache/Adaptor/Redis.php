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
namespace SsdPHP\Cache\Adaptor;
/**
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 * Class Redis
 * @package SsdPHP\Pulgins\Cache\Adaptor
 */
class Redis
{
    private static $_redis=array();
    private $guid;
    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param mixed $mix 变量
     * @return string
     */
    public function to_guid_string($mix) {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }
    /**
     * Redis constructor.
     * @param array $opt redis配置
     * @throws \Exception
     */
    public function __construct($opt=array()){

        $this->guid = $this->to_guid_string($opt);
        if(!empty($opt)){
            self::$_redis[$this->guid] = new \Redis();
            $opt['timeout'] = !empty($opt['timeout']) ? $opt['timeout']:0;

            if(!empty($opt['pconnect']) && $opt['pconnect']==true ){
                self::$_redis[$this->guid]->pconnect($opt['host'],$opt['port'],$opt['timeout']);
            }else{
                self::$_redis[$this->guid]->connect($opt['host'],$opt['port'],$opt['timeout']);
            }
            if(!empty($opt['auth'])){
                self::$_redis[$this->guid]->auth($opt['auth']);
            }
        }else{
            throw new \Exception("Redis Config Unspecify!");
        }

    }

    public function __call($name,$args){

        return  call_user_func_array (array(self::$_redis[$this->guid],$name),$args);
    }
}